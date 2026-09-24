<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SabitAhmad\SteadFast\DTO\OrderRequest;
use SabitAhmad\SteadFast\Facades\SteadFast;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()->with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);

        if ($perPage === 'all') {
            $orders = $query->paginate($query->count('*') ?: 15);
        } else {
            $perPage = in_array((int) $perPage, [10, 15, 20, 30, 50]) ? (int) $perPage : 15;
            $orders = $query->paginate($perPage);
        }

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', '=', 'pending', 'and')->count('*'),
            'confirmed' => Order::where('order_status', '=', 'confirmed', 'and')->count('*'),
            'delivered' => Order::where('order_status', '=', 'delivered', 'and')->count('*'),
            'cancelled' => Order::where('order_status', '=', 'cancelled', 'and')->count('*'),
            'returned' => Order::where('order_status', '=', 'returned', 'and')->count('*'),
        ];

        return view('backend.orders.index', compact('orders', 'statusCounts'));
    }

    public function bulkPrint(Request $request): View
    {
        $idsString = $request->input('ids', '');
        $ids = array_filter(explode(',', $idsString));

        if (empty($ids)) {
            abort(404, 'No orders selected.');
        }

        $orders = Order::query()->whereIn('id', $ids, 'and', false)->with('items')->latest()->get();

        return view('backend.orders.bulk-print', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('backend.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => 'nullable|in:pending,confirmed,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid',
        ]);

        $updateData = array_filter($validated, fn ($value) => ! is_null($value));

        if (isset($updateData['order_status']) && $updateData['order_status'] === 'delivered') {
            $updateData['payment_status'] = 'paid';
        }

        if ($order->payment_status === 'paid') {
            if (
                (isset($updateData['order_status']) && $updateData['order_status'] === 'pending') ||
                (isset($updateData['payment_status']) && $updateData['payment_status'] === 'pending')
            ) {
                return back()->with('error', 'Cannot change status to Pending for a Paid order.');
            }
        }

        if (! empty($updateData)) {
            $oldStatus = $order->order_status;
            $order->update($updateData);

            if (isset($updateData['order_status'])) {
                $newStatus = $updateData['order_status'];
            }
        }

        $messages = [];
        if (isset($updateData['order_status'])) {
            $messages[] = 'Order status updated to '.ucfirst($updateData['order_status']);
        }
        if (isset($updateData['payment_status'])) {
            $messages[] = 'Payment status updated to '.ucfirst($updateData['payment_status']);
        }

        $message = empty($messages) ? 'Order updated.' : implode(' and ', $messages);

        return back()->with('success', $message);
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function sendToSteadfast(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:orders,id',
        ]);

        $orders = Order::query()->whereIn('id', $validated['ids'], 'and', false)->whereIn('order_status', ['pending', 'confirmed'])->with('items')->get();

        $successCount = 0;
        $errors = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            try {
                // Clean the phone number to 11 digits starting with 01
                $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if (str_starts_with($phone, '8801') && strlen($phone) === 13) {
                    $phone = substr($phone, 2);
                } elseif (str_starts_with($phone, '01') && strlen($phone) === 11) {
                    // Already correct
                } else {
                    if (strlen($phone) === 10 && str_starts_with($phone, '1')) {
                        $phone = '0'.$phone;
                    }
                }

                // Prepare item details
                $productName = $order->items->first()?->product_name ?? 'Multiple Products';
                if ($order->items->count() > 1) {
                    $productName = $order->items->count().' items (incl. '.$productName.')';
                }

                $productName = substr($productName, 0, 100);

                $orderData = [
                    'invoice' => $order->invoice_no,
                    'recipient_name' => substr($order->customer_name, 0, 99),
                    'recipient_phone' => $phone,
                    'recipient_address' => substr($order->customer_address, 0, 249),
                    'cod_amount' => $order->payment_status === 'paid' ? 0.00 : (float) $order->total,
                    'note' => 'Deliver securely. Contact client.',
                ];

                $orderRequest = OrderRequest::fromArray($orderData);
                $response = SteadFast::createOrder($orderRequest);

                $oldStatus = $order->order_status;
                $order->update([
                    'order_status' => 'delivered',
                    'payment_status' => $order->payment_status === 'paid' ? 'paid' : 'pending',
                ]);


                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Order #{$order->invoice_no}: ".$e->getMessage();
            }
        }

        if ($successCount > 0 && count($errors) === 0) {
            $message = "Successfully sent {$successCount} order(s) to Steadfast Courier!";
            session()->flash('success', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return redirect()->back();
        } elseif ($successCount > 0) {
            $message = "Sent {$successCount} order(s) successfully. Some orders failed:\n".implode("\n", $errors);
            session()->flash('success', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return redirect()->back();
        } else {
            $message = "Failed to send orders to Steadfast:\n".implode("\n", $errors);
            session()->flash('error', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ]);
            }

        }
    }

    public function export(Request $request)
    {
        $query = Order::query()->with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        $filename = "orders_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Invoice No',
            'Customer Name',
            'Customer Phone',
            'Address',
            'Products',
            'Total Amount',
            'Order Status',
            'Payment Status',
            'Order Date'
        ];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // add BOM for UTF-8 compatibility
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $products = [];
                foreach ($order->items as $item) {
                    $products[] = $item->product_name . ' (Qty: ' . $item->quantity . ')';
                }

                $row = [
                    $order->invoice_no,
                    $order->customer_name,
                    '="' . $order->customer_phone . '"',
                    $order->customer_address,
                    implode(' | ', $products),
                    $order->total,
                    ucfirst($order->order_status),
                    ucfirst($order->payment_status),
                    $order->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = Order::query()->with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.orders.pdf', compact('orders'))->setPaper('a4', 'landscape');
        return $pdf->download('orders_export_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
