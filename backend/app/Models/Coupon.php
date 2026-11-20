<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type', // fixed / percent
        'discount_amount',
        'min_spend',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function (Builder $query) {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    /**
     * Kupon aktif pertama dipakai sebagai sumber tunggal untuk semua
     * referensi promo di storefront (hero, banner, keranjang, tentang).
     */
    public static function firstActive(): ?self
    {
        return static::query()->active()->orderBy('id')->first();
    }

    public function discountLabel(): string
    {
        if ($this->discount_type === 'percent') {
            return 'Diskon '.$this->formattedAmount($this->discount_amount).'%';
        }

        return 'Potongan Rp'.$this->formattedAmount($this->discount_amount);
    }

    public function promoHighlight(): string
    {
        if ($this->discount_type === 'percent') {
            return $this->formattedAmount($this->discount_amount).'%';
        }

        return 'Rp'.$this->formattedAmount($this->discount_amount);
    }

    public function promoTitle(): string
    {
        if ($this->discount_type === 'percent') {
            return 'Diskon '.$this->promoHighlight().' untuk pesananmu';
        }

        return 'Potongan '.$this->promoHighlight().' untuk pesananmu';
    }

    public function promoDescription(): string
    {
        if ($this->discount_type === 'percent') {
            return 'Gunakan kode ini saat checkout dan nikmati potongan '.$this->promoHighlight().' dari subtotal belanjamu.';
        }

        return 'Gunakan kode ini saat checkout untuk potongan langsung '.$this->promoHighlight().'.';
    }

    public function minimumLabel(): string
    {
        if ((float) $this->min_spend <= 0) {
            return 'Tanpa minimum belanja';
        }

        return 'Min. belanja Rp'.$this->formattedAmount($this->min_spend);
    }

    public function promoNote(): string
    {
        if (! $this->expires_at) {
            return 'Berlaku selama kuota kupon tersedia.';
        }

        return 'Berlaku hingga '.$this->expires_at->locale('id')->translatedFormat('j F Y');
    }

    private function formattedAmount(float|string|null $value): string
    {
        $number = (float) $value;

        return rtrim(rtrim(number_format($number, 2, ',', '.'), '0'), ',');
    }

    public function isValid(float $totalAmount): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($totalAmount < $this->min_spend) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $totalAmount): float
    {
        if (! $this->isValid($totalAmount)) {
            return 0.0;
        }

        if ($this->discount_type === 'percent') {
            return round(($totalAmount * $this->discount_amount) / 100, 2);
        }

        return min((float) $this->discount_amount, $totalAmount);
    }
}
