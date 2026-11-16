# Laporan QA End-to-End ShopDress

**Tanggal pengujian:** 12 November 2026  
**Penguji:** QA Engineer (OpenCode)  
**Aplikasi:** ShopDress — Laravel 11 + Blade + Tailwind CSS v4 + Alpine.js  
**URL:** http://localhost:8000  
**Commit:** `c288958` (termasuk perubahan frontend yang belum di-commit)  
**Status rilis:** **NO-GO** — QA-01 sampai QA-04, QA-06, QA-07, dan QA-08 sudah diperbaiki; blocker tersisa adalah kredensial Midtrans yang valid (QA-05).

> **Update 13 November 2026:** QA-01, QA-02, QA-03, QA-04, QA-06, QA-07, dan QA-08 sudah diperbaiki dan diverifikasi. Lihat bagian 9–11. QA-05 sudah diberi guard konfigurasi, tetapi tetap terbuka karena membutuhkan kunci Midtrans sandbox yang valid. Status masih **NO-GO** sampai pembayaran Midtrans diuji end-to-end.

---

## 1. Ringkasan Eksekutif

Pengujian end-to-end mencakup halaman publik, katalog, detail produk, keranjang, autentikasi web/API, wishlist, checkout, kupon, pesanan, ulasan, API, webhook Midtrans, panel admin, halaman 404, responsivitas, aksesibilitas, dan kesehatan JavaScript.

**Hasil utama:**

- **21/21 automated test lulus** (56 assertions).
- Alur katalog, produk, keranjang guest, autentikasi, wishlist, ulasan, pesanan, API katalog, webhook, admin, 404, dan responsif **berjalan baik**.
- **Checkout web selalu gagal** karena penulisan kolom yang tidak ada di tabel `order_items`. Tidak ada order yang terbentuk.
- **Keranjang guest hilang setelah login** karena proses merge memakai session ID yang sudah berubah.
- **Checkout API meninggalkan order orphan, stok berkurang, dan keranjang kosong** ketika Midtrans gagal (kunci masih placeholder).
- **Data alamat, penerima, catatan, dan kupon pada checkout web diabaikan** oleh server. Ini akan menjadi defect data integrity setelah blocker checkout diperbaiki.
- **Kunci Midtrans masih placeholder**, sehingga pembayaran nyata belum dapat diuji end-to-end.

**Rekomendasi:** tahan rilis sampai QA-05 diselesaikan dengan kredensial Midtrans sandbox yang valid, lalu ulangi regression test checkout web dan API end-to-end.

---

## 2. Lingkup dan Metode

### 2.1 Lingkup pengujian

| Area | Cakupan |
|---|---|
| Halaman publik | Homepage, hero 3 slide, loader, CTA, kategori, tentang kami, promo, bantuan, 404 |
| Katalog | Daftar produk, pencarian, filter kategori, filter harga min/max, sorting harga/nama, empty state |
| Detail produk | Varian warna/ukuran, stok, harga, gambar, ulasan, produk terkait |
| Keranjang | Tambah, merge varian sama, ubah qty, validasi stok, varian invalid, hapus, kosongkan, mini-cart |
| Autentikasi | Login web/API, registrasi web/API, auto-login, profil, logout, revokasi token, throttle |
| Wishlist | Toggle tambah/hapus via session web dan Bearer token, guest ditolak |
| Checkout web | Form alamat, penerima, telepon, catatan, kupon, submit, halaman pembayaran/sukses |
| Checkout API | Preview, validasi, kupon, pembuatan order, integrasi Midtrans |
| Kupon | Persentase, fixed, gratis ongkir, minimum spend, kode invalid, penerapan dan penghapusan di UI |
| Pesanan | Riwayat, detail, kepemilikan order, halaman pembayaran/sukses |
| Ulasan | Render ulasan, submit untuk produk yang dibeli, penolakan jika belum membeli |
| Admin | Akses panel admin, penolakan customer, redirect guest |
| API | Home, katalog, filter metadata, detail slug, 404, pagination |
| Webhook | Payload kosong, signature salah, signature valid, idempotensi |
| Non-fungsional | Responsif 390/1440, horizontal overflow, alt text, nama tombol, error JavaScript |

### 2.2 Metode

1. **Automated test** — `php artisan test` (PHPUnit).
2. **Functional HTTP test** — Python `requests` dengan session cookie, CSRF token, dan Bearer token.
3. **UI test** — Chrome headless via Chrome DevTools Protocol (CDP), desktop 1440×900 dan mobile 390×844, screenshot, console/error listener, dan pengecekan layout.
4. **Code review terarah** — untuk mengonfirmasi akar masalah checkout, merge keranjang, dan integrasi Midtrans.
5. **Verifikasi database** — SQLite via `php artisan tinker` untuk memeriksa order, item, stok, kupon, wishlist, dan review.

### 2.3 Environment

- PHP 8.2.4, Laravel 11.57.0, SQLite
- `APP_ENV=local`, `APP_DEBUG=true`
- `MAIL_MAILER=log`, `QUEUE_CONNECTION=database`
- Midtrans: `SB-Mid-server-YOUR_SERVER_KEY` dan `SB-Mid-client-YOUR_CLIENT_KEY` (placeholder)
- Data awal: 5 produk, 5 kategori, 4 user, 1 order, 3 kupon, 2 review
- Akun uji: `admin@shopdress.com`, `customer@shopdress.com`, `anisa@shopdress.com` (password: `password`)

### 2.4 Keterbatasan

- Pembayaran Midtrans nyata tidak dapat diuji karena kunci masih placeholder; yang diuji adalah pembuatan order, kegagalan Snap, dan webhook dengan signature simulasi.
- Pengiriman email tidak diverifikasi karena mailer diset `log`.
- Pengujian performa/load dan penetration test tidak termasuk lingkup.
- Browser yang diuji hanya Chromium (headless); Safari/Firefox belum diuji.

---

## 3. Hasil Pengujian

### 3.1 Automated test

```text
Tests: 21 passed (56 assertions)
Duration: 2.52s
```

Mencakup admin access, API home, cart wiring, frontend navigation, homepage, dan product detail.

### 3.2 Matriks hasil per area

| No | Area | Hasil | Catatan |
|---:|---|---|---|
| 1 | Homepage, hero, loader | **PASS** | 3 slide, loader selesai, featured products tampil; ada error render SVG (QA-06) |
| 2 | Header, navigasi, dropdown kategori | **PASS** | Teks "Kategori" ke `/kategori`, panah membuka dropdown, pill "Daftar" hanya di `xl` |
| 3 | Katalog: search, filter, sort | **PASS** | Search, kategori, min/max harga, sort asc/desc, kategori tidak dikenal → empty state |
| 4 | Detail produk & varian | **PASS** | Warna, ukuran, stok, harga, ulasan, produk terkait tampil |
| 5 | Keranjang guest & cart page | **PASS** | Add, merge duplikat, update qty, stok 422, varian invalid 422, clear, total benar |
| 6 | Merge keranjang setelah login | **FAIL** | Item guest hilang setelah login (QA-03) |
| 7 | Autentikasi web | **PASS** | Login salah/valid, registrasi, auto-login, profil, logout |
| 8 | Autentikasi API | **PASS** | Register, token, `/me`, logout/revoke, login, 401, throttle |
| 9 | Wishlist | **PASS** | Toggle add/remove via session dan Bearer; guest mendapat 401 + toast |
| 10 | Checkout web | **FAIL** | Submit selalu gagal; data form dan kupon diabaikan (QA-01, QA-02, QA-07) |
| 11 | Checkout API | **FAIL** | Order dibuat sebelum Midtrans; kegagalan meninggalkan side effect (QA-04) |
| 12 | Kupon | **PASS** | 20% = 69.800; fixed 50.000; ongkir 20.000; min spend/invalid 422; UI apply/remove dan diskon 50.000 terverifikasi; penyimpanan kupon di order web gagal (QA-02) |
| 13 | Pesanan | **PASS** | Riwayat, detail, ownership 404, halaman payment/success |
| 14 | Ulasan | **PASS** | Produk yang dibeli → 200; belum membeli → 403 |
| 15 | Admin | **PASS** | Admin → 200; customer → 403; guest → 302 |
| 16 | API katalog | **PASS** | Pagination, search, filter size/color/featured, sort, metadata, detail/404 |
| 17 | Webhook Midtrans | **PASS** | Payload kosong 400, signature salah 400, signature valid + sudah PAID → idempotent 200 |
| 18 | Halaman 404 | **PASS** | HTTP 404 nyata, kartu custom, desktop/mobile |
| 19 | Responsif | **PASS** | Tidak ada horizontal overflow di 390 dan 1440 untuk home, katalog, produk, keranjang, dan checkout; form checkout terlihat di mobile |
| 20 | Aksesibilitas & JS health | **PASS*** | Semua `<img>` punya alt, semua button punya nama; tidak ada uncaught exception; *kecuali error SVG (QA-06) |

**Catatan triage:** kegagalan awal pada harness (HTTP 419 CSRF, gambar lazy-load belum termuat, selector mini-cart mengharapkan `display: block`) bukan defect aplikasi. Setelah token CSRF dikirim, gambar di-scroll, dan pengecekan visibilitas diperbaiki, semuanya lulus. Satu kegagalan `/api/user/orders` 401 pada suite part-2 juga false negative karena request uji tidak mengirim `Referer`/konteks stateful; alur browser dan suite fungsional utama berhasil.

---

## 4. Temuan Defect

Severity: **P0 Blocker**, **P1 High**, **P2 Medium**, **P3 Low**.

### QA-01 — [P0 Blocker] Checkout web selalu gagal karena kolom `variant_color`/`variant_size` tidak ada

**Area:** Checkout web / `CartService`  
**Status:** Confirmed  
**Dampak:** 100% customer yang login tidak dapat menyelesaikan checkout. Tidak ada order yang terbentuk, sehingga seluruh funnel pembelian berhenti di langkah terakhir.

**Langkah reproduksi:**
1. Login sebagai customer.
2. Tambahkan varian produk ke keranjang.
3. Buka `GET /checkout` — halaman tampil normal.
4. Isi form dan submit `POST /checkout`.

**Hasil aktual:**
- Respons `302` kembali ke `/checkout`.
- Flash error: "Gagal memproses checkout: ...".
- Tidak ada order baru di database; transaksi di-rollback.
- Tabel `order_items` hanya memiliki kolom `variant_details`, bukan `variant_color`/`variant_size`.

**Hasil yang diharapkan:**
- Order dibuat, item tersimpan, stok berkurang, keranjang dikosongkan, lalu redirect ke halaman pembayaran.

**Akar masalah:**
`CartService::createOrderFromCart()` menulis kolom yang tidak ada:

```php
// backend/app/Services/CartService.php:161-170
OrderItem::create([
    'order_id' => $order->id,
    'product_variant_id' => $variant->id,
    'product_name' => $product->name,
    'variant_color' => $variant->color, // kolom tidak ada
    'variant_size' => $variant->size,   // kolom tidak ada
    'quantity' => $cartItem->quantity,
    'price' => $variant->final_price,
    'subtotal' => $variant->final_price * $cartItem->quantity,
]);
```

**Bukti:**
- `Schema::getColumnListing('order_items')` → `id, order_id, product_variant_id, product_name, variant_details, price, quantity, subtotal, created_at, updated_at`.
- Uji terkontrol: cart berisi 1 item → `POST /checkout` → `302 /checkout`, `GET /api/user/orders` kosong.
- Kontras dengan `Api/CheckoutController` yang sudah benar menulis `variant_details` sebagai array (`sku`, `size`, `color`).

**Rekomendasi:**
- Tulis `variant_details` sebagai array, konsisten dengan API dan model `OrderItem`.
- Tambahkan feature test untuk checkout web happy path.
- Setelah diperbaiki, ulangi uji dengan kupon dan alamat untuk memastikan QA-02 juga tertutup.

---

### QA-02 — [P1 High] Checkout web mengabaikan alamat, penerima, telepon, catatan, dan kupon

**Area:** Checkout web / `CheckoutController`  
**Status:** Confirmed (code review)  
**Dampak:** Setelah QA-01 diperbaiki, order akan tetap memiliki alamat dan penerima kosong, catatan `null`, dan diskon `0`. Customer akan ditagih harga penuh meskipun sudah menerapkan kupon. Fulfillment tidak dapat memproses pengiriman.

**Langkah reproduksi:**
1. Login dan isi keranjang.
2. Buka `/checkout`, isi alamat, penerima, telepon, catatan, dan kupon `HEMAT50RB`.
3. Submit form.
4. Periksa payload yang diterima server dan data order yang akan dibuat.

**Hasil aktual:**
- `Frontend\CheckoutController::process()` tidak menerima `Request` dan tidak membaca input form.
- `CartService::createOrderFromCart()` menulis nilai hardcoded:

```php
// backend/app/Services/CartService.php:142-155
$order = Order::create([
    // ...
    'discount_amount' => 0,
    'grand_total' => $grandTotal, // tanpa diskon
    'shipping_address' => '',
    'recipient_name' => '',
    'recipient_phone' => '',
    'notes' => null,
]);
```

- Kupon hanya dihitung di sisi client untuk tampilan; `coupon_code` tidak diproses server saat checkout web.

**Hasil yang diharapkan:**
- Server memvalidasi `CheckoutRequest`, menyimpan alamat/penerima/telepon/catatan, menghitung diskon kupon di server, menaikkan `used_count`, dan memakai grand total setelah diskon.

**Rekomendasi:**
- Ubah signature menjadi `process(CheckoutRequest $request)`.
- Teruskan data tervalidasi dan `coupon_code` ke service.
- Hitung diskon di server dengan `Coupon::isValid()` dan `calculateDiscount()`; jangan percaya nilai dari client.
- Tambahkan test yang memastikan order menyimpan alamat, kupon, dan grand total yang benar.

---

### QA-03 — [P1 High] Keranjang guest hilang setelah login

**Area:** Autentikasi / keranjang  
**Status:** Confirmed  
**Dampak:** Customer yang menambahkan produk sebagai guest lalu login/registrasi kehilangan seluruh isi keranjang. Ini memutus funnel tepat sebelum checkout dan berpotensi menurunkan konversi secara signifikan.

**Langkah reproduksi:**
1. Sebagai guest, tambahkan varian ke keranjang (qty 1).
2. Pastikan halaman `/keranjang` menampilkan 1 item.
3. Login sebagai customer.
4. Buka `/keranjang` kembali.

**Hasil aktual:**
- Sebelum login: `total_quantity = 1`, halaman keranjang menampilkan produk.
- Setelah login: halaman keranjang menampilkan **0 item**; produk hilang.
- Cart guest lama tetap ada di database dengan `user_id = null`, sedangkan cart user baru kosong.

**Akar masalah:**
`Auth::attempt()` diikuti `session()->regenerate()` mengganti session ID. `CartService::getCart()` kemudian mencari guest cart memakai `Session::getId()` yang baru, sehingga cart guest dengan session ID lama tidak ditemukan dan tidak di-merge.

```php
// backend/app/Http/Controllers/Frontend/HomeController.php:80-81
if (Auth::attempt($credentials, $remember)) {
    $request->session()->regenerate();
    // ...
}

// backend/app/Services/CartService.php:28-30
$sessionId = Session::getId(); // sudah ID baru
$guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
```

**Rekomendasi:**
- Simpan cart/session ID guest sebelum `session()->regenerate()`, lalu merge setelah login.
- Alternatif yang lebih tahan lama: gunakan cart cookie/identifier stabil yang tidak berubah saat regenerasi session.
- Terapkan juga pada alur registrasi web dan API.
- Tambahkan regression test: guest add → login → item tetap ada (qty benar, duplikat di-merge).

---

### QA-04 — [P1 High] Checkout API meninggalkan order orphan, stok berkurang, dan cart kosong saat Midtrans gagal

**Area:** Checkout API / integrasi Midtrans  
**Status:** Confirmed  
**Dampak:** Ketika pembuatan Snap token gagal, customer melihat error tetapi order `PENDING/UNPAID` tetap ada, stok sudah berkurang, dan keranjang sudah dikosongkan. Jika kupon dipakai, `used_count` juga sudah bertambah. Customer tidak bisa mengulang checkout dengan cart yang sama; stok dan laporan penjualan menjadi tidak akurat.

**Langkah reproduksi:**
1. Login via API dan tambahkan produk ke keranjang.
2. Kirim `POST /api/checkout` dengan data alamat valid.
3. Midtrans mengembalikan `401 Unauthorized` karena server key placeholder.
4. Periksa order, stok, dan isi keranjang.

**Hasil aktual:**
- Order `ORD-20261112-EE603` (id 2) terbentuk dengan status `PENDING`, `payment_status = UNPAID`, grand total 369.000.
- Stok varian 1 berkurang dari 15 menjadi 14.
- Cart user kosong.
- Log: `Midtrans Snap Error: ... HTTP status code: 401 ... {"order":"ORD-20261112-EE603"}`.
- Respons API tetap `422` dengan pesan "Gagal memproses pesanan...", meskipun order sudah tercipta.

**Akar masalah:**
Transaksi database `DB::transaction()` sudah commit untuk order, order item, reserve stock, dan clear cart. Pemanggilan `createSnapToken()` dilakukan **setelah** transaksi, di luar rollback scope:

```php
// backend/app/Http/Controllers/Api/CheckoutController.php:119-180
$order = DB::transaction(function () use (...) {
    // create order, items, reserve stock, clear cart
    return $order;
});

$snapToken = $this->midtransService->createSnapToken($order); // di luar transaksi
```

Alur web memiliki pola yang sama setelah QA-01 diperbaiki: `createOrderFromCart()` commit lalu `createSnapToken()` dipanggil di controller.

**Rekomendasi:**
- Buat order sebagai `DRAFT`/`PENDING_PAYMENT` tanpa mengurangi stok final, lalu commit stok setelah Snap token berhasil.
- Atau bungkus pembuatan order + Snap token dalam satu unit kerja dengan kompensasi eksplisit: jika Snap gagal, hapus order, kembalikan stok, pulihkan cart, dan kembalikan `used_count` kupon.
- Gunakan idempotency key agar retry tidak membuat order ganda.
- Tambahkan integration test dengan Midtrans client yang di-mock untuk failure path.

**Catatan:** Order id 2 dan seluruh data QA sudah dihapus setelah bukti dicatat; stok varian 1 dikembalikan ke 15.

---

### QA-05 — [P2 Medium] Kredensial Midtrans masih placeholder

**Area:** Konfigurasi / pembayaran  
**Status:** Confirmed  
**Dampak:** Seluruh percobaan pembayaran gagal `401`. Pembayaran nyata tidak dapat diuji dan aplikasi tidak siap produksi sampai kunci valid dipasang.

**Bukti:**
- `backend/.env`: `MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY`, `MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY`.
- Log Midtrans: `HTTP status code: 401 API response: {"error":"Unauthorized"}`.

**Rekomendasi:**
- Isi server/client key sandbox yang valid, uji Snap popup, settlement, pending, expire, cancel, dan webhook.
- Setelah lolos, ganti dengan kunci produksi melalui secret manager, bukan file yang di-commit.
- Tambahkan health check/alert jika Snap mengembalikan 401.

---

### QA-06 — [P3 Low] Path SVG di hero tidak valid → error render di Chromium

**Area:** Homepage / hero  
**Status:** Confirmed  
**Dampak:** Browser mencatat error rendering SVG. Ikon centang dan chevron dapat tampil tidak sempurna atau hilang, tergantung browser. Tidak memblokir fungsi, tetapi menurunkan kualitas visual dan menambah noise di console.

**Bukti:**
Console Chromium:

```text
rendering Error: <path> attribute d: Expected number, "…1.414l-8 8…"
rendering Error: <path> attribute d: Expected number, "…1.414L9.414…"
```

Lokasi:

- `backend/resources/views/frontend/home/hero.blade.php:204`
- `backend/resources/views/frontend/home/hero.blade.php:273`

Path saat ini:

```html
<!-- baris 204 -->
d="M16.707 5.293a1 1 0 0 1 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4…"

<!-- baris 273 -->
d="M12.707 5.293a1 1 0 0 1 1.414L9.414 10l3.293…"
```

Kedua arc kehilangan nilai `dy` setelah `1.414`.

**Rekomendasi:**
- Ubah menjadi `1.414 0l-8 8` dan `1.414 0L9.414 10`.
- Jalankan `php artisan view:clear`.
- Tambahkan pengecekan console error/HTML validation di CI agar path SVG rusak tidak lolos.

---

### QA-07 — [P2 Medium] Pesan exception internal ditampilkan ke pengguna

**Area:** Checkout web / keamanan informasi  
**Status:** Confirmed (code review)  
**Dampak:** Saat checkout gagal, pengguna menerima detail exception mentah, termasuk nama tabel/kolom database. Ini membocorkan struktur internal dan memberikan UX yang buruk.

**Bukti:**

```php
// backend/app/Http/Controllers/Frontend/CheckoutController.php:61-63
} catch (\Exception $e) {
    return back()->with('error', 'Gagal memproses checkout: '.$e->getMessage());
}
```

**Rekomendasi:**
- Tampilkan pesan generik: "Checkout gagal diproses. Silakan coba lagi atau hubungi CS."
- Log detail exception dengan konteks order/user di channel log, bukan di flash message.
- Pastikan `APP_DEBUG=false` di produksi.

---

### QA-08 — [P2 Medium, latent] Checkout web tidak memvalidasi ulang/mengunci stok

**Area:** Checkout web / inventory  
**Status:** Code review (belum dapat diuji end-to-end karena QA-01 memblokir alur)  
**Dampak:** Jika dua customer checkout varian yang sama secara bersamaan, stok dapat menjadi negatif karena alur web hanya memanggil `decrement()` tanpa `lockForUpdate()` atau pengecekan ulang.

**Bukti:**

```php
// backend/app/Services/CartService.php:172-173
// Decrease stock
$variant->decrement('stock', $cartItem->quantity);
```

API sudah memakai `InventoryService::reserveStock()` yang melakukan lock dan validasi; alur web belum.

**Rekomendasi:**
- Gunakan `InventoryService` yang sama di checkout web.
- Validasi stok di dalam transaksi dengan `lockForUpdate()`.
- Tambahkan test konkurensi/stok habis saat checkout.

---

## 5. Hal yang Sudah Berjalan Baik

- **Katalog dan pencarian** konsisten antara web dan API; filter kategori, harga, ukuran, warna, featured, serta sorting berfungsi.
- **Detail produk** menampilkan varian, stok, harga tambahan, ulasan, dan produk terkait dengan benar.
- **Keranjang guest** kuat: merge duplikat, update qty, validasi stok, dan clear berjalan.
- **Autentikasi** web dan API berjalan, termasuk revokasi token dan throttle login API.
- **Wishlist** berfungsi via session maupun Bearer token; guest mendapat respons yang tepat.
- **Kupon** dihitung benar di server: 20% = 69.800, fixed 50.000, ongkir 20.000; minimum spend dan kode invalid ditolak 422.
- **Ownership order** aman: user lain mendapat 404 untuk halaman pesanan, pembayaran, dan sukses.
- **Admin panel** hanya dapat diakses admin; customer mendapat 403, guest diarahkan ke login.
- **Webhook Midtrans** memvalidasi signature dan idempotent untuk order yang sudah PAID.
- **404 custom** mengembalikan HTTP 404 nyata dan tampil baik di desktop/mobile.
- **Responsif** tidak menunjukkan horizontal overflow di 390 px dan 1440 px.
- **Aksesibilitas dasar** baik: alt text dan nama tombol lengkap.
- **Tidak ada uncaught JavaScript exception** di halaman utama, katalog, produk, keranjang, dan checkout.

---

## 6. Rekomendasi

### Harus sebelum rilis (blocking)

1. **Perbaiki QA-01** — ganti `variant_color`/`variant_size` dengan `variant_details`.
2. **Perbaiki QA-02** — proses `CheckoutRequest`, simpan alamat/penerima/telepon/catatan, dan hitung kupon di server.
3. **Perbaiki QA-03** — merge keranjang guest sebelum/setelah regenerasi session.
4. **Perbaiki QA-04** — jangan commit order/stok/cart sebelum Snap token berhasil; sediakan kompensasi jika gagal.
5. **Isi kredensial Midtrans sandbox** dan ulangi uji pembayaran end-to-end.

### Sebelum produksi

6. Set `APP_DEBUG=false`, gunakan secret manager untuk kunci Midtrans, dan aktifkan HTTPS.
7. Jalankan queue worker untuk email/order job; uji deliverability dengan mailer nyata.
8. Terapkan QA-07 (pesan error generik) dan QA-08 (validasi stok terpusat).
9. Tambahkan monitoring error rate checkout, alert Midtrans 401/5xx, dan dashboard order `PENDING` yang menggantung.

### Kualitas jangka panjang

10. Tambahkan regression test untuk:
    - checkout web happy path + kupon + alamat,
    - guest cart merge saat login dan registrasi,
    - Midtrans failure/rollback,
    - stok habis saat checkout.
11. Tambahkan E2E smoke test di CI (Playwright/Dusk) untuk funnel: home → produk → cart → login → checkout.
12. Tambahkan validasi HTML/SVG dan pengecekan console error di pipeline.
13. Pertimbangkan idempotency key untuk pembuatan order dan retry pembayaran.

---

## 7. Bukti dan Artefak

**Screenshot (folder `C:\Users\HILLMI\AppData\Local\Temp\opencode`):**

- `qa-home-desktop.png`
- `qa-catalog-minicart-2.png`
- `qa-product-minicart.png`
- `qa-cart-desktop.png`
- `qa-checkout-coupon-final.png` — kupon HEMAT50RB aktif, diskon Rp 50.000
- `qa-checkout-full-desktop.png` — form alamat + ringkasan lengkap
- `qa-mobile-home.png`
- `qa-mobile-catalog.png`
- `qa-mobile-product.png`
- `qa-mobile-cart.png`
- `qa-mobile-checkout-full.png` — checkout mobile lengkap, tanpa overflow
- `404-final-desktop.png`, `404-final-mobile.png`
- `kategori-fixed-1440.png`, `navbar-final-1440.png`

**Raw results:**

- `qa-part2-results.json` — 33/34 pass; 1 false negative karena konteks stateful.
- `qa-ui-results.json`, `qa-ui-retest-results.json` — hasil UI dan triage.
- `qa-checkout-ui-results.json` — verifikasi checkout desktop/mobile dan kupon. 1 FAIL pada suite ini adalah ekspektasi total yang salah (cart uji berisi 4 item); diskon Rp 50.000 tetap terverifikasi.
- `qa-functional-results.json` — hasil suite fungsional (jika tersedia).
- Log Laravel: `backend/storage/logs/laravel.log` — Midtrans 401, webhook invalid signature, dll.

**Script reproduksi:**

- `qa-functional.py`, `qa-part2.py`, `qa-ui.py`, `qa-ui-retest.py`
- `qa-cart-merge.py`, `qa-web-checkout.py`, `qa-coupon-debug2.py`
- `qa-ownership-check.py`

---

## 8. Catatan Cleanup Data QA

Setelah pengujian, data QA dibersihkan dan database dikembalikan ke kondisi awal:

- User QA id 5–9 dihapus; total user kembali 4.
- Order QA id 2 beserta item dihapus; total order kembali 1.
- Cart QA id ≥ 58 dan item-nya dihapus.
- Cart asli user 2 (id 31) tetap berisi 3 item seperti semula (Aurelia, Seraphina, Ji-Woo).
- Token API QA yang dibuat hari ini dihapus; token lama (id 1–3) dipertahankan.
- Wishlist QA dibersihkan; tersisa wishlist asli user 2 → produk 1.
- Review id 1 dikembalikan ke rating 5 dan komentar asli.
- Stok varian 1 dikembalikan ke 15.
- Chrome headless di port 9224 dihentikan dan profil sementara dihapus.

**Verifikasi akhir:** 4 user, 1 order, 1 order item, 2 review, stok varian 1 = 15.

---

## 9. Pembaruan Pasca-Perbaikan — 13 November 2026

**QA-01 — Fixed.** `CartService::createOrderFromCart()` sekarang menulis `variant_details` (SKU, size, color), bukan `variant_color`/`variant_size`.

**QA-02 — Fixed.** `Frontend\CheckoutController::process()` sekarang menerima `CheckoutRequest`, dan `CartService` menyimpan `shipping_address`, `recipient_name`, `recipient_phone`, `notes`, serta menghitung diskon kupon di server. Kupon invalid ditolak sebelum order dibuat.

**QA-08 — Ikut diperbaiki.** Stok sekarang divalidasi dan dikurangi melalui `InventoryService::reserveStock()` dengan lock, bukan `decrement()` langsung.

**Regression test baru:** `backend/tests/Feature/WebCheckoutTest.php`

- Checkout web membuat order + item + `variant_details`, menyimpan alamat/penerima/telepon/catatan, menerapkan kupon, mengurangi stok, dan mengosongkan cart.
- Kupon kedaluwarsa ditolak tanpa membuat order, tanpa mengurangi stok, dan cart tetap utuh.

**Hasil verifikasi:**

```text
Tests: 23 passed (87 assertions)
```

**Uji HTTP live:** order berhasil dibuat dengan `discount_amount = 50.000`, `grand_total = 319.000`, alamat/penerima/catatan terisi, `variant_details` benar, stok 15 → 14, cart kosong. Midtrans tetap gagal 401 karena kunci placeholder (QA-05), sehingga QA-04 masih menjadi blocker tersendiri. Data uji live sudah dibersihkan; stok kembali 15 dan `used_count` kupon kembali 32.

**Status setelah perbaikan:** **masih NO-GO** karena QA-03 (merge cart guest) dan QA-04 (atomicity saat Midtrans gagal) belum diperbaiki. QA-01 dan QA-02 sudah tidak menjadi blocker.

---

## 10. Pembaruan Pasca-Perbaikan — QA-03 & QA-04 (13 November 2026)

### QA-03 — Fixed: keranjang guest bertahan setelah login/registrasi

- `CartService::getCart()` sekarang menyimpan session ID guest di session payload (`cart_session_id`). Data session bertahan saat `Auth::login()` / `session()->regenerate()` mengganti session ID, sehingga cart guest tetap bisa ditemukan.
- Listener baru `App\Listeners\MergeGuestCartOnLogin` menjalankan merge tepat setelah event `Login`. Merge juga berjalan lazy saat cart diakses, sehingga alur API/stateful dan retry tetap aman.
- Merge dilakukan dalam transaksi dengan lock, menggabungkan quantity item duplikat, membatasi quantity agar tidak melebihi stok, memindahkan item guest ke cart user, lalu menghapus cart guest dan marker session.
- Berlaku untuk login web, registrasi web, dan akses cart berikutnya. Kegagalan merge tidak memblokir login; error dicatat dan merge dapat dicoba ulang.

**Regression tests:** `backend/tests/Feature/CartMergeTest.php` (4 tests) — login, registrasi, item duplikat + batas stok, dan lazy merge.

### QA-04 — Fixed: tidak ada side effect saat Midtrans gagal

- Service baru `App\Services\CheckoutService` menjadi satu-satunya jalur checkout untuk web dan API.
- `MidtransService::generateSnapToken()` membuat Snap token **tanpa menyimpan apa pun**. Token dibuat sebelum order ditulis, sehingga kegagalan Midtrans (401, timeout, jaringan) tidak menyentuh database.
- Setelah token berhasil, order + order item + reservasi stok + pemakaian kupon + pengosongan cart di-commit dalam **satu transaksi lokal**. Jika commit gagal, semuanya rollback.
- API checkout sekarang memakai service yang sama, sehingga kupon invalid ditolak (sebelumnya diabaikan diam-diam) dan tidak ada lagi order orphan.
- `createSnapToken()` tetap tersedia untuk halaman pembayaran order yang sudah ada.

**Regression tests:** `WebCheckoutTest` (failure path) dan `ApiCheckoutTest` (success + failure path).

**Hasil verifikasi:**

```text
Tests: 30 passed (140 assertions)
```

**Uji HTTP live (13 November 2026):**

- Guest menambahkan produk → login → produk tetap tampil di `/keranjang`.
- Checkout dengan Midtrans placeholder menghasilkan redirect error, lalu diperiksa: 0 order untuk user uji, stok varian 1 tetap 15, `used_count` kupon tetap 32, cart tetap berisi item, dan cart customer 31 tetap 3 item.
- Data user/cart uji sudah dibersihkan; state database kembali seperti semula.

**Status setelah perbaikan:** QA-03 dan QA-04 tidak lagi menjadi blocker. Rilis tetap **NO-GO** karena QA-05 (kunci Midtrans placeholder), QA-06 (SVG hero rusak), dan QA-07 (pesan exception internal bocor).

---

## 11. Pembaruan Pasca-Perbaikan — QA-05, QA-06, QA-07 (13 November 2026)

### QA-07 — Fixed: exception internal tidak lagi bocor ke pengguna

- Ditambahkan `App\Exceptions\CheckoutException` untuk error domain yang pesannya aman ditampilkan (cart kosong, kupon tidak valid, stok kurang, nilai kupon berubah).
- `CheckoutService` sekarang melempar `CheckoutException` untuk semua error tersebut.
- `Frontend\CheckoutController`:
  - `CheckoutException` → pesan spesifik yang ramah pengguna.
  - Exception lain → detail lengkap ditulis ke log (user ID + exception), pengguna hanya melihat "Checkout gagal diproses. Silakan coba lagi atau hubungi CS."
  - Kegagalan pembuatan Snap token di halaman pembayaran juga dicatat ke log, tidak lagi ditelan diam-diam.
- `Api\CheckoutController` mengikuti pola yang sama; error tak terduga mengembalikan HTTP 500 dengan pesan generik, bukan detail internal.
- Regression test memastikan pesan mentah seperti `Midtrans unavailable` tidak muncul di response.

**Uji HTTP live:** checkout dengan kredensial placeholder hanya menampilkan "Pembayaran online belum dikonfigurasi. Silakan hubungi admin." Tidak ada `Unauthorized`, `API response`, `Midtrans Snap Error`, atau `SB-Mid-server` di HTML.

### QA-06 — Fixed: path SVG hero valid

- Dua arc pada `backend/resources/views/frontend/home/hero.blade.php` (baris 204 dan 273) diperbaiki dengan menambahkan nilai `dy` yang hilang: `a1 1 0 0 1 0 1.414...`.
- `php artisan view:clear` sudah dijalankan.
- Verifikasi headless Chromium: **118 `<path>`**, **0 rendering/SVG error**, **0 console error**, dan **0 path dengan panjang nol**.

### QA-05 — Sebagian dimitigasi, masih terbuka

- Ditambahkan `MidtransService::ensureConfigured()`. Jika server/client key kosong atau masih mengandung placeholder `YOUR_`, checkout berhenti lebih awal dengan pesan ramah pengguna dan menulis warning ke log.
- Ini mencegah request 401 ke Midtrans dan memastikan alur QA-04 tetap aman di lingkungan yang belum dikonfigurasi.
- QA-05 **belum bisa ditutup** karena membutuhkan kredensial sandbox valid untuk menguji Snap popup, settlement, pending, expire, cancel, dan webhook. Kunci produksi harus diberikan lewat secret manager, bukan di-commit.

**Hasil verifikasi:**

```text
Tests: 31 passed (148 assertions)
```

**Status setelah perbaikan:** QA-06 dan QA-07 fixed. **QA-05 tetap menjadi satu-satunya blocker rilis** dan membutuhkan kredensial Midtrans, bukan perubahan kode.
