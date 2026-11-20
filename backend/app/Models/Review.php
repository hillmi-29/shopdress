<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'photos',
        'video',
        'is_visible',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'photo_urls',
        'video_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * URL publik untuk seluruh foto ulasan.
     */
    public function getPhotoUrlsAttribute(): array
    {
        return collect($this->photos ?? [])
            ->filter(fn ($path) => is_string($path) && $path !== '')
            ->map(fn (string $path) => asset('storage/'.ltrim($path, '/')))
            ->values()
            ->all();
    }

    /**
     * URL publik untuk video ulasan.
     */
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video
            ? asset('storage/'.ltrim($this->video, '/'))
            : null;
    }

    /**
     * Pelanggan hanya boleh mengulas produk yang sudah dibayar/diterima.
     */
    public static function hasVerifiedPurchase(User $user, int $productId): bool
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                OrderStatus::PAID,
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED,
                OrderStatus::COMPLETED,
            ])
            ->whereHas('items.variant', fn ($query) => $query->where('product_id', $productId))
            ->exists();
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Urutan kurasi testimoni beranda; ulasan terbaru menjadi penentu kedua.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->latest();
    }
}
