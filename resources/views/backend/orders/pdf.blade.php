<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Report</title>
    <style>
        @font-face {
            font-family: 'Hind Siliguri';
            font-style: normal;
            font-weight: 400;
            src: url("{{ public_path('fonts/HindSiliguri-Regular.ttf') }}") format('truetype');
        }
        body {
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Orders Report</h2>
    <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Products</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->invoice_no }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_phone }}</td>
                    <td>{{ $order->customer_address }}</td>
                    <td>
                        @foreach ($order->items as $item)
                            {{ $item->product_name }} (x{{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td>{{ number_format($order->total, 2) }}</td>
                    <td>{{ ucfirst($order->order_status) }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
