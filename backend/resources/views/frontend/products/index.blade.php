@extends('frontend.layouts.app')

@php
    $catalogUrl = \App\Support\FrontendUrls::catalog();
    $productUrl = fn ($p) => \App\Support\FrontendUrls::product($p);
    $totalCount = $products->total();
    $activeCatSlug = request('category', '');
    $activeSort = request('sort', 'popular');
    $hasSearch = request()->filled('search');
    $activeCategory = $categories->firstWhere('slug', $activeCatSlug);
    $hasFilters = request()->hasAny(['category', 'sort', 'search', 'min_price', 'max_price']);
@endphp

@section('title', ($activeCategory ? $activeCategory->name.' — ' : 'Koleksi Produk — ').\App\Models\SiteSetting::storeName())
@section('meta_description', $activeCategory ? Str::limit(strip_tags($activeCategory->description ?? ''), 160) : 'Jelajahi seluruh koleksi dress, gamis, abaya, dan busana wanita modern '.\App\Models\SiteSetting::storeName().' dengan bahan premium.')

@section('content')

    <div x-data="catalogFilter({ endpoint: @js($catalogUrl) })"
         @submit.prevent="applyFilters()">

        {{-- Editorial page header --}}
        <header id="catalog-header" class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
            <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

            @if ($activeCategory)
                <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
                    <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ \App\Support\FrontendUrls::categoryIndex() }}" class="transition hover:text-[var(--brand)]">Kategori</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-[var(--text)]" aria-current="page">{{ $activeCategory->name }}</span>
                </nav>

                <div class="relative mt-5 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-xl">
                        <p class="section-kicker">Koleksi kategori</p>
                        <h1 class="mt-2 font-display text-[2.5rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--brand)] sm:text-[3.4rem]">
                            {{ $activeCategory->name }}
                        </h1>
                        <p id="catalog-summary" class="mt-3 max-w-md text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                            {{ $activeCategory->description ?: "Pilihan busana dari kategori {$activeCategory->name} dengan bahan sejuk, potongan nyaman, dan detail rapi." }}
                        </p>
                    </div>

                    <a href="{{ $catalogUrl }}"
                       class="inline-flex h-9 w-fit items-center gap-1.5 rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                        Semua produk
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            @else
                <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
                    <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-[var(--text)]" aria-current="page">Produk</span>
                </nav>

                <div class="relative mt-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="section-kicker">Katalog lengkap</p>
                        <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--brand)] sm:text-6xl">
                            Produk (<span id="catalog-count">{{ $totalCount }}</span>)
                        </h1>
                        <div id="catalog-summary" class="mt-3 max-w-xl text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                            @if ($hasSearch)
                                Hasil pencarian untuk <span class="font-bold text-[var(--text)]">"{{ request('search') }}"</span>. Temukan potongan yang paling sesuai dengan gayamu.
                            @else
                                Temukan busana wanita idamanmu — potongan anggun, bahan sejuk, dan detail rapi dengan standar butik.
                            @endif
                        </div>
                    </div>

                    <div class="relative flex flex-wrap items-center gap-2">
                        @if ($hasSearch)
                            <span class="inline-flex items-center gap-2 rounded-full bg-[var(--accent-soft)] px-3.5 py-2 text-[10px] font-bold text-[#7a5a1f]">
                                "{{ request('search') }}"
                                <a href="{{ $catalogUrl }}" class="text-[#7a5a1f]/70 transition hover:text-[#7a5a1f]" aria-label="Hapus pencarian">×</a>
                            </span>
                        @endif
                        @if ($hasFilters)
                            <a href="{{ $catalogUrl }}"
                               class="inline-flex items-center gap-1.5 rounded-full border border-[var(--border)] bg-[var(--surface)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </header>

        {{-- Filter and sort bar --}}
        <div class="mt-5">
            @include('frontend.products.partials.filter-bar', [
                'filterAction' => $catalogUrl,
                'ajax' => true,
                'categoryMode' => 'filter',
                'activeCatSlug' => $activeCatSlug,
                'activeSort' => $activeSort,
                'categories' => $categories,
                'totalCount' => $totalCount,
                'visibleCount' => $products->count(),
            ])

            @include('frontend.products.partials.size-guide')
        </div>

        {{-- Results --}}
        <div x-ref="results" class="relative mt-7" :aria-busy="filtering ? 'true' : 'false'">
            {{-- Skeleton shimmer --}}
            <div x-show="filtering" x-cloak class="grid grid-cols-2 gap-3.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
                @for ($i = 0; $i < 8; $i++)
                    <div class="overflow-hidden rounded-[1.4rem] border border-[var(--border)] bg-[var(--surface)] p-2.5 sm:p-3">
                        <div class="skeleton-shimmer aspect-[3/4] w-full rounded-[1.05rem]"></div>
                        <div class="space-y-2 px-1 pb-1 pt-3.5">
                            <div class="skeleton-shimmer h-2.5 w-16 rounded-full"></div>
                            <div class="skeleton-shimmer h-3.5 w-full rounded-full"></div>
                            <div class="skeleton-shimmer h-3.5 w-3/4 rounded-full"></div>
                            <div class="flex items-end justify-between pt-3">
                                <div class="skeleton-shimmer h-5 w-20 rounded-full"></div>
                                <div class="skeleton-shimmer h-9 w-9 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div x-show="!filtering">
                <div id="catalog-grid-content">
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

                        <div class="mt-12 flex flex-col items-center gap-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-16 text-center">
                            <div class="pointer-events-none absolute left-1/2 top-0 h-40 w-40 -translate-x-1/2 rounded-full bg-[var(--brand-soft)]/70 blur-2xl"></div>
                            <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] text-[var(--brand)]">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                </svg>
                            </div>
                            <h2 class="relative mt-5 font-display text-2xl font-semibold tracking-[-0.03em] text-[var(--text)]">
                                Koleksi belum ditemukan
                            </h2>
                            <p class="relative mx-auto mt-2 max-w-md text-xs leading-6 text-[var(--text-muted)]">
                                Tidak ada busana yang sesuai dengan filter ini. Coba kata kunci lain atau lihat seluruh koleksi kami.
                            </p>
                            <a href="{{ $catalogUrl }}"
                               class="relative mt-6 inline-flex items-center gap-2 rounded-full bg-[var(--brand)] px-5 py-3 text-[11px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                                Lihat semua koleksi
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('catalogFilter', (config) => ({
                endpoint: config.endpoint,
                filtering: false,

                async applyFilters() {
                    const form = this.$refs.filterForm;

                    if (! form) {
                        return;
                    }

                    const params = new URLSearchParams(new FormData(form));

                    params.forEach((value, key) => {
                        if (value === '') {
                            params.delete(key);
                        }
                    });

                    const query = params.toString();
                    const url = this.endpoint + (query ? '?' + query : '');
                    const grid = this.$refs.results?.querySelector('#catalog-grid-content');
                    const summary = document.getElementById('catalog-summary');
                    const count = document.getElementById('catalog-count');
                    const visibleCount = document.getElementById('catalog-visible-count');

                    this.filtering = true;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'text/html',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                        });

                        if (! response.ok) {
                            throw new Error('Filter request failed');
                        }

                        const html = await response.text();
                        const documentFragment = new DOMParser().parseFromString(html, 'text/html');
                        const nextGrid = documentFragment.querySelector('#catalog-grid-content');
                        const nextSummary = documentFragment.querySelector('#catalog-summary');

                        if (! nextGrid || ! grid) {
                            throw new Error('Filter response missing grid');
                        }

                        grid.innerHTML = nextGrid.innerHTML;

                        const nextHeader = documentFragment.querySelector('#catalog-header');
                        const header = document.getElementById('catalog-header');

                        if (header && nextHeader) {
                            header.innerHTML = nextHeader.innerHTML;
                        } else if (summary && nextSummary) {
                            summary.innerHTML = nextSummary.innerHTML;
                        }

                        if (count) {
                            const nextCount = documentFragment.querySelector('#catalog-count')?.textContent?.trim();
                            if (nextCount) {
                                count.textContent = nextCount;
                            }
                        }

                        if (visibleCount) {
                            visibleCount.textContent = documentFragment.querySelector('#catalog-visible-count')?.textContent?.trim() || visibleCount.textContent;
                        }

                        if (documentFragment.title) {
                            document.title = documentFragment.title;
                        }

                        window.history.replaceState({}, '', url);

                        if (window.Alpine?.initTree) {
                            window.Alpine.initTree(grid);
                        }

                        window.ShopDressReveal?.refresh();
                    } catch (error) {
                        form.submit();
                        return;
                    } finally {
                        this.filtering = false;
                    }
                },
            }));
        });
    </script>
@endpush
