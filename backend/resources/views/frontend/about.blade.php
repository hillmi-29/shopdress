@extends('frontend.layouts.app')

@section('title', 'Tentang Kami — ShopDress')
@section('meta_description', 'Kenali ShopDress: brand busana wanita modern Indonesia dengan bahan premium, potongan nyaman, dan detail yang dikerjakan teliti.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $promoUrl = \App\Support\FrontendUrls::promo();
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">Tentang kami</span>
    </nav>

    {{-- Hero --}}
    <section class="reveal grid items-stretch gap-5 lg:grid-cols-[1.08fr_0.92fr]">
        <div class="relative flex flex-col justify-center overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-9 sm:px-9 sm:py-11">
            <div class="pointer-events-none absolute -left-20 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 right-10 h-28 w-28 rounded-full bg-[var(--accent-soft)]/90 blur-2xl"></div>

            <div class="relative max-w-xl">
                <p class="section-kicker">Cerita ShopDress</p>
                <h1 class="mt-3 font-display text-[2.7rem] font-semibold leading-[0.98] tracking-[-0.045em] text-[var(--text)] sm:text-6xl">
                    Busana yang tumbuh
                    <span class="block italic text-[var(--brand)]">bersama ceritamu.</span>
                </h1>
                <p class="mt-5 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    ShopDress lahir dari satu keyakinan sederhana: perempuan Indonesia berhak tampil anggun tanpa mengorbankan kenyamanan. Kami memilih bahan yang jatuh lembut, memotong pola untuk tubuh yang nyata, dan menyelesaikan setiap detail dengan sabar.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-2.5">
                    <a href="{{ $catalogUrl }}"
                       class="group inline-flex h-11 items-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-18px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)]">
                        Jelajahi koleksi
                        <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="{{ $promoUrl }}"
                       class="inline-flex h-11 items-center rounded-full border border-[var(--border)] px-5 text-[11px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                        Lihat promo
                    </a>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface-2)]">
            <img src="{{ asset('images/editorial/about.jpg') }}"
                 alt="Deretan busana ShopDress dengan bahan lembut dan warna netral"
                 class="h-full min-h-[19rem] w-full object-cover sm:min-h-[24rem]">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#241d2e]/55 via-transparent to-transparent"></div>

            <div class="absolute inset-x-4 bottom-4 sm:inset-x-5 sm:bottom-5">
                <div class="flex items-center justify-between gap-4 rounded-[1.25rem] border border-white/15 bg-[#241d2e]/55 px-4 py-3.5 backdrop-blur-md">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/55">Sejak 2019</p>
                        <p class="mt-1 font-display text-lg font-semibold leading-tight text-white">Dibuat di Indonesia, untuk perempuan Indonesia.</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[var(--accent)] text-[var(--brand-deep)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="reveal mt-5 grid grid-cols-2 gap-px overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--border)] sm:grid-cols-4" style="--reveal-delay: 70ms">
        @foreach ([
            ['value' => '10.000+', 'label' => 'Pelanggan bahagia'],
            ['value' => '4,9/5', 'label' => 'Rating pelanggan'],
            ['value' => '100%', 'label' => 'Bahan dikurasi'],
            ['value' => '7 hari', 'label' => 'Garansi tukar ukuran'],
        ] as $stat)
            <div class="bg-[var(--surface)] px-4 py-5 text-center sm:px-5 sm:py-6">
                <p class="font-display text-2xl font-semibold tracking-[-0.03em] text-[var(--brand)] sm:text-3xl">{{ $stat['value'] }}</p>
                <p class="mt-1 text-[9px] font-semibold uppercase tracking-[0.12em] text-[var(--text-subtle)]">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- Values --}}
    <section class="mt-14 sm:mt-20">
        <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Yang kami pegang</p>
                <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                    Tiga hal yang tidak kami kompromikan
                </h2>
            </div>
            <p class="max-w-sm text-xs leading-6 text-[var(--text-muted)] sm:text-right sm:text-sm">
                Bukan sekadar tren sesaat — setiap koleksi dirancang agar tetap relevan dan nyaman dipakai bertahun-tahun.
            </p>
        </div>

        <div class="mt-7 grid gap-4 md:grid-cols-3">
            @foreach ([
                ['title' => 'Bahan pilihan', 'desc' => 'Satin silk, linen, dan rayon premium yang jatuh lembut, menyerap keringat, dan tidak menerawang.', 'icon' => 'm12 3 9 5.25-9 5.25-9-5.25L12 3Zm-9 8.625 9 5.25 9-5.25M3 15.75 12 21l9-5.25'],
                ['title' => 'Detail yang rapi', 'desc' => 'Jahitan kuat, lining lembut, dan finishing yang diperiksa satu per satu sebelum dikirim.', 'icon' => 'M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.388-1.62m-5.043-.025a15.994 15.994 0 0 1 1.622-3.395m3.42 3.42a15.995 15.995 0 0 0 4.764-4.648l3.876-5.814a1.151 1.151 0 0 0-1.597-1.597L14.146 6.32a15.996 15.996 0 0 0-4.649 4.763m3.42 3.42a6.776 6.776 0 0 0-3.42-3.42'],
                ['title' => 'Nyaman sepanjang hari', 'desc' => 'Potongan yang memberi ruang bergerak, dari rapat pagi sampai acara malam tanpa perlu ganti.', 'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z'],
            ] as $value)
                <article class="reveal group rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 transition-colors duration-250 hover:border-[var(--brand)]/30 sm:p-6" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)] transition-colors duration-250 group-hover:bg-[var(--brand)] group-hover:text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $value['icon'] }}" />
                        </svg>
                    </span>
                    <p class="mt-5 text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                    <h3 class="mt-1.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">{{ $value['title'] }}</h3>
                    <p class="mt-2 text-[11px] leading-6 text-[var(--text-muted)]">{{ $value['desc'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Process --}}
    <section class="mt-14 overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] sm:mt-20">
        <div class="grid lg:grid-cols-[0.88fr_1.12fr]">
            <div class="relative min-h-[17rem] overflow-hidden bg-[var(--surface-2)]">
                <img src="{{ asset('images/editorial/hero-2.jpg') }}"
                     alt="Model mengenakan dress ShopDress berwarna lembut"
                     loading="lazy"
                     class="h-full w-full object-cover object-top">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#32135f]/45 via-transparent to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5">
                    <p class="text-[9px] font-black uppercase tracking-[0.16em] text-white/60">ShopDress atelier</p>
                    <p class="mt-1 font-display text-xl font-semibold leading-tight text-white">Detail kecil yang membuat perbedaan besar.</p>
                </div>
            </div>

            <div class="px-6 py-8 sm:px-9 sm:py-10">
                <p class="section-kicker">Dari bahan ke lemari</p>
                <h2 class="mt-2 max-w-md font-display text-3xl font-semibold leading-[1.05] tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                    Proses yang kami tempuh sebelum dress sampai di tanganmu
                </h2>

                <ol class="mt-7 space-y-5">
                    @foreach ([
                        ['title' => 'Kurasi bahan', 'desc' => 'Kami menguji tekstur, ketebalan, dan jatuhnya kain sebelum masuk koleksi.'],
                        ['title' => 'Pola & sampel', 'desc' => 'Setiap potongan disesuaikan dengan proporsi tubuh wanita Indonesia melalui beberapa kali sampel.'],
                        ['title' => 'Jahit & periksa', 'desc' => 'Penjahit berpengalaman menyelesaikan tiap detail, lalu tim QC memeriksa jahitan dan lining.'],
                        ['title' => 'Dikemas & dikirim', 'desc' => 'Busana dilipat rapi, dilindungi, dan dikirim dengan opsi pengiriman yang bisa dilacak.'],
                    ] as $process)
                        <li class="flex gap-3.5">
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--bg)] text-[9px] font-black text-[var(--brand)]">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <p class="text-[12px] font-bold text-[var(--text)]">{{ $process['title'] }}</p>
                                <p class="mt-0.5 text-[10px] leading-5 text-[var(--text-muted)]">{{ $process['desc'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- Quote / CTA --}}
    <section class="reveal relative mt-14 overflow-hidden rounded-[1.75rem] bg-[var(--brand-deep)] px-6 py-10 text-white sm:mt-20 sm:px-10 sm:py-12">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[var(--brand)]/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 h-56 w-56 rounded-full bg-[var(--accent)]/15 blur-3xl"></div>

        <div class="relative grid items-center gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <figure>
                <span class="font-display text-5xl leading-none text-[var(--accent)]" aria-hidden="true">&ldquo;</span>
                <blockquote class="mt-2 max-w-2xl font-display text-2xl font-medium leading-[1.2] tracking-[-0.025em] sm:text-3xl">
                    Kami tidak ingin perempuan harus memilih antara terlihat cantik dan merasa nyaman. Keduanya bisa dimiliki.
                </blockquote>
                <figcaption class="mt-4 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/55">
                    Tim ShopDress
                </figcaption>
            </figure>

            <div class="rounded-[1.35rem] border border-white/15 bg-white/[0.06] p-5 backdrop-blur-sm">
                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-white/50">Siap menemukan favoritmu?</p>
                <p class="mt-2 text-xs leading-6 text-white/70">
                    Mulai dari koleksi paling dicari, atau gunakan kode <span class="font-mono font-bold text-white">SHOPDRESSNEW</span> untuk potongan 20% di pesanan pertamamu.
                </p>
                <a href="{{ $catalogUrl }}"
                   class="mt-4 flex h-11 w-full items-center justify-center gap-2 rounded-full bg-white px-5 text-[11px] font-bold text-[var(--brand-deep)] transition hover:bg-[var(--brand-soft)]">
                    Mulai belanja
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endsection
