# SHOPDRESS — UI/UX Design Specification & Project Progress Brief

> **Dokumen Panduan Desain untuk Google Stitch / AI UI Designer**  
> *Project:* **ShopDress** (E-Commerce Busana & Dress Wanita Modern)  
> *Brand Identity:* Modern Fashion, Dynamic Orange `#FF5D00`, Clean Typography (Space Grotesk & Inter), High Usability.

---

## 1. Ringkasan Eksekutif & Karakteristik Brand

**ShopDress** adalah platform e-commerce fashion wanita modern di Indonesia yang berfokus pada kurasi dress berkualitas tinggi:
- **Kategori Produk:**
  1. *Gamis & Abaya Modern*
  2. *Casual & Daily Dress*
  3. *Korean Style Dress*
  4. *Maxi & Long Dress*
  5. *Party & Formal Dress*
- **Target Pengguna:** Wanita modern usia 18–38 tahun yang mencari busana elegan, stylish, dan berkualitas untuk aktivitas harian, kerja, maupun acara formal.
- **Tone & Mood Visual:**
  - Bersih, elegan, modern, dan percaya diri (*high-end yet accessible*, referensi: Zara, Love, Bonito, CottonInk).
  - Aksen warna energik dan dinamis (*Vibrant Electric Orange* `#FF5D00`) berpadu dengan latar belakang putih bersih (`#FFFFFF`) dan kontras gelap elegan (`#0F172A`).
  - Navigasi intuitif, alur belanja cepat (*frictionless checkout*), dan responsif penuh (Mobile-first).

---

## 2. Progress Pengembangan Saat Ini (Backend & Fondasi Data)

Sistem backend telah siap dan berjalan dengan arsitektur lengkap:

### A. Fitur Inti yang Sudah Selesai:
1. **Autentikasi & Akun:** Multi-role (Admin & Customer), Laravel Sanctum Token API, Manajemen Profil & Alamat Pengiriman.
2. **Katalog & Manajemen Varian:**
   - Produk memiliki multiple varian (Kombinasi Warna & Ukuran: S, M, L, XL).
   - Pengelolaan galeri foto per produk.
   - Manajemen inventaris stok real-time dengan *Pessimistic Locking* (mencegah *race condition* dan overselling).
3. **Keranjang Belanja Pintar (Cart System):**
   - Mendukung *Guest Cart* (keranjang sesi sebelum login).
   - Otomatis menggabungkan (*merge*) keranjang tamu saat pengguna login.
4. **Checkout & Pembayaran Otomatis:**
   - Terintegrasi penuh dengan **Midtrans Payment Gateway** (Snap Popup/Redirect).
   - Verifikasi notifikasi webhook SHA-512 dengan transaksi database idempoten.
5. **Logistik & Pengiriman:**
   - Integrasi kurir pengiriman (JNE REG/YES/OKE).
   - Pelacakan nomor resi otomatis dengan notifikasi status pesanan via Email.
6. **Machine Learning / AI Forecasting di Admin Panel:**
   - Model analitik stok cerdas: *Weighted Moving Average (WMA)* demand velocity, prediksi habis stok (*Days to Stockout*), dan *Economic Order Quantity (EOQ)* saran restock.
   - Visualisasi: Grafik Donut Status Risiko Stok, Grafik Batang Stok Fisik vs Saran Restock per Kategori, 4 Kartu KPI Metrik, dan tabel analitik varian dengan aksi pembuatan PO WhatsApp Konveksi otomatis.

---

## 3. Sistem Desain & Design Tokens (UI Guidelines)

### A. Palet Warna (Color Palette)
- **Primary / Brand Accent:** `#FF5D00` (Electric Orange — energi, CTA utama, highlight diskon, status aktif).
- **Primary Hover:** `#E05200`
- **Secondary / Accent Teal:** `#0EA5E9` (Sky Blue) & `#10B981` (Emerald Green untuk status sukses/aman).
- **Neutral Dark (Text & Headers):** `#0F172A` (Slate 900 — tajam, mudah dibaca, kontras tinggi).
- **Neutral Body Text:** `#334155` (Slate 700) / `#64748B` (Slate 500 untuk caption & metadata).
- **Neutral Background (Canvas):** `#FFFFFF` (Surface) & `#F8FAFC` (Soft Background).
- **Borders & Dividers:** `#E2E8F0` (Slate 200) / `#F1F5F9` (Slate 100).
- **Status & Risk Alerts:**
  - *Kritis / Out of Stock:* `#EF4444` (Red 500), Background `#FEF2F2` (Red 50)
  - *Waspada / Low Stock:* `#F59E0B` (Amber 500), Background `#FFFBEB` (Amber 50)
  - *Aman:* `#10B981` (Emerald 500), Background `#ECFDF5` (Emerald 50)
  - *Stok Mengendap (Slow Moving):* `#64748B` (Slate 500), Background `#F1F5F9` (Slate 100)

### B. Tipografi (Typography)
- **Heading Font:** `Space Grotesk` (Google Fonts) — memberikan kesan modern, edgy, dan high-fashion.
- **Body & UI Font:** `Inter` atau `Plus Jakarta Sans` — memastikan tingkat keterbacaan (readability) maksimal pada mobile screen.
- **Skala Ukuran:**
  - `Display / Hero Title`: 40px – 48px (Bold 700)
  - `Section Title`: 28px – 32px (Bold 700)
  - `Card Title / Subheading`: 16px – 18px (SemiBold 600)
  - `Body Regular`: 14px (Regular 400 / Medium 500)
  - `Caption & Microcopy`: 11px – 12px (Medium 500)

### C. Komponen UI Standar
- **Corner Radius:**
  - Tombol & Chip Varian: `rounded-xl` (10px–12px)
  - Kartu Produk & Container: `rounded-2xl` (16px)
  - Modal & Bottom Sheet: `rounded-3xl` (24px)
- **Elevasi & Bayangan (Shadows):**
  - Card Default: `shadow-sm` halus (`box-shadow: 0 1px 3px rgba(0,0,0,0.05)`)
  - Hover Card: `shadow-md` dengan transisi halus (`translate-y: -2px`)
- **Tanpa Emoji:** Semua indikator visual menggunakan ikon berbasis SVG / Heroicons (bersih, profesional, elegan).

---

## 4. Rincian Kebutuhan Halaman untuk Google Stitch

Harap buat desain UI/UX interaktif untuk 6 halaman utama berikut:

### 1. Homepage (Beranda E-Commerce)
- **Top Bar:** Promo ticker ("Gratis Ongkir Seluruh Indonesia s/d 50rb" / "Gunakan voucher NEWDRESS diskon 15%").
- **Header & Navbar:** Logo ShopDress, link navigasi kategori, search bar dengan auto-complete, ikon Wishlist, dan ikon Keranjang dengan badge counter angka.
- **Hero Banner:** Full-width visual bergaya majalah fashion, headline tebal, subheadline, dan tombol CTA utama `"Belanja Koleksi Terbaru"` (Oranye `#FF5D00`).
- **Category Explorer:** Carousel/Grid visual kategori (Gamis, Casual, Korean Style, Maxi, Formal) dengan foto model beresolusi tinggi.
- **Curated Collections / Trending Dresses:** Grid kartu produk dengan fitur:
  - Label status (*New Arrival*, *Best Seller*, *Diskon 20%*).
  - Quick Wishlist toggle (ikon hati di sudut foto).
  - Thumbnail sekunder saat foto di-hover.
  - Nama produk, harga normal, harga coret diskon, dan rating bintang.
- **Banner Fitur Layanan:** 3–4 selling points (Garansi Pengembalian 7 Hari, 100% Produk Original, Pengiriman Cepat JNE, Pembayaran Aman).
- **Footer:** Link bantuan, metode pembayaran (Midtrans, Bank Transfer, QRIS, GoPay), kurir pengiriman (JNE), dan kolom pendaftaran newsletter diskon.

---

### 2. Halaman Katalog & Filter Produk (`/catalog`)
- **Sticky Filter Sidebar (Desktop) / Filter Bottom Sheet (Mobile):**
  - Filter Kategori (*Checkbox list*).
  - Filter Ukuran (*Chip selector:* S, M, L, XL).
  - Filter Warna (*Color circle swatch:* Sage Green, Mocca, Dusty Pink, Black, Navy).
  - Rentang Harga (*Dual-point price slider*).
  - Urutkan (*Dropdown:* Terpopuler, Terbaru, Harga Terendah, Harga Tertinggi).
- **Area Grid Produk:** Layout 3 kolom (desktop) atau 2 kolom (mobile) yang lega.
- **Active Filter Chips:** Tag filter yang sedang aktif dengan tombol silang (*clear*) satu klik.
- **Pagination / Infinite Scroll:** Tombol *"Muat Lebih Banyak Produk"* yang mulus.

---

### 3. Halaman Detail Produk / PDP (`/products/{slug}`)
- **Galeri Foto Interaktif:** Foto utama besar di sisi kiri dengan thumbnail bar vertikal/horizontal, serta fitur zoom preview.
- **Info Produk Utama:**
  - Nama Produk & Kategori Breadcrumb.
  - Rating bintang dan jumlah ulasan (*e.g., 4.9 (128 ulasan)*).
  - Harga jelas dengan kalkulasi diskon.
- **Selector Varian yang Intuitif:**
  - Pilihan Warna (*Color swatches* dengan teks nama warna).
  - Pilihan Ukuran (*Size selector* dengan informasi real-time: *"Sisa 3 pcs!"* atau abu-abu jika habis).
  - Tombol *"Panduan Ukuran (Size Chart)"* membuka modal panduan ukuran tubuh (Lingkar Dada, Panjang Dress).
- **Quantity Selector & Action Buttons:**
  - Counter jumlah pesanan `[-] 1 [+]`.
  - Tombol Primer: `Tambah ke Keranjang` (Outlined / Black).
  - Tombol Sekunder: `Beli Sekarang` (Solid Electric Orange `#FF5D00`).
- **Tab Deskripsi, Material Bahan & Pengiriman:** Detail bahan (e.g. *Silk Premium, Adem & Lembut*), panduan perawatan, estimasi biaya kirim via JNE.
- **Ulasan Pembeli:** Statistik rating, filter ulasan berbintang, dan review asli dengan foto testimoni pengguna.

---

### 4. Slide-Over Drawer Cart & Halaman Checkout
- **Slide-Over Mini Cart (Muncul dari kanan):**
  - Menampilkan ringkasan produk yang baru dimasukkan tanpa perlu refresh halaman.
  - Pengubahan jumlah item atau hapus item secara instan.
  - Kolom input kode kupon diskon (*Voucher Code*).
  - Subtotal ringkas dan tombol CTA `"Lanjut ke Pembayaran"`.
- **Halaman Checkout Bersih (One-Page Checkout):**
  - **Langkah 1: Alamat Pengiriman:** Form nama penerima, no handphone, alamat lengkap, kota/kecamatan.
  - **Langkah 2: Pilihan Kurir:** Radio box pilihan layanan JNE (JNE REG - 2-3 hari, JNE YES - 1 hari sampai) beserta ongkos kirim real-time.
  - **Langkah 3: Metode Pembayaran:** Pilihan pembayaran instan via Midtrans (BCA/Mandiri Virtual Account, QRIS, GoPay, ShopeePay, Kartu Kredit).
  - **Ringkasan Pesanan (Sticky Right Column):** Rincian harga produk, ongkos kirim, diskon voucher, total bayar, dan tombol proteksi transaksi aman.

---

### 5. Dashboard Pengguna (`/dashboard/orders`)
- **Tab Navigasi Akun:** Pesanan Saya, Wishlist Favorit, Alamat Tersimpan, Pengaturan Akun.
- **Kartu Riwayat Pesanan:**
  - Nomor Order unik (misal: `#ORD-20261011-8721`).
  - Badge Status Berwarna (*Menunggu Pembayaran*, *Diproses*, *Dikirim*, *Selesai*).
  - **Live Tracking Widget Resi JNE:** Timeline visual pergerakan kurir (Manifest, On Process, Out for Delivery, Delivered).
  - Tombol Aksi: *"Beri Ulasan"* atau *"Beli Lagi"*.

---

### 6. AI Demand Forecasting & Restock Alert (Admin Panel Reference)
*Struktur halaman analitik stok yang sudah diterapkan di backend:*
- **Baris Atas (Dua Widget Visual):**
  - *Grafik Donut:* Distribusi Risiko Ketahanan Stok (Kritis, Waspada, Slow Moving, Aman).
  - *Grafik Batang:* Perbandingan Stok Fisik Tersedia vs Saran Order Restock per Kategori.
- **Baris Tengah (4 Kartu KPI Metrik):**
  - `Restock Kritis`: Ikon segitiga merah + angka `0 Varian`.
  - `Peringatan Waspada`: Ikon jam amber + angka `0 Varian`.
  - `Stok Mengendap`: Ikon kubus boks slate + angka `9 Varian`.
  - `Saran Total Restock`: Ikon paket/gembok slate + angka `0 Pcs`.
- **Baris Bawah (Tabel Analisis Varian):**
  - Kolom: `Produk`, `Varian & SKU`, `Stok Fisik` (dengan stok pengaman & ROP), `Laju Harian (WMA)` (dengan penjualan 7/30 hari), `Ketahanan`, `Risiko`, `Saran Restock`, `Tindakan`.
  - Panel *Cara perhitungan* berisi 4 langkah (WMA, stok pengaman, ROP, saran restock) dan rincian hitung per baris untuk menjelaskan asal angka.
  - Dilengkapi tombol cepat integrasi order konveksi via WhatsApp.

---

## 5. Instruksi Khusus untuk Google Stitch

1. **Prioritaskan Aksesibilitas & Micro-Interactions:**
   - Berikan efek hover halus (*smooth hover lift*) pada kartu produk.
   - Sediakan state visual yang jelas untuk tombol (*Default, Hover, Active, Disabled, Loading Spinner*).
   - Pastikan kontras warna teks terhadap background memenuhi standar WCAG AA.
2. **Desain Mobile-First (Responsive):**
   - Di tampilan ponsel (viewport < 640px), sediakan *Bottom Navigation Bar* melayang (*Home, Kategori, Wishlist, Keranjang, Akun*).
   - Filter katalog menggunakan *Bottom Sheet Modal* agar mudah dijangkau satu tangan.
3. **Kerapian & Konsistensi UI:**
   - Gunakan grid sistem 8pt/4pt (*spacing standard*).
   - Hindari penggunaan dekorasi berlebihan atau emoji; gunakan ikon vektor minimalis agar kesan toko online terlihat mewah, modern, dan tepercaya.
