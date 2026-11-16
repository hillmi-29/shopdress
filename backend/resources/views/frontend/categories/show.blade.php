@extends('frontend.layouts.app')

@section('title', $category->name.' — ShopDress')
@section('meta_description', Str::limit(strip_tags($category->description ?? "Koleksi {$category->name} terbaru dari ShopDress."), 160))

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $categoryIndexUrl = \App\Support\FrontendUrls::categoryIndex();
        $productUrl = fn ($product) => \App\Support\FrontendUrls::product($product);
        $categoryUrl = fn ($category) => \App\Support\FrontendUrls::category($category);
        $resultLabel = $products->total() === 1 ? '1 produk' : $products->total().' produk';
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ $categoryIndexUrl }}" class="transition hover:text-[var(--brand)]">Kategori</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">{{ $category->name }}</span>
    </nav>

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 right-1/3 h-36 w-36 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Koleksi kategori</p>
                <h1 class="mt-2 font-display text-[2.5rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--brand)] sm:text-[3.4rem]">
                    {{ $category->name }}
                </h1>
                <p class="mt-3 max-w-md text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    {{ $category->description ?: "Pilihan busana dari kategori {$category->name} dengan bahan sejuk, potongan nyaman, dan detail rapi." }}
                </p>
            </div>

            <a href="{{ $catalogUrl }}"
               class="inline-flex h-9 w-fit items-center gap-1.5 rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                Semua produk
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </header>

    <div class="mt-5 grid items-start gap-5 lg:grid-cols-[0.27fr_0.73fr] lg:gap-7">
        {{-- Category navigation --}}
        <aside class="rounded-[1.4rem] border border-[var(--border)] bg-[var(--surface)] p-3.5 lg:sticky lg:top-24">
            <div class="flex items-center justify-between gap-3 px-1.5 pb-2">
                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Kategori</p>
                <span class="rounded-full bg-[var(--surface-2)] px-2 py-0.5 text-[9px] font-bold text-[var(--text-muted)]">{{ $categories->count() }}</span>
            </div>

            <ul class="space-y-1" role="list">
                <li>
                    <a href="{{ $catalogUrl }}"
                       class="flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-[11px] font-semibold text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)]">
                        Semua produk
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>

                @foreach ($categories as $cat)
                    <li>
                        <a href="{{ $categoryUrl($cat) }}"
                           @if ($cat->id === $category->id) aria-current="page" @endif
                           class="flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-[11px] transition {{ $cat->id === $category->id ? 'bg-[var(--brand)] font-bold text-white shadow-[0_8px_18px_-14px_rgba(124,58,237,0.9)]' : 'font-semibold text-[var(--text-muted)] hover:bg-[var(--surface-2)] hover:text-[var(--text)]' }}">
                            <span class="truncate">{{ $cat->name }}</span>
                            <span class="shrink-0 text-[9px] {{ $cat->id === $category->id ? 'text-white/60' : 'text-[var(--text-subtle)]' }}">{{ $cat->products_count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-3 border-t border-[var(--border-light)] px-1.5 pt-3">
                <p class="text-[9px] leading-4 text-[var(--text-subtle)]">Butuh bantuan memilih ukuran?</p>
                <a href="{{ \App\Support\FrontendUrls::help() }}" class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">
                    Baca panduan
                    <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </aside>

        {{-- Products --}}
        <main>
            @if ($products->isNotEmpty())
                <div class="flex flex-col gap-2 pb-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[11px] text-[var(--text-muted)]">
                        Menampilkan <span class="font-bold text-[var(--text)]">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                        dari <span class="font-bold text-[var(--text)]">{{ $resultLabel }}</span>
                    </p>
                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[9px] font-bold text-[var(--text-muted)]">
                        <span class="h-1 w-1 rounded-full bg-[var(--brand)]"></span>
                        Koleksi {{ $category->name }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-3">
                    @foreach ($products as $product)
                        <x-product-card
                            :product="$product"
                            :wishlisted="in_array($product->id, $wishlistProductIds, true)"
                            :productUrl="$productUrl($product)"
                        />
                    @endforeach
                </div>

                <x-pagination :paginator="$products" class="mt-7" />
            @else
                <div class="overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6.75a3.75 3.75 0 1 0-7.5 0v3.75m-.75 11.25h10.5a1.5 1.5 0 0 0 1.5-1.5v-7.5a1.5 1.5 0 0 0-1.5-1.5H6.75a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>
                    </div>
                    <h2 class="mt-5 font-display text-2xl font-semibold tracking-[-0.02em] text-[var(--text)]">Belum ada produk di kategori ini</h2>
                    <p class="mx-auto mt-2 max-w-md text-xs leading-6 text-[var(--text-muted)]">Koleksi {{ $category->name }} sedang kosong. Cek kategori lain atau kembali lagi untuk produk terbaru.</p>
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                        <a href="{{ $categoryIndexUrl }}" class="inline-flex h-10 items-center rounded-full bg-[var(--brand)] px-5 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">Lihat kategori lain</a>
                        <a href="{{ $catalogUrl }}" class="inline-flex h-10 items-center rounded-full border border-[var(--border)] px-5 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">Semua produk</a>
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection
