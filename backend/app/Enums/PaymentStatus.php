<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'UNPAID';
    case EXPIRED = 'EXPIRED';
    case SETTLED = 'SETTLED';
    case FAILED = 'FAILED';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Dibayar',
            self::EXPIRED => 'Kedaluwarsa',
            self::SETTLED => 'Berhasil / Lunas',
            self::FAILED => 'Gagal',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'warning',
            self::EXPIRED => 'gray',
            self::SETTLED => 'success',
            self::FAILED => 'danger',
        };
    }
}
