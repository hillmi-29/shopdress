<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    public const POSITION_HERO = 'hero';

    public const POSITION_PROMO = 'promo';

    protected $fillable = [
        'position',
        'eyebrow',
        'title',
        'highlight',
        'subtitle',
        'image',
        'image_alt',
        'caption',
        'caption_sub',
        'badge',
        'badge_style',
        'cta_label',
        'cta_url',
        'secondary_label',
        'secondary_url',
        'footnote',
        'coupon_code',
        'rating_text',
        'show_rating',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'show_rating' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePosition(Builder $query, string $position): Builder
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Public URL for the uploaded banner image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    /**
     * Resolve the primary CTA: relative paths are converted to absolute URLs.
     */
    public function resolvedCtaUrl(): ?string
    {
        return $this->resolveUrl($this->cta_url);
    }

    /**
     * Resolve the secondary CTA: relative paths are converted to absolute URLs.
     */
    public function resolvedSecondaryUrl(): ?string
    {
        return $this->resolveUrl($this->secondary_url);
    }

    /**
     * Bullet points parsed from the footnote textarea (one point per line).
     *
     * @return array<int, string>
     */
    public function footnoteLines(): array
    {
        if (blank($this->footnote)) {
            return [];
        }

        return array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', $this->footnote) ?: [],
        )));
    }

    protected function resolveUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://', '#', 'mailto:', 'tel:'])) {
            return $url;
        }

        return url($url);
    }
}
