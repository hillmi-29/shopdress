@extends('frontend.layouts.app')

@section('title', 'Wishlist — ShopDress')
@section('meta_description', 'Kelola wishlist Anda di ShopDress. Simpan produk favorit dan beli kapan saja.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $productUrl = fn ($product) => \App\Support\FrontendUrls::product($product);
    @endphp

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[#d1456b]/8 blur-2xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

        <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">Wishlist</span>
        </nav>

        <div class="relative mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Koleksi pribadimu</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--text)] sm:text-6xl">
                    Wishlist
                </h1>
                <p class="mt-3 max-w-lg text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Simpan potongan yang membuatmu jatuh cinta, lalu kembali saat waktunya berbelanja.
                </p>
            </div>

            @auth
                <span class="inline-flex items-center gap-2 self-start rounded-full border border-[var(--border)] bg-[var(--bg)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)] sm:self-auto">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#d1456b]"></span>
                    {{ $wishlists->count() }} produk tersimpan
                </span>
            @endauth
        </div>
    </header>

    <div class="mt-6">
        @auth
            @if ($wishlists->isNotEmpty())
                <div class="grid grid-cols-2 gap-3.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($wishlists as $wishlist)
                        <div class="reveal" style="--reveal-delay: {{ ($loop->index % 4) * 55 }}ms">
                            <x-product-card
                                :product="$wishlist->product"
                                :wishlisted="true"
                                :productUrl="$productUrl($wishlist->product)"
                            />
                        </div>
                    @endforeach
                </div>
            @else
                <x-empty-state
                    class="mx-auto max-w-md py-16"
                    title="Wishlist masih kosong"
                    message="Belum ada produk yang kamu simpan. Jelajahi katalog dan ketuk ikon hati pada produk favoritmu."
                    action-label="Jelajahi Katalog"
                    :action-url="$catalogUrl"
                />
            @endif
        @else
            <x-empty-state
                class="mx-auto max-w-md py-16"
                title="Masuk untuk melihat wishlist"
                message="Wishlist disimpan per akun. Masuk atau daftar untuk mulai menyimpan produk favoritmu."
                action-label="Masuk"
                :action-url="\App\Support\FrontendUrls::login()"
            />
        @endauth
    </div>
@endsection
