<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'price',
        'quantity',
        'variants',
        'line_total',
    ];

    /**
     * @return array{variants: 'array'}
     */
    protected function casts(): array
    {
        return [
            'variants' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedVariantsAttribute(): string
    {
        $vars = $this->variants;
        if (!is_array($vars) || empty($vars)) {
            return '';
        }

        $formatted = [];
        if (array_is_list($vars)) {
            foreach ($vars as $vArr) {
                if (is_array($vArr)) {
                    $temp = [];
                    foreach ($vArr as $k => $v) {
                        if ($k !== '_sku' && !is_array($v)) {
                            $temp[] = ucfirst($k) . ': ' . $v;
                        }
                    }
                    if (!empty($temp)) {
                        $formatted[] = '[' . implode(', ', $temp) . ']';
                    }
                }
            }
        } else {
            foreach ($vars as $k => $v) {
                if ($k !== '_sku' && !is_array($v)) {
                    $formatted[] = ucfirst($k) . ': ' . $v;
                }
            }
        }

        return implode(' · ', $formatted);
    }
}
