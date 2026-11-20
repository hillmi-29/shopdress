@extends('frontend.layouts.app')

@section('title', $category->name.' — '.\App\Models\SiteSetting::storeName())
@section('meta_description', Str::limit(strip_tags($category->description ?? 'Koleksi '.$category->name.' terbaru dari '.\App\Models\SiteSetting::storeName().'.'), 160))

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $categoryIndexUrl = \App\Support\FrontendUrls::categoryIndex();
        $productUrl = fn ($product) => \App\Support\FrontendUrls::product($product);
        $categoryUrl = fn ($category) => \App\Support\FrontendUrls::category($category);
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

    <div class="mt-5">
        @include('frontend.products.partials.filter-bar', [
            'filterAction' => $categoryUrl($category),
            'categoryMode' => 'page',
            'activeCatSlug' => $category->slug,
            'activeSort' => request('sort', 'popular'),
            'categories' => $categories,
            'totalCount' => $products->total(),
            'visibleCount' => $products->count(),
            'allProductsUrl' => $catalogUrl,
        ])

        @include('frontend.products.partials.size-guide')

        <section class="mt-7" aria-label="Produk kategori {{ $category->name }}">
            @if ($products->isNotEmpty())
                <div class="grid grid-cols-2 gap-3.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($products as $product)
                        <div class="reveal" style="--reveal-delay: {{ ($loop->index % 4) * 55 }}ms">
                            <x-product-card
                                :product="$product"
                                :wishlisted="in_array($product->id, $wishlistProductIds, true)"
                                :productUrl="$productUrl($product)"
                            />
                        </div>
                    @endforeach
                </div>

                <x-pagination :paginator="$products" class="mt-10" />
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
        </section>
    </div>
@endsection
