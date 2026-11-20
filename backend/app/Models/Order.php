<?php

namespace App\Models;

use App\Casts\LandmarkPhotos;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'shipping_cost',
        'discount_amount',
        'grand_total',
        'status',
        'payment_status',
        'snap_token',
        'shipping_address',
        'recipient_name',
        'recipient_phone',
        'tracking_number',
        'notes',
        'landmark_photo',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'landmark_photo' => LandmarkPhotos::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
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
}
