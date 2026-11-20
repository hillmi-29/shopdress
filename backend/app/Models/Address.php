<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'recipient_phone',
        'province_code',
        'province_name',
        'regency_code',
        'regency_name',
        'district_code',
        'district_name',
        'village_code',
        'village_name',
        'postal_code',
        'rt',
        'rw',
        'street',
        'landmark_photo',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $appends = [
        'full_address',
        'landmark_photo_url',
        'landmark_photo_urls',
        'landmark_photos',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get list of storage paths for landmark photos (supports single path or JSON array).
     */
    public function getLandmarkPhotosAttribute(): array
    {
        if (empty($this->landmark_photo)) {
            return [];
        }

        if (is_array($this->landmark_photo)) {
            return $this->landmark_photo;
        }

        $decoded = json_decode((string) $this->landmark_photo, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded, 'is_string'));
        }

        return [$this->landmark_photo];
    }

    /**
     * Get list of public URLs for all landmark photos.
     */
    public function getLandmarkPhotoUrlsAttribute(): array
    {
        return array_values(array_map(fn ($path) => asset('storage/'.$path), $this->landmark_photos));
    }

    /**
     * Get the public URL for the first landmark photo if present.
     */
    public function getLandmarkPhotoUrlAttribute(): ?string
    {
        return $this->landmark_photo_urls[0] ?? null;
    }

    /**
     * Compose the address string used on cards and checkout.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];

        if ($this->street) {
            $parts[] = $this->street;
        }

        $rtRw = '';
        if ($this->rt && $this->rw) {
            $rtRw = "RT {$this->rt}/RW {$this->rw}";
        } elseif ($this->rt) {
            $rtRw = "RT {$this->rt}";
        } elseif ($this->rw) {
            $rtRw = "RW {$this->rw}";
        }
        if ($rtRw) {
            $parts[] = $rtRw;
        }

        if ($this->village_name) {
            $parts[] = 'Desa/Kel. '.$this->village_name;
        }
        if ($this->district_name) {
            $parts[] = 'Kec. '.$this->district_name;
        }
        if ($this->regency_name) {
            $parts[] = $this->regency_name;
        }
        if ($this->province_name) {
            $parts[] = $this->province_name;
        }
        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }

        return implode(', ', $parts);
    }
}
