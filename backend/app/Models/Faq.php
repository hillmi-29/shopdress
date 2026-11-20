<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    public const PAGE_HOME = 'home';

    public const PAGE_HELP = 'help';

    protected $fillable = [
        'page',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function pageOptions(): array
    {
        return [
            self::PAGE_HOME => 'Beranda',
            self::PAGE_HELP => 'Halaman Bantuan',
        ];
    }

    public static function pageLabel(string $page): string
    {
        return self::pageOptions()[$page] ?? ucfirst($page);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Konten FAQ lama dipakai sebagai fallback tampilan sekaligus sumber
     * data seeder, jadi teks frontend tetap sama sebelum admin mengubahnya.
     *
     * @return array<int, array{q: string, a: string}>
     */
    public static function defaultsFor(string $page): array
    {
        return match ($page) {
            self::PAGE_HOME => [
                [
                    'q' => 'Bagaimana cara memilih ukuran yang tepat?',
                    'a' => 'Setiap produk dilengkapi panduan ukuran (lingkar dada, panjang baju, panjang lengan) di halaman detail. Jika ragu di antara dua ukuran, pilih yang lebih longgar atau hubungi customer care kami untuk konsultasi gratis.',
                ],
                [
                    'q' => 'Apakah bahannya menerawang?',
                    'a' => 'Tidak. Kain satin silk, linen, dan rayon premium yang kami gunakan memiliki kerapatan serat tinggi. Warna cerah seperti putih dan cream juga dilapisi furing lembut agar tetap nyaman dan aman.',
                ],
                [
                    'q' => 'Berapa lama estimasi pengiriman?',
                    'a' => 'Pesanan sebelum pukul 15.00 WIB diproses di hari yang sama. Jabodetabek 1–2 hari kerja, Pulau Jawa 2–3 hari kerja, dan luar Pulau Jawa 3–5 hari kerja.',
                ],
                [
                    'q' => 'Metode pembayaran apa saja yang tersedia?',
                    'a' => 'Pembayaran diproses aman melalui Midtrans: virtual account BCA, Mandiri, BNI, BRI, QRIS (GoPay, OVO, ShopeePay, Dana), serta kartu kredit/debit Visa dan Mastercard.',
                ],
                [
                    'q' => 'Bagaimana jika ukuran tidak pas atau produk cacat?',
                    'a' => 'Kami memberikan garansi tukar ukuran atau pengembalian produk cacat maksimal 7 hari sejak paket diterima. Sertakan foto unboxing dan hubungi WhatsApp customer care untuk diproses tanpa biaya admin.',
                ],
            ],
            self::PAGE_HELP => [
                [
                    'q' => 'Apakah bisa belanja tanpa membuat akun?',
                    'a' => 'Bisa. Kamu tetap dapat menambahkan produk ke keranjang dan checkout sebagai tamu. Namun, membuat akun memudahkanmu memantau pesanan dan menyimpan wishlist.',
                ],
                [
                    'q' => 'Bagaimana cara memilih ukuran yang tepat?',
                    'a' => 'Buka detail produk dan pilih ukuran yang tersedia. Jika ragu, bandingkan ukuran badanmu dengan panduan pada deskripsi produk atau hubungi kami sebelum membeli.',
                ],
                [
                    'q' => 'Metode pembayaran apa saja yang tersedia?',
                    'a' => 'Pembayaran diproses melalui Midtrans dan mendukung berbagai metode seperti transfer bank, virtual account, e-wallet, dan kartu.',
                ],
                [
                    'q' => 'Apakah pesanan bisa dibatalkan?',
                    'a' => 'Pesanan yang belum dibayar dapat dibatalkan melalui halaman Pesanan. Jika sudah dibayar, silakan hubungi kami untuk memeriksa kemungkinan pembatalan.',
                ],
                [
                    'q' => 'Bagaimana jika produk yang diterima rusak?',
                    'a' => 'Segera hubungi kami maksimal 7 hari setelah produk diterima dengan menyertakan foto kondisi produk dan nomor pesanan. Kami akan membantu proses penggantian.',
                ],
            ],
            default => [],
        };
    }
}
