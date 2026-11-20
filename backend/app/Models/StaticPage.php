<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'kicker',
        'title',
        'highlight',
        'subtitle',
        'content',
        'meta_description',
        'image',
        'image_alt',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function slugOptions(): array
    {
        return [
            'about' => 'Tentang Kami',
            'help' => 'Halaman Bantuan',
            'terms' => 'Syarat & Ketentuan',
            'privacy' => 'Kebijakan Privasi',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function findActive(string $slug): ?self
    {
        return static::query()->active()->where('slug', $slug)->first();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
