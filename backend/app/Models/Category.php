<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[ObservedBy([CategoryObserver::class])]
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Count only products that customers can currently see.
     */
    public function scopeWithActiveProductCount(Builder $query): Builder
    {
        return $query->withCount([
            'products' => fn (Builder $productQuery) => $productQuery->where('is_active', true),
        ]);
    }

    /**
     * Order categories by units sold in settled orders, then by active product count.
     */
    public function scopeOrderByPopularity(Builder $query): Builder
    {
        $soldCount = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->whereColumn('products.category_id', 'categories.id')
            ->where('orders.payment_status', PaymentStatus::SETTLED->value)
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0)');

        $activeProductCount = Product::query()
            ->whereColumn('products.category_id', 'categories.id')
            ->where('is_active', true)
            ->selectRaw('COUNT(*)');

        return $query->orderByDesc($soldCount)
            ->orderByDesc($activeProductCount)
            ->orderBy('categories.name');
    }
}
