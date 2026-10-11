# 🛍️ ShopDress - E-Commerce Toko Baju Online

Dokumentasi arsitektur, arsitektur basis data, alur transaksi, dan roadmap pengerjaan untuk proyek e-commerce jualan baju online **ShopDress**.

---

## 🛠️ 1. Tech Stack & Library

* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Admin Dashboard Panel:** Filament v3 (FilamentPHP)
* **Frontend UI:** Blade Templates + Alpine.js + Tailwind CSS
* **Database Management:** MySQL / MariaDB
* **Authentication:** Laravel Breeze (Customer) + Filament Native Auth (Admin)
* **Payment Gateway:** Midtrans Snap API (`midtrans/midtrans-php`)
* **Email System:** Laravel Mailable + Queue Worker (Database Queue Driver)
* **Media & File Storage:** Spatie MediaLibrary / Filament Native File Upload

---

## 📂 2. Struktur Direktori Proyek

```text
shopdress/
├── app/
│   ├── Enums/
│   │   ├── OrderStatus.php         # PENDING, PAID, PROCESSING, SHIPPED, COMPLETED, CANCELLED
│   │   └── PaymentStatus.php       # UNPAID, EXPIRED, SETTLED, FAILED
│   ├── Filament/                   # Admin Panel (Filament)
│   │   ├── Resources/
│   │   │   ├── CategoryResource.php
│   │   │   ├── ProductResource.php
│   │   │   ├── OrderResource.php
│   │   │   ├── UserResource.php
│   │   │   └── CouponResource.php
│   │   └── Widgets/
│   │       ├── StatsOverviewWidget.php
│   │       └── LatestOrdersWidget.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── MidtransWebhookController.php
│   │   │   ├── WishlistController.php
│   │   │   └── UserDashboardController.php
│   ├── Mail/
│   │   ├── OrderCreatedMail.php
│   │   ├── PaymentSuccessMail.php
│   │   └── OrderStatusUpdatedMail.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Product.php
│   │   ├── ProductVariant.php    # Size + Color + Stock SKU
│   │   ├── ProductImage.php
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Review.php
│   │   ├── Wishlist.php
│   │   └── Coupon.php
│   └── Services/
│       ├── CartService.php
│       ├── InventoryService.php
│       └── MidtransService.php
├── config/
│   └── midtrans.php
├── database/
│   ├── migrations/
│   └── seeders/
└── resources/
    └── views/
        ├── components/
        ├── layouts/
        ├── pages/
        │   ├── home.blade.php
        │   ├── products/
        │   ├── cart.blade.php
        │   ├── checkout.blade.php
        │   └── user/
        └── emails/
```

---

## 🗄️ 3. Skema Basis Data (Database Schema)

### 1. `users`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `name` | String | Nama lengkap |
| `email` | String | Unique email |
| `password` | String | Hashed password |
| `phone` | String (Nullable) | Nomor HP / WhatsApp |
| `address` | Text (Nullable) | Alamat pengiriman utama |
| `role` | Enum | `customer`, `admin` |

### 2. `categories`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `name` | String | Nama kategori (misal: Gamis, Kaos, Kemeja) |
| `slug` | String | Unique URL slug |
| `image` | String (Nullable) | Path gambar thumbnail |
| `description` | Text (Nullable) | Deskripsi singkat kategori |

### 3. `products`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `category_id` | Foreign ID | Relasi ke `categories` |
| `name` | String | Nama produk baju |
| `slug` | String | Unique URL slug |
| `description` | Text | Deskripsi produk lengkap |
| `price` | Decimal (12,2) | Harga dasar produk |
| `is_active` | Boolean | Status tampil di katalog (Default: `true`) |
| `featured` | Boolean | Produk unggulan halaman depan (Default: `false`) |

### 4. `product_variants` (Tracking Stok per Ukuran & Warna)
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `product_id` | Foreign ID | Relasi ke `products` |
| `sku` | String | Stock Keeping Unit unik |
| `size` | String | Ukuran (S, M, L, XL, XXL) |
| `color` | String | Warna (Hitam, Putih, Navy, dll) |
| `stock` | Integer | Jumlah stok tersedia |
| `additional_price` | Decimal (12,2) | Tambahan harga (Opsional, Default: 0) |

### 5. `product_images`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `product_id` | Foreign ID | Relasi ke `products` |
| `image_path` | String | Path file gambar |
| `is_primary` | Boolean | Penanda gambar utama |

### 6. `carts` & `cart_items`
- **`carts`**: `id`, `user_id` (Nullable for guest), `session_id`
- **`cart_items`**: `id`, `cart_id`, `product_variant_id`, `quantity`

### 7. `orders` & `order_items`
- **`orders`**:
  - `id`, `order_number` (KODE: `ORD-YYYYMMDD-XXXX`), `user_id`
  - `total_amount`, `shipping_cost`, `grand_total`
  - `status` (`PENDING`, `PAID`, `PROCESSING`, `SHIPPED`, `COMPLETED`, `CANCELLED`)
  - `payment_status` (`UNPAID`, `EXPIRED`, `SETTLED`, `FAILED`)
  - `snap_token`, `shipping_address`, `notes`
- **`order_items`**:
  - `id`, `order_id`, `product_variant_id`, `product_name`
  - `variant_details` (JSON: `{ "size": "L", "color": "Navy" }`)
  - `price`, `quantity`, `subtotal`

### 8. `reviews`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigIncrements | Primary Key |
| `user_id` | Foreign ID | Pembeli |
| `product_id` | Foreign ID | Produk yang diulas |
| `rating` | TinyInteger | Skala 1 s.d. 5 |
| `comment` | Text (Nullable) | Ulasan tekstual |

### 9. `wishlists`
- `id`, `user_id`, `product_id`

### 10. `coupons`
- `id`, `code`, `discount_type` (`fixed`/`percent`), `discount_amount`, `min_spend`, `expires_at`, `usage_limit`

---

## 🔄 4. Workflows & Logika Sistem

### A. Alur Checkout & Manajemen Stok
1. Customer memilih produk, memilih **Ukuran & Warna** (Product Variant), lalu memasukkan ke keranjang.
2. Sistem memverifikasi stok ketersediaan (`product_variants.stock >= quantity`).
3. Saat Customer checkout & dialihkan ke Midtrans Snap:
   - Order disimpan di DB dengan status `PENDING` dan payment status `UNPAID`.
   - Stok dipotong sementara (*Reserved Stock* via `InventoryService::reserveStock()`).
4. **Pembayaran Sukses** (Midtrans Webhook `settlement` / `capture`):
   - Status Order diubah menjadi `PAID`.
   - Email Konfirmasi dikirim ke Customer melalui Queue Worker.
5. **Pembayaran Kedaluwarsa/Gagal/Dibatalkan** (`expire` / `cancel` / `deny`):
   - Status Order diubah menjadi `CANCELLED`.
   - Stok yang sebelumnya dipotong **dikembalikan otomatis** (`InventoryService::restoreStock()`).

### B. Integrasi Midtrans Webhook
- Webhook Handler menerima callback HTTP POST dari Midtrans.
- Verifikasi *Signature Key* untuk memastikan keaslian request dari Midtrans:
  $$\text{Signature} = \text{SHA512}(\text{order\_id} + \text{status\_code} + \text{gross\_amount} + \text{ServerKey})$$
- Proses update status transaksi bersifat *idempotent* (mencegah komputasi ganda jika callback dikirim ulang oleh Midtrans).

---

## 📅 5. Roadmap Pengerjaan 10 Minggu

### Minggu 1: Setup Proyek & Autentikasi
- Install Laravel 11, konfigurasi `.env` database MySQL.
- Install Laravel Breeze untuk autentikasi customer (Login, Register, Reset Password).
- Install Filament v3 (`composer require filament/filament`).
- Middleware pemisah akses customer dan admin panel.

### Minggu 2: Skema Database, Migration & Seeder
- Buat semua file migration sesuai skema basis data.
- Definisikan relasi Eloquent (`hasMany`, `belongsTo`, `hasManyThrough`).
- Buat Factory & Seeder data dummy (kategori, produk, varian stok, admin & user customer).

### Minggu 3: Katalog Produk, Varian & Filter
- Buat Layout Utama Frontend (Navbar, Footer, Hero Banner).
- Halaman Home & Katalog Produk.
- Filter produk berbasis Kategori, Ukuran, Warna, Rentang Harga, dan Sorting.
- Halaman Detail Produk (Galeri foto, selector varian warna/ukuran, status stok real-time).

### Minggu 4: Keranjang Belanja (Cart) & Wishlist
- Fitur Add to Cart (Session Cart untuk Guest, Database Cart untuk User Terautentikasi).
- Halaman Keranjang Belanja (Update kuantitas, hapus item, kalkulasi total).
- Fitur Wishlist (Simpan & hapus produk favorit).

### Minggu 5: Integrasi Midtrans Payment Gateway & Checkout Flow
- Registrasi akun Midtrans Sandbox & konfigurasi Server Key / Client Key.
- Buat `MidtransService` untuk komunikasi Snap API & membuat Snap Token.
- Halaman Checkout: Form Alamat, Ringkasan Produk, Popup Snap Payment.
- Buat `MidtransWebhookController` penanganan callback pembayaran.

### Minggu 6: Dashboard Customer Area
- Halaman Profil Customer (Edit data diri & alamat default).
- Halaman Riwayat Pesanan (Daftar pesanan, detail status, tombol bayar ulang jika token masih berlaku).
- Fitur Ulasan Produk (Input rating & ulasan untuk produk yang sudah berstatus `PAID`).

### Minggu 7: Filament Admin Panel - Manajemen Produk & Stok
- Setup Filament Resources: `CategoryResource`, `ProductResource`.
- Form Manajemen Produk dilengkapi Repeater untuk Varian (`ProductVariants`: Size, Color, Stock SKU) & Upload Multi Gambar.
- Custom Column & Badge indikator stok habis/menipis di tabel admin.

### Minggu 8: Filament Admin Panel - Manajemen Pesanan & Analytics
- Setup `OrderResource` di Filament (Filter status, ubah status pengiriman, input nomor resi).
- Setup `CouponResource` untuk pengelolaan kode voucher diskon.
- Dashboard Analytics Widget: Total Pendapatan, Pesanan Hari Ini, Produk Terlaris, Grafik Penjualan Bulanan.

### Minggu 9: Notifikasi Email (Queue) & Integration Testing
- Pembuatan Blade Email Template & Mailable:
  - Email Pesanan Dibuat (`OrderCreatedMail`)
  - Email Pembayaran Sukses (`PaymentSuccessMail`)
  - Email Update Status Pengiriman (`OrderStatusUpdatedMail`)
- Uji coba pengiriman email asinkron dengan `php artisan queue:work`.
- End-to-End Integration Testing (Pilih varian -> Cart -> Checkout -> Midtrans Simulator -> Webhook -> Stok Terpotong -> Email Terkirim).

### Minggu 10: Finishing UI/UX & Refinements
- Optimasi tampilan responsive mobile & tablet.
- Handling error notification (Flash Message / Toast Notification).
- Final Code Review & Persiapan Deployment.
