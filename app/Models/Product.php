<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name', 'category_id', 'sub_category_id', 'brand_id', 'buy_price', 'price',
        'discount_type', 'discount_value', 'discount_start_date', 'discount_expiry_date',
        'stock', 'sales_count', 'slug', 'variants', 'image', 'images',
        'is_active', 'is_featured', 'is_new_arrival',
        'description', 'specifications',
    ];

    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'images' => 'array',
            'specifications' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'discount_start_date' => 'datetime',
            'discount_expiry_date' => 'datetime',
        ];
    }

    public function getHasActiveDiscountAttribute(): bool
    {
        if (! $this->discount_type || $this->discount_value <= 0) {
            return false;
        }

        $now = now();

        if ($this->discount_start_date && $this->discount_start_date->gt($now)) {
            return false;
        }

        if ($this->discount_expiry_date && $this->discount_expiry_date->lt($now)) {
            return false;
        }

        return true;
    }

    public function getHasAnyDiscountAttribute(): bool
    {
        if ($this->has_active_discount) {
            return true;
        }

        if (is_array($this->variants)) {
            $now = now();
            foreach ($this->variants as $variant) {
                if (isset($variant['combo']) && !empty($variant['discount_type']) && (float)($variant['discount'] ?? 0) > 0) {
                    $startDate = !empty($variant['discount_start']) ? \Carbon\Carbon::parse($variant['discount_start']) : null;
                    $endDate = !empty($variant['discount_end']) ? \Carbon\Carbon::parse($variant['discount_end']) : null;
                    
                    $isActive = true;
                    if ($startDate && $startDate->gt($now)) {
                        $isActive = false;
                    }
                    if ($endDate && $endDate->lt($now)) {
                        $isActive = false;
                    }
                    
                    if ($isActive) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function scopeFrontendActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('discount_expiry_date')
                    ->orWhere('discount_expiry_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('discount_start_date')
                    ->orWhere('discount_start_date', '<=', now());
            });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(ProductLandingPage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    protected static function booted()
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->name)));
            }
            $product->syncStock();
        });

        static::updating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->name)));
            }
            $product->syncStock();
        });
    }

    public function syncStock()
    {
        if (!empty($this->variants) && is_array($this->variants)) {
            $totalStock = 0;
            foreach ($this->variants as $variant) {
                if (isset($variant['combo'])) {
                    $totalStock += (int)($variant['stock'] ?? 0);
                }
            }
            $this->stock = $totalStock;
        }
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews_avg_rating ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) ($this->reviews_count ?? 0);
    }

    public function adjustStock(int $quantity, ?array $orderItemVariants = null)
    {
        if (empty($this->variants) || empty($orderItemVariants)) {
            $this->stock += $quantity;
            $this->save();
            return;
        }

        $variants = $this->variants;
        $matched = false;
        
        $itemVariantsForMatch = collect($orderItemVariants)
            ->reject(fn ($val, $key) => str_starts_with($key, '_'))
            ->all();

        $totalStock = 0;
        foreach ($variants as &$v) {
            if (isset($v['combo'])) {
                $isMatch = true;
                foreach ($v['combo'] as $k => $val) {
                    if (!isset($itemVariantsForMatch[$k]) || $itemVariantsForMatch[$k] !== $val) {
                        $isMatch = false;
                        break;
                    }
                }
                
                if ($isMatch && count($v['combo']) === count($itemVariantsForMatch)) {
                    $v['stock'] = max(0, (int)($v['stock'] ?? 0) + $quantity);
                    $matched = true;
                }
                
                $totalStock += (int)($v['stock'] ?? 0);
            }
        }
        
        if ($matched) {
            $this->variants = $variants;
            $this->stock = $totalStock;
            $this->save();
        } else {
            $this->stock += $quantity;
            $this->save();
        }
    }
}
