<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Seed the homepage hero slides and promo banner with the existing
     * editorial images so the dynamic homepage matches the previous design.
     */
    public function run(): void
    {
        $image = function (string $filename, string $source): string {
            $path = 'banners/'.$filename;

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, file_get_contents(public_path('images/'.$source)));
            }

            return $path;
        };

        $slides = [
            [
                'position' => Banner::POSITION_HERO,
                'sort_order' => 1,
                'eyebrow' => 'Koleksi 2026',
                'title' => 'Temukan dress yang membuatmu merasa {highlight}',
                'highlight' => 'paling percaya diri',
                'subtitle' => 'Potongan anggun, bahan premium yang sejuk, dan detail rapi — dirancang untuk menemani setiap langkahmu dengan nyaman.',
                'image' => $image('hero-1.jpg', 'editorial/hero-1.jpg'),
                'image_alt' => 'Dress putih elegan dari koleksi terbaru ShopDress',
                'caption' => 'The Soft Ivory Edit',
                'caption_sub' => 'Satin flow dress',
                'badge' => 'New arrival',
                'badge_style' => 'light',
                'cta_label' => 'Belanja Sekarang',
                'cta_url' => '/produk',
                'secondary_label' => 'Lihat promo bulan ini',
                'secondary_url' => '/promo',
                'show_rating' => true,
                'rating_text' => '4.9/5 dari 1.200+ pelanggan',
                'is_active' => true,
            ],
            [
                'position' => Banner::POSITION_HERO,
                'sort_order' => 2,
                'eyebrow' => 'Penawaran pelanggan baru',
                'title' => 'Diskon {highlight} untuk belanja pertamamu',
                'highlight' => '20%',
                'subtitle' => 'Gunakan kode SHOPDRESSNEW saat checkout. Minimum belanja Rp150.000.',
                'coupon_code' => 'SHOPDRESSNEW',
                'image' => $image('hero-2.jpg', 'editorial/hero-2.jpg'),
                'image_alt' => 'Dress ruffle pink dari koleksi ShopDress',
                'caption' => 'The Blush Edit',
                'caption_sub' => 'Soft ruffle midi',
                'badge' => 'Hemat 20%',
                'badge_style' => 'accent',
                'cta_label' => 'Klaim Promo',
                'cta_url' => '/promo',
                'secondary_label' => 'Lihat koleksi dulu',
                'secondary_url' => '/produk',
                'footnote' => "Tanpa minimum merek\nBerlaku untuk semua kategori",
                'is_active' => true,
            ],
            [
                'position' => Banner::POSITION_HERO,
                'sort_order' => 3,
                'eyebrow' => 'Belanja tanpa ragu',
                'title' => 'Gratis ongkir & garansi retur 7 hari',
                'subtitle' => 'Bebas coba di rumah. Kalau ukuran belum pas, tukar dengan mudah dalam 7 hari — tanpa biaya admin tambahan.',
                'image' => $image('hero-3.jpg', 'editorial/hero-3.jpg'),
                'image_alt' => 'Koleksi dress floral ShopDress',
                'caption' => 'The Garden Edit',
                'caption_sub' => 'Floral everyday dress',
                'badge' => 'Retur 7 hari',
                'badge_style' => 'light_check',
                'cta_label' => 'Mulai Belanja',
                'cta_url' => '/produk',
                'secondary_label' => 'Pelajari layanan',
                'secondary_url' => '/bantuan',
                'footnote' => "Dikirim dari Jakarta\nPacking eksklusif",
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Banner::firstOrCreate(
                [
                    'position' => $slide['position'],
                    'sort_order' => $slide['sort_order'],
                ],
                $slide,
            );
        }

        Banner::firstOrCreate(
            [
                'position' => Banner::POSITION_PROMO,
                'sort_order' => 1,
            ],
            [
                'position' => Banner::POSITION_PROMO,
                'sort_order' => 1,
                'eyebrow' => 'Promo pelanggan baru',
                'title' => 'Siap menemukan dress favoritmu?',
                'highlight' => 'Diskon 20% untuk belanja pertama.',
                'subtitle' => 'Gunakan kode di bawah saat checkout. Berlaku untuk semua koleksi dengan minimum belanja Rp150.000.',
                'coupon_code' => 'SHOPDRESSNEW',
                'image' => $image('promo.jpg', 'editorial/promo.jpg'),
                'image_alt' => 'Pelanggan ShopDress membawa tas belanja',
                'badge' => '20%',
                'cta_label' => 'Lihat semua promo',
                'cta_url' => '/promo',
                'footnote' => '*Syarat dan ketentuan berlaku. Tidak dapat digabung dengan promo lain.',
                'is_active' => true,
            ],
        );
    }
}
