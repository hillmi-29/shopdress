# ShopDress — Backend

Aplikasi Laravel 11 untuk ShopDress (e-commerce busana wanita): storefront customer,
REST API, admin panel Filament, checkout Midtrans, dan sistem pembayaran/email.

Dokumentasi proyek lengkap ada di [`../README.md`](../README.md).

## Kebutuhan

- PHP 8.2+ dengan ekstensi `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`
- Composer 2
- Node.js 20+ dan npm
- SQLite (default) atau MySQL/MariaDB

## Setup

```bash
cd backend
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
touch database/database.sqlite  # Windows: New-Item database\database.sqlite
php artisan migrate --seed
npm install
npm run build
```

Pastikan `database/regions.sqlite` tersedia. File ini berisi data wilayah
administratif Indonesia (provinsi, kabupaten/kota, kecamatan, desa/kelurahan,
kode pos) yang dipakai endpoint `/api/regions/*` dan dropdown alamat checkout.

## Menjalankan

```bash
composer dev
```

Perintah tersebut menjalankan server Laravel, queue listener, log viewer,
dan Vite dev server secara bersamaan. Alternatif manual:

```bash
php artisan serve      # http://localhost:8000
npm run dev            # asset development
php artisan queue:work # proses email/queue
```

## Perintah Penting

| Perintah | Keterangan |
| --- | --- |
| `php artisan test` | Menjalankan seluruh unit & feature test |
| `npm run build` | Build asset produksi (Tailwind/Vite) |
| `php artisan migrate` | Menjalankan migrasi database |
| `php artisan db:seed` | Mengisi data awal |
| `php artisan optimize:clear` | Membersihkan cache config/route/view |

## Struktur Utama

```text
app/
├── Filament/            # Admin panel (resource, page, widget)
├── Http/
│   ├── Controllers/Api/       # REST API
│   ├── Controllers/Frontend/  # Halaman customer
│   ├── Controllers/Webhooks/  # Midtrans webhook
│   └── Requests/              # Validasi form
├── Models/              # Model Eloquent
├── Services/            # Logika bisnis (cart, checkout, inventory, region, Midtrans)
└── ...
resources/views/
├── frontend/            # Blade storefront (home, produk, cart, checkout, akun)
├── filament/            # Kustomisasi tampilan admin
├── emails/              # Template email transaksi
└── vendor/              # Override view package
```

## Konfigurasi Integrasi

| Variabel `.env` | Keterangan |
| --- | --- |
| `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY` | Kredensial Midtrans Snap |
| `MIDTRANS_IS_PRODUCTION` | `false` untuk sandbox |
| `DB_CONNECTION`, `DB_DATABASE` | Koneksi database (default SQLite) |
| `QUEUE_CONNECTION` | Driver queue untuk email transaksi |

> Kredensial Midtrans bawaan masih placeholder. Checkout akan berhenti dengan
> pesan aman sampai kredensial sandbox/production diisi.

## Admin Panel

Panel admin Filament tersedia di `/admin`.

```bash
php artisan make:filament-user
```
