@extends('frontend.layouts.app')

@php
    $page = $page ?? null;
    $fallback = $fallback ?? [];
    $slug = $slug ?? 'terms';
    $storeName = \App\Models\SiteSetting::storeName();
    $kicker = $page?->kicker ?: ($fallback['kicker'] ?? 'Informasi toko');
    $heading = $page?->title ?: ($fallback['title'] ?? 'Syarat &');
    $highlight = $page?->highlight ?: ($fallback['highlight'] ?? 'Ketentuan');
    $subtitle = $page?->subtitle ?: ($fallback['subtitle'] ?? '');
    $metaDescription = $page?->meta_description ?: ($fallback['meta_description'] ?? '');
    $updatedAt = $page?->updated_at?->locale('id')->translatedFormat('j F Y');

    $fallbackSections = $slug === 'privacy'
        ? [
            [
                'title' => 'Informasi yang kami kumpulkan',
                'paragraphs' => [
                    'Kami mengumpulkan informasi yang Anda berikan secara langsung, seperti nama, alamat email, nomor telepon, alamat pengiriman, dan riwayat pesanan.',
                    'Kami juga mencatat data teknis sederhana seperti jenis perangkat dan halaman yang dikunjungi untuk membantu kami memahami cara toko digunakan.',
                ],
            ],
            [
                'title' => 'Cara kami menggunakan informasi',
                'paragraphs' => [
                    'Data dipakai untuk memproses pesanan, mengatur pengiriman, memberikan bantuan pelanggan, dan mengirimkan informasi penting terkait transaksi Anda.',
                    'Dengan persetujuan Anda, kami dapat mengirimkan kabar promo dan koleksi terbaru. Anda bisa berhenti berlangganan kapan saja.',
                ],
            ],
            [
                'title' => 'Berbagi informasi',
                'paragraphs' => [
                    'Kami hanya membagikan data kepada pihak yang membantu operasional toko, seperti jasa pengiriman dan penyedia pembayaran, sebatas yang diperlukan.',
                    'Kami tidak menjual data pribadi pelanggan kepada pihak lain.',
                ],
            ],
            [
                'title' => 'Keamanan data',
                'paragraphs' => [
                    'Kami memakai koneksi terenkripsi dan pembatasan akses internal untuk menjaga data pelanggan. Meski demikian, tidak ada sistem yang sepenuhnya bebas risiko.',
                ],
            ],
            [
                'title' => 'Hak Anda',
                'paragraphs' => [
                    'Anda berhak meminta salinan, perbaikan, atau penghapusan data pribadi yang kami simpan. Hubungi customer care kami untuk mengajukan permintaan tersebut.',
                ],
            ],
            [
                'title' => 'Perubahan kebijakan',
                'paragraphs' => [
                    'Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu tersedia di halaman ini beserta tanggal pembaruannya.',
                ],
            ],
        ]
        : [
            [
                'title' => 'Ketentuan umum',
                'paragraphs' => [
                    "Dengan mengakses dan berbelanja di {$storeName}, Anda menyetujui syarat dan ketentuan yang berlaku di halaman ini.",
                    'Kami dapat memperbarui ketentuan ini dari waktu ke waktu. Perubahan berlaku setelah dipublikasikan di halaman ini.',
                ],
            ],
            [
                'title' => 'Pesanan dan pembayaran',
                'paragraphs' => [
                    'Pesanan dianggap sah setelah pembayaran berhasil dikonfirmasi. Harga yang tercantum sudah termasuk pajak yang berlaku, tetapi belum termasuk ongkos kirim kecuali dinyatakan lain.',
                    'Kami berhak membatalkan pesanan apabila terjadi kesalahan harga, stok tidak tersedia, atau indikasi penyalahgunaan.',
                ],
            ],
            [
                'title' => 'Pengiriman',
                'paragraphs' => [
                    'Pesanan diproses pada hari kerja dan dikirim ke alamat yang Anda isi saat checkout. Pastikan data penerima dan alamat sudah benar.',
                    'Estimasi pengiriman dapat berubah karena faktor cuaca, hari libur, atau kebijakan jasa pengiriman.',
                ],
            ],
            [
                'title' => 'Pengembalian dan penukaran',
                'paragraphs' => [
                    "Produk dapat ditukar atau dikembalikan sesuai ketentuan retur yang berlaku, yaitu {$siteSettings['return_policy']} setelah pesanan diterima.",
                    'Produk harus dalam kondisi belum dipakai, belum dicuci, dan label masih lengkap. Hubungi customer care terlebih dahulu sebelum mengirimkan produk kembali.',
                ],
            ],
            [
                'title' => 'Hak kekayaan intelektual',
                'paragraphs' => [
                    'Seluruh konten di situs ini, termasuk foto, teks, dan logo, merupakan milik toko dan tidak boleh dipakai tanpa izin tertulis.',
                ],
            ],
            [
                'title' => 'Hubungi kami',
                'paragraphs' => [
                    'Jika ada pertanyaan tentang ketentuan ini, silakan hubungi customer care kami melalui email atau WhatsApp yang tertera di halaman ini.',
                ],
            ],
        ];
@endphp

@section('title', trim($heading.' '.$highlight).' — '.$storeName)
@section('meta_description', $metaDescription)

@section('content')
    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">{{ $heading }} {{ $highlight }}</span>
    </nav>

    <section class="reveal relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-9 sm:px-9 sm:py-11">
        <div class="pointer-events-none absolute -left-20 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-10 h-28 w-28 rounded-full bg-[var(--accent-soft)]/90 blur-2xl"></div>

        <div class="relative max-w-2xl">
            <p class="section-kicker">{{ $kicker }}</p>
            <h1 class="mt-3 font-display text-[2.5rem] font-semibold leading-[0.98] tracking-[-0.045em] text-[var(--text)] sm:text-5xl">
                {{ $heading }}
                @if (filled($highlight))
                    <span class="block italic text-[var(--brand)]">{{ $highlight }}</span>
                @endif
            </h1>
            @if (filled($subtitle))
                <p class="mt-5 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">{{ $subtitle }}</p>
            @endif
            <p class="mt-5 inline-flex items-center gap-2 rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[9px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                </svg>
                {{ $updatedAt ? 'Diperbarui '.$updatedAt : 'Berlaku saat ini' }}
            </p>
        </div>
    </section>

    <section class="reveal mt-5 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-7" style="--reveal-delay: 70ms">
        @if ($page?->content)
            <div class="sd-rich-text">{!! $page->content !!}</div>
        @else
            <div class="space-y-7">
                @foreach ($fallbackSections as $section)
                    <article>
                        <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)] sm:text-2xl">{{ $section['title'] }}</h2>
                        <div class="mt-2.5 space-y-2.5">
                            @foreach ($section['paragraphs'] as $paragraph)
                                <p class="text-[11px] leading-6 text-[var(--text-muted)] sm:text-xs">{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="reveal mt-5 flex flex-col gap-4 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface-2)] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6" style="--reveal-delay: 120ms">
        <div>
            <p class="font-display text-lg font-semibold tracking-[-0.02em] text-[var(--text)]">Masih ada pertanyaan?</p>
            <p class="mt-1 text-[11px] leading-6 text-[var(--text-muted)]">Tim customer care {{ $storeName }} siap membantu lewat email atau WhatsApp.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            @if (filled($siteSettings['email_url'] ?? null))
                <a href="{{ $siteSettings['email_url'] }}" class="inline-flex h-10 items-center rounded-full bg-[var(--brand)] px-4 text-[11px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                    Email kami
                </a>
            @endif
            @if (filled($siteSettings['whatsapp_help_url'] ?? null))
                <a href="{{ $siteSettings['whatsapp_help_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex h-10 items-center rounded-full border border-[var(--border)] px-4 text-[11px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                    WhatsApp
                </a>
            @endif
        </div>
    </section>

    <p class="mt-6 text-center text-[10px] text-[var(--text-subtle)]">
        Baca juga
        @if ($slug === 'privacy')
            <a href="{{ \App\Support\FrontendUrls::terms() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Syarat & Ketentuan</a>.
        @else
            <a href="{{ \App\Support\FrontendUrls::privacy() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Kebijakan Privasi</a>.
        @endif
    </p>
@endsection
