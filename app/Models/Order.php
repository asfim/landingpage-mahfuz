<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $decreasing = ['confirmed', 'delivered'];
            if (in_array($order->order_status, $decreasing)) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->adjustStock(-$item->quantity, $item->variants);
                    }
                }
            }
        });

        static::updated(function (Order $order) {
            if ($order->isDirty('order_status')) {
                $oldStatus = $order->getOriginal('order_status');
                $newStatus = $order->order_status;

                $decreasing = ['confirmed', 'delivered'];

                $wasDecreased = in_array($oldStatus, $decreasing);
                $shouldBeDecreased = in_array($newStatus, $decreasing);

                if (! $wasDecreased && $shouldBeDecreased) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->adjustStock(-$item->quantity, $item->variants);
                        }
                    }
                } elseif ($wasDecreased && ! $shouldBeDecreased) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->adjustStock($item->quantity, $item->variants);
                        }
                    }
                }
            }
        });

        static::deleting(function (Order $order) {
            $decreasing = ['confirmed', 'delivered'];
            if (in_array($order->order_status, $decreasing)) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->adjustStock($item->quantity, $item->variants);
                    }
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'invoice_no',
        'customer_name',
        'customer_phone',
        'customer_address',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'order_status',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'tax',
        'total',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateInvoiceNo(): string
    {
        $prefix = 'INV-'.date('Ymd').'-';
        $lastOrder = self::where('invoice_no', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) str_replace($prefix, '', $lastOrder->invoice_no);

            return $prefix.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return $prefix.'0001';
    }
}
