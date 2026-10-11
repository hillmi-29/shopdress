<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case PROCESSING = 'PROCESSING';
    case SHIPPED = 'SHIPPED';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Pembayaran',
            self::PAID => 'Sudah Dibayar',
            self::PROCESSING => 'Sedang Diproses',
            self::SHIPPED => 'Dalam Pengiriman',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'success',
            self::PROCESSING => 'info',
            self::SHIPPED => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
