<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StaticPageSeeder extends Seeder
{
    /**
     * Isi awal halaman Tentang Kami dan Bantuan dengan konten yang sudah ada.
     */
    public function run(): void
    {
        $aboutImage = null;
        $aboutSource = public_path('images/editorial/about.jpg');

        if (is_file($aboutSource)) {
            $aboutImage = 'pages/about.jpg';

            if (! Storage::disk('public')->exists($aboutImage)) {
                Storage::disk('public')->put($aboutImage, file_get_contents($aboutSource));
            }
        }

        StaticPage::firstOrCreate(
            ['slug' => 'about'],
            [
                'kicker' => 'Cerita ShopDress',
                'title' => 'Busana yang tumbuh',
                'highlight' => 'bersama ceritamu.',
                'subtitle' => 'ShopDress lahir dari satu keyakinan sederhana: perempuan Indonesia berhak tampil anggun tanpa mengorbankan kenyamanan. Kami memilih bahan yang jatuh lembut, memotong pola untuk tubuh yang nyata, dan menyelesaikan setiap detail dengan sabar.',
                'meta_description' => 'Kenali ShopDress: brand busana wanita modern Indonesia dengan bahan premium, potongan nyaman, dan detail yang dikerjakan teliti.',
                'image' => $aboutImage,
                'image_alt' => 'Deretan busana ShopDress dengan bahan lembut dan warna netral',
                'is_active' => true,
            ],
        );

        StaticPage::firstOrCreate(
            ['slug' => 'help'],
            [
                'kicker' => 'Pusat bantuan',
                'title' => 'Ada yang bisa',
                'highlight' => 'kami bantu?',
                'subtitle' => 'Temukan panduan belanja, informasi pengiriman, dan jawaban atas pertanyaan yang paling sering ditanyakan.',
                'meta_description' => 'Panduan cara pemesanan, pengiriman, pengembalian, dan pertanyaan umum belanja di ShopDress.',
                'is_active' => true,
            ],
        );

        StaticPage::firstOrCreate(
            ['slug' => 'terms'],
            [
                'kicker' => 'Ketentuan belanja',
                'title' => 'Syarat &',
                'highlight' => 'Ketentuan',
                'subtitle' => 'Panduan singkat agar pengalaman berbelanja di toko kami tetap aman, nyaman, dan saling menguntungkan.',
                'content' => <<<'HTML'
<h2>Ketentuan umum</h2>
<p>Dengan mengakses dan berbelanja di toko kami, Anda menyetujui syarat dan ketentuan yang berlaku di halaman ini. Kami dapat memperbarui ketentuan ini dari waktu ke waktu, dan perubahan berlaku setelah dipublikasikan.</p>
<h2>Pesanan dan pembayaran</h2>
<p>Pesanan dianggap sah setelah pembayaran berhasil dikonfirmasi. Harga yang tercantum sudah termasuk pajak yang berlaku, tetapi belum termasuk ongkos kirim kecuali dinyatakan lain. Kami berhak membatalkan pesanan apabila terjadi kesalahan harga, stok tidak tersedia, atau indikasi penyalahgunaan.</p>
<h2>Pengiriman</h2>
<p>Pesanan diproses pada hari kerja dan dikirim ke alamat yang Anda isi saat checkout. Pastikan data penerima dan alamat sudah benar. Estimasi pengiriman dapat berubah karena faktor cuaca, hari libur, atau kebijakan jasa pengiriman.</p>
<h2>Pengembalian dan penukaran</h2>
<p>Produk dapat ditukar atau dikembalikan sesuai ketentuan retur yang berlaku. Produk harus dalam kondisi belum dipakai, belum dicuci, dan label masih lengkap. Hubungi customer care terlebih dahulu sebelum mengirimkan produk kembali.</p>
<h2>Hak kekayaan intelektual</h2>
<p>Seluruh konten di situs ini, termasuk foto, teks, dan logo, merupakan milik toko dan tidak boleh dipakai tanpa izin tertulis.</p>
<h2>Hubungi kami</h2>
<p>Jika ada pertanyaan tentang ketentuan ini, silakan hubungi customer care kami melalui email atau WhatsApp yang tertera di footer.</p>
HTML,
                'meta_description' => 'Syarat dan ketentuan belanja, pembayaran, pengiriman, serta pengembalian produk.',
                'is_active' => true,
            ],
        );

        StaticPage::firstOrCreate(
            ['slug' => 'privacy'],
            [
                'kicker' => 'Perlindungan data',
                'title' => 'Kebijakan',
                'highlight' => 'Privasi',
                'subtitle' => 'Kami memahami bahwa data pribadi Anda adalah titipan. Berikut cara kami mengumpulkan, memakai, dan menjaga informasi tersebut.',
                'content' => <<<'HTML'
<h2>Informasi yang kami kumpulkan</h2>
<p>Kami mengumpulkan informasi yang Anda berikan secara langsung, seperti nama, alamat email, nomor telepon, alamat pengiriman, dan riwayat pesanan. Kami juga mencatat data teknis sederhana seperti jenis perangkat dan halaman yang dikunjungi untuk membantu kami memahami cara toko digunakan.</p>
<h2>Cara kami menggunakan informasi</h2>
<p>Data dipakai untuk memproses pesanan, mengatur pengiriman, memberikan bantuan pelanggan, dan mengirimkan informasi penting terkait transaksi Anda. Dengan persetujuan Anda, kami dapat mengirimkan kabar promo dan koleksi terbaru. Anda bisa berhenti berlangganan kapan saja.</p>
<h2>Berbagi informasi</h2>
<p>Kami hanya membagikan data kepada pihak yang membantu operasional toko, seperti jasa pengiriman dan penyedia pembayaran, sebatas yang diperlukan. Kami tidak menjual data pribadi pelanggan kepada pihak lain.</p>
<h2>Keamanan data</h2>
<p>Kami memakai koneksi terenkripsi dan pembatasan akses internal untuk menjaga data pelanggan. Meski demikian, tidak ada sistem yang sepenuhnya bebas risiko.</p>
<h2>Hak Anda</h2>
<p>Anda berhak meminta salinan, perbaikan, atau penghapusan data pribadi yang kami simpan. Hubungi customer care kami untuk mengajukan permintaan tersebut.</p>
<h2>Perubahan kebijakan</h2>
<p>Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu tersedia di halaman ini beserta tanggal pembaruannya.</p>
HTML,
                'meta_description' => 'Kebijakan privasi tentang cara kami mengumpulkan, memakai, dan melindungi data pelanggan.',
                'is_active' => true,
            ],
        );
    }
}
