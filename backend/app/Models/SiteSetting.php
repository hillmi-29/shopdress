<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Nilai bawaan dipakai saat baris database belum ada, sehingga tema
     * frontend tetap tampil utuh meski pengaturan belum pernah disimpan.
     */
    public const DEFAULTS = [
        'store_name' => 'ShopDress',
        'brand_color' => '#7c3aed',
        'payment_methods' => 'BCA, MANDIRI, BNI, BRI, QRIS, GOPAY',
        'store_address' => 'Jakarta, Indonesia',
        'store_email' => 'halo@shopdress.com',
        'store_phone' => '+62 812-3456-7890',
        'whatsapp_number' => '6281234567890',
        'operational_hours' => '09.00 – 21.00 WIB',
        'operational_days' => 'Senin – Minggu',
        'help_operational_hours' => 'Senin–Sabtu, 09.00–18.00 WIB',
        'return_policy' => '7 hari tukar ukuran',
        'shipping_cost' => '20000',
        'footer_description' => 'Busana wanita modern yang dikurasi dengan bahan premium dan detail rapi — untuk menemani setiap momen, dari hari biasa sampai acara spesial.',
        'social_instagram' => '',
        'social_tiktok' => '',
        'social_facebook' => '',
        'announcement_enabled' => '0',
        'announcement_text' => '',
    ];

    public static function defaults(): array
    {
        return self::DEFAULTS;
    }

    /**
     * Seluruh pengaturan yang sudah digabung dengan nilai bawaan, ditambah
     * URL siap pakai untuk email dan WhatsApp.
     */
    public static function values(): array
    {
        try {
            $stored = static::query()->pluck('value', 'key')->all();
        } catch (Throwable) {
            // Tabel belum dimigrasikan (misalnya saat instalasi awal).
            $stored = [];
        }

        $values = array_merge(self::defaults(), $stored);

        $values['announcement_enabled'] = filter_var(
            $values['announcement_enabled'] ?? false,
            FILTER_VALIDATE_BOOLEAN,
        );

        $values['store_name'] = filled($values['store_name'] ?? null)
            ? trim((string) $values['store_name'])
            : self::DEFAULTS['store_name'];

        $values['brand_color'] = preg_match('/^#[0-9a-fA-F]{6}$/', (string) ($values['brand_color'] ?? '')) === 1
            ? $values['brand_color']
            : self::DEFAULTS['brand_color'];

        $values['shipping_cost'] = filled($values['shipping_cost'] ?? null)
            ? (int) $values['shipping_cost']
            : (int) self::DEFAULTS['shipping_cost'];

        $values['email_url'] = filled($values['store_email'] ?? null)
            ? 'mailto:'.$values['store_email']
            : null;

        $whatsapp = self::normalizeWhatsappNumber($values['whatsapp_number'] ?? null);
        $storeName = $values['store_name'] ?? self::DEFAULTS['store_name'];

        $values['whatsapp_url'] = $whatsapp ? 'https://wa.me/'.$whatsapp : null;
        $values['whatsapp_product_url'] = $whatsapp
            ? 'https://wa.me/'.$whatsapp.'?text='.rawurlencode('Halo '.$storeName.', saya ingin bertanya seputar produk')
            : null;
        $values['whatsapp_help_url'] = $whatsapp
            ? 'https://wa.me/'.$whatsapp.'?text='.rawurlencode('Halo '.$storeName.', saya ingin bertanya')
            : null;

        return $values;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::values()[$key] ?? $default;
    }

    /**
     * Nama toko dipakai di judul halaman, navbar, footer, dan email.
     */
    public static function storeName(): string
    {
        return (string) static::get('store_name', self::DEFAULTS['store_name']);
    }

    /**
     * Warna brand untuk elemen yang tidak memakai CSS tema, seperti email.
     */
    public static function brandColor(): string
    {
        $color = (string) static::get('brand_color', self::DEFAULTS['brand_color']);

        return preg_match('/^#[0-9a-fA-F]{6}$/', $color) === 1
            ? $color
            : self::DEFAULTS['brand_color'];
    }

    /**
     * Normalisasi nomor Indonesia: 0812..., 812..., +62 812... menjadi 62812...
     */
    public static function normalizeWhatsappNumber(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }
}
