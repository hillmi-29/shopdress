<?php

namespace App\Models;

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
