@extends('frontend.layouts.app')

@section('title', 'Bantuan — ShopDress')
@section('meta_description', 'Panduan cara pemesanan, pengiriman, pengembalian, dan pertanyaan umum belanja di ShopDress.')

@section('content')
    @php
        $homeUrl = \App\Support\FrontendUrls::home();
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $cartUrl = \App\Support\FrontendUrls::cart();
        $contactUrl = 'mailto:halo@shopdress.com';
    @endphp

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

        <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ $homeUrl }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">Bantuan</span>
        </nav>

        <div class="relative mt-5 max-w-2xl">
            <p class="section-kicker">Pusat bantuan</p>
            <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--text)] sm:text-6xl">
                Ada yang bisa <span class="italic text-[var(--brand)]">kami bantu?</span>
            </h1>
            <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Temukan panduan belanja, informasi pengiriman, dan jawaban atas pertanyaan yang paling sering ditanyakan.
            </p>
        </div>
    </header>

    <div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach ([
            ['href' => '#cara-pemesanan', 'label' => 'Cara Pemesanan', 'desc' => 'Langkah belanja'],
            ['href' => '#pengiriman', 'label' => 'Pengiriman', 'desc' => 'Estimasi & ongkir'],
            ['href' => '#pengembalian', 'label' => 'Pengembalian', 'desc' => 'Tukar & retur'],
            ['href' => '#faq', 'label' => 'FAQ', 'desc' => 'Pertanyaan umum'],
        ] as $item)
            <a href="{{ $item['href'] }}"
               class="group flex items-center justify-between gap-3 rounded-2xl border border-[var(--border)] bg-[var(--surface)] px-4 py-3.5 transition hover:border-[var(--brand)]/30 hover:shadow-[0_16px_32px_-28px_rgba(50,19,95,0.45)]">
                <span>
                    <span class="block text-[11px] font-bold text-[var(--text)] transition group-hover:text-[var(--brand)]">{{ $item['label'] }}</span>
                    <span class="mt-0.5 block text-[9px] text-[var(--text-subtle)]">{{ $item['desc'] }}</span>
                </span>
                <svg class="h-3.5 w-3.5 shrink-0 text-[var(--text-subtle)] transition group-hover:translate-x-0.5 group-hover:text-[var(--brand)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </a>
        @endforeach
    </div>

    <div class="mt-6 space-y-5">
        {{-- Cara Pemesanan --}}
        <section id="cara-pemesanan" class="reveal scroll-mt-24 overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
            <div class="flex items-start gap-3 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l1 13H5L6 7Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a3 3 0 0 1 6 0v2" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Cara Pemesanan</h2>
                    <p class="mt-0.5 text-[11px] text-[var(--text-muted)]">Belanja di ShopDress hanya butuh beberapa langkah.</p>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <ol class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['step' => '01', 'title' => 'Pilih produk', 'desc' => 'Jelajahi katalog atau kategori, lalu buka detail produk untuk memilih warna dan ukuran.'],
                        ['step' => '02', 'title' => 'Masukkan keranjang', 'desc' => 'Tentukan jumlah, lalu tekan tombol Tambah ke Keranjang. Kamu bisa mengubah jumlah di halaman keranjang.'],
                        ['step' => '03', 'title' => 'Isi data pengiriman', 'desc' => 'Lanjut ke checkout, lengkapi nama penerima, nomor telepon, dan alamat pengiriman.'],
                        ['step' => '04', 'title' => 'Bayar pesanan', 'desc' => 'Pilih metode pembayaran melalui Midtrans, lalu selesaikan pembayaran sebelum batas waktu.'],
                    ] as $step)
                        <li class="rounded-xl bg-[var(--bg)] p-4">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[9px] font-black text-[var(--brand)]">{{ $step['step'] }}</span>
                            <h3 class="mt-2.5 text-[11px] font-bold text-[var(--text)]">{{ $step['title'] }}</h3>
                            <p class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">{{ $step['desc'] }}</p>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-5 flex flex-wrap gap-2.5">
                    <a href="{{ $catalogUrl }}" class="inline-flex h-10 items-center justify-center gap-1.5 rounded-full bg-[var(--brand)] px-4 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                        Mulai Belanja
                    </a>
                    <a href="{{ $cartUrl }}" class="inline-flex h-10 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                        Lihat Keranjang
                    </a>
                </div>
            </div>
        </section>

        {{-- Pengiriman --}}
        <section id="pengiriman" class="reveal scroll-mt-24 overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
            <div class="flex items-start gap-3 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v10H3V7Zm11 3h4l3 3v4h-7v-7Z" />
                        <circle cx="7" cy="18" r="1.5" />
                        <circle cx="17" cy="18" r="1.5" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Pengiriman</h2>
                    <p class="mt-0.5 text-[11px] text-[var(--text-muted)]">Pesanan dikemas rapi dan dikirim melalui kurir terpercaya.</p>
                </div>
            </div>

            <dl class="grid gap-3 p-5 sm:grid-cols-2 sm:p-6">
                @foreach ([
                    ['term' => 'Estimasi pengiriman', 'desc' => '1–2 hari kerja untuk area Jabodetabek dan 2–5 hari kerja untuk luar Jabodetabek.'],
                    ['term' => 'Ongkos kirim', 'desc' => 'Dihitung otomatis saat checkout berdasarkan alamat dan kurir yang dipilih.'],
                    ['term' => 'Nomor resi', 'desc' => 'Resi akan tersedia di halaman Pesanan setelah pesanan dikirim.'],
                    ['term' => 'Pengemasan', 'desc' => 'Produk dilindungi kemasan ekstra agar tetap aman selama perjalanan.'],
                ] as $item)
                    <div class="rounded-xl bg-[var(--bg)] p-4">
                        <dt class="text-[11px] font-bold text-[var(--text)]">{{ $item['term'] }}</dt>
                        <dd class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">{{ $item['desc'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- Pengembalian --}}
        <section id="pengembalian" class="reveal scroll-mt-24 overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
            <div class="flex items-start gap-3 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 9a8 8 0 1 1 2.34 5.66" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Pengembalian & Penukaran</h2>
                    <p class="mt-0.5 text-[11px] text-[var(--text-muted)]">Ukuran kurang pas? Kami bantu proses penukarannya.</p>
                </div>
            </div>

            <div class="space-y-4 p-5 text-[11px] leading-6 text-[var(--text-muted)] sm:p-6">
                <p>
                    Pengajuan penukaran atau pengembalian dapat dilakukan maksimal <strong class="font-bold text-[var(--text)]">7 hari</strong> setelah pesanan diterima.
                    Produk harus dalam kondisi belum dipakai, belum dicuci, dan label masih menempel.
                </p>
                <ul class="space-y-2">
                    @foreach ([
                        'Hubungi kami melalui email dengan menyertakan nomor pesanan dan foto produk.',
                        'Tim kami akan memeriksa pengajuan dalam 1–2 hari kerja.',
                        'Jika disetujui, produk dikirim kembali ke alamat yang kami informasikan.',
                        'Penukaran ukuran dapat dilakukan selama stok tersedia.',
                    ] as $point)
                        <li class="flex items-start gap-2.5">
                            <svg class="mt-1 h-3.5 w-3.5 shrink-0 text-[var(--brand)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- FAQ --}}
        <section id="faq" class="reveal scroll-mt-24 overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
            <div class="flex items-start gap-3 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9a2.5 2.5 0 1 1 4.1 1.9c-.9.7-1.6 1.2-1.6 2.6M12 17h.01" />
                    </svg>
                </span>
                <div>
                    <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Pertanyaan Umum</h2>
                    <p class="mt-0.5 text-[11px] text-[var(--text-muted)]">Jawaban singkat untuk pertanyaan yang sering muncul.</p>
                </div>
            </div>

            <div class="divide-y divide-[var(--border-light)]">
                @foreach ([
                    ['q' => 'Apakah bisa belanja tanpa membuat akun?', 'a' => 'Bisa. Kamu tetap dapat menambahkan produk ke keranjang dan checkout sebagai tamu. Namun, membuat akun memudahkanmu memantau pesanan dan menyimpan wishlist.'],
                    ['q' => 'Bagaimana cara memilih ukuran yang tepat?', 'a' => 'Buka detail produk dan pilih ukuran yang tersedia. Jika ragu, bandingkan ukuran badanmu dengan panduan pada deskripsi produk atau hubungi kami sebelum membeli.'],
                    ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Pembayaran diproses melalui Midtrans dan mendukung berbagai metode seperti transfer bank, virtual account, e-wallet, dan kartu.'],
                    ['q' => 'Apakah pesanan bisa dibatalkan?', 'a' => 'Pesanan yang belum dibayar dapat dibatalkan melalui halaman Pesanan. Jika sudah dibayar, silakan hubungi kami untuk memeriksa kemungkinan pembatalan.'],
                    ['q' => 'Bagaimana jika produk yang diterima rusak?', 'a' => 'Segera hubungi kami maksimal 7 hari setelah produk diterima dengan menyertakan foto kondisi produk dan nomor pesanan. Kami akan membantu proses penggantian.'],
                ] as $faq)
                    <details class="group px-5 py-4 sm:px-6">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-[11px] font-bold text-[var(--text)] marker:hidden">
                            {{ $faq['q'] }}
                            <svg class="h-3.5 w-3.5 shrink-0 text-[var(--text-subtle)] transition-transform duration-250 group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </summary>
                        <p class="mt-2.5 max-w-3xl text-[10px] leading-6 text-[var(--text-muted)]">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </section>
    </div>

    <section class="reveal mt-6 overflow-hidden rounded-[1.5rem] bg-[var(--brand-deep)] p-6 text-white sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold tracking-[-0.02em]">Masih butuh bantuan?</h2>
                <p class="mt-1 text-[11px] text-white/60">Tim kami siap membantu setiap Senin–Sabtu, 09.00–18.00 WIB.</p>
            </div>
            <a href="{{ $contactUrl }}" class="inline-flex h-11 shrink-0 items-center justify-center gap-2 self-start rounded-full bg-white px-5 text-[11px] font-bold text-[var(--brand-dark)] transition hover:bg-[var(--brand-soft)] sm:self-auto">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4V6Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                </svg>
                Hubungi Kami
            </a>
        </div>
    </section>
@endsection
