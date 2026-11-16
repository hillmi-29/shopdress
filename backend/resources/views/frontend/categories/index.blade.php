@extends('frontend.layouts.app')

@section('title', 'Kategori Produk — ShopDress')
@section('meta_description', 'Jelajahi semua kategori busana wanita ShopDress: gamis, abaya, casual, Korean style, maxi dress, dan party dress.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $categoryUrl = fn ($category) => \App\Support\FrontendUrls::category($category);
        $totalProducts = $categories->sum('products_count');
        $categoryImages = [
            'Gamis & Abaya Modern' => asset('storage/products/demo-1-1.png'),
            'Korean Style Dress' => asset('storage/products/demo-2-3.png'),
            'Casual & Daily Dress' => asset('storage/products/demo-3-5.png'),
            'Party & Formal Dress' => asset('storage/products/demo-4-6.png'),
            'Maxi & Long Dress' => asset('storage/products/demo-5-7.png'),
        ];
        $fallbackCategoryImages = [
            asset('images/editorial/hero-1.jpg'),
            asset('images/editorial/hero-2.jpg'),
            asset('images/editorial/hero-3.jpg'),
            asset('images/editorial/about.jpg'),
            asset('images/editorial/promo.jpg'),
        ];
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">Kategori</span>
    </nav>

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-36 w-36 rounded-full bg-[var(--accent-soft)]/80 blur-2xl"></div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Temukan gayamu</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--brand)] sm:text-6xl">
                    Kategori
                </h1>
                <p class="mt-3 max-w-md text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Dari gamis anggun sampai dress kasual — pilih kategori yang paling sesuai dengan momen dan gaya harianmu.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface-2)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand)]"></span>
                    {{ $categories->count() }} kategori
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)]">
                    {{ $totalProducts }} produk
                </span>
            </div>
        </div>
    </header>

    @if ($categories->isNotEmpty())
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a href="{{ $categoryUrl($category) }}"
                   class="category-card reveal group relative flex min-h-[15rem] flex-col justify-between overflow-hidden rounded-[1.4rem] border border-[var(--border)] bg-[var(--surface-2)] p-5 text-white hover:border-[var(--brand)] hover:shadow-[0_24px_50px_-30px_rgba(50,19,95,0.65)] sm:min-h-[17rem] sm:p-6"
                   style="--reveal-delay: {{ $loop->index * 60 }}ms">
                    <img src="{{ $categoryImages[$category->name] ?? $fallbackCategoryImages[$loop->index % count($fallbackCategoryImages)] }}"
                         alt="Koleksi {{ $category->name }}"
                         loading="lazy"
                         class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-[1.045]">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#241d2e]/90 via-[#241d2e]/30 to-[#241d2e]/10"></div>

                    <div class="relative flex items-start justify-between gap-3">
                        <span class="text-[10px] font-bold tabular-nums tracking-[0.14em] text-white/60">
                            {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-full border border-white/25 bg-white/10 text-white backdrop-blur-sm transition-all duration-250 ease-out group-hover:border-white group-hover:bg-white group-hover:text-[var(--brand)]">
                            <svg class="h-4 w-4 -rotate-45 transition-transform duration-250 ease-out group-hover:rotate-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>

                    <div class="relative mt-10">
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/55">Koleksi</p>
                        <h2 class="mt-1.5 font-display text-2xl font-semibold leading-[1.1] tracking-[-0.025em] text-white sm:text-[1.7rem]">
                            {{ $category->name }}
                        </h2>
                        <p class="mt-1.5 text-[10px] font-medium text-white/65">
                            {{ $category->products_count }} produk
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="reveal mt-8 flex flex-col items-start justify-between gap-3 rounded-[1.35rem] border border-dashed border-[var(--brand)]/30 bg-[var(--brand-soft)]/50 px-5 py-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-[11px] font-bold text-[var(--brand-dark)]">Masih bingung memilih kategori?</p>
                <p class="mt-0.5 text-[10px] text-[var(--text-muted)]">Lihat seluruh koleksi dan filter sesuai ukuran, warna, atau harga.</p>
            </div>
            <a href="{{ $catalogUrl }}" class="inline-flex h-9 items-center gap-1.5 rounded-full bg-[var(--brand)] px-4 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                Semua produk
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    @else
        <div class="mt-5 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-16 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </div>
            <h2 class="mt-5 font-display text-2xl font-semibold tracking-[-0.02em] text-[var(--text)]">Belum ada kategori</h2>
            <p class="mx-auto mt-2 max-w-md text-xs leading-6 text-[var(--text-muted)]">Kategori produk akan muncul di sini setelah ditambahkan melalui panel admin.</p>
            <a href="{{ $catalogUrl }}" class="mt-5 inline-flex h-10 items-center rounded-full bg-[var(--brand)] px-5 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">Lihat semua produk</a>
        </div>
    @endif
@endsection
