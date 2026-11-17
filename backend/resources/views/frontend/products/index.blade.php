@extends('frontend.layouts.app')

@section('title', 'Koleksi Produk — ShopDress')
@section('meta_description', 'Jelajahi seluruh koleksi dress, gamis, abaya, dan busana wanita modern ShopDress dengan bahan premium.')

@section('content')
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

    <div x-data="catalogFilter({ endpoint: @js($catalogUrl) })"
         @submit.prevent="applyFilters()">

        {{-- Editorial page header --}}
        <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
            <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

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
                        @elseif ($activeCategory)
                            Koleksi pilihan dari kategori <span class="font-bold text-[var(--text)]">{{ $activeCategory->name }}</span>. Dikurasi dengan bahan sejuk dan jahitan rapi.
                        @else
                            Temukan busana wanita idamanmu — potongan anggun, bahan sejuk, dan detail rapi dengan standar butik.
                        @endif
                    </div>
                </div>

                <div class="relative flex flex-wrap items-center gap-2">
                    @if ($activeCategory)
                        <span class="inline-flex items-center gap-2 rounded-full bg-[var(--brand-soft)] px-3.5 py-2 text-[10px] font-bold text-[var(--brand-dark)]">
                            {{ $activeCategory->name }}
                            <a href="{{ $catalogUrl }}" class="text-[var(--brand)]/70 transition hover:text-[var(--brand)]" aria-label="Hapus filter kategori">×</a>
                        </span>
                    @endif
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
        </header>

        {{-- Filter and sort bar --}}
        <form method="GET"
              action="{{ $catalogUrl }}"
              x-ref="filterForm"
              @change="applyFilters()"
              class="mt-5 rounded-[1.35rem] border border-[var(--border)] bg-[var(--surface)] p-3 sm:p-4">
            @if ($hasSearch)
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if (request()->filled('min_price'))
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            @endif
            @if (request()->filled('max_price'))
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            @endif

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <label for="category-filter" class="flex items-center gap-2 pl-1 text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M10 18h4" />
                        </svg>
                        Filter
                    </label>

                    <div class="relative">
                        <select id="category-filter"
                                name="category"
                                class="h-10 w-full min-w-[13rem] cursor-pointer appearance-none rounded-full border border-[var(--border)] bg-[var(--surface-2)]/70 pl-4 pr-10 text-[11px] font-bold text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--brand-soft)] sm:min-w-[15rem]">
                            <option value="">Semua Kategori ({{ $categories->sum('products_count') }})</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ $activeCatSlug === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[var(--text-subtle)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 sm:justify-end">
                    <div class="flex items-center gap-1.5 text-[10px] font-semibold text-[var(--text-subtle)]">
                        <span class="hidden h-1.5 w-1.5 rounded-full bg-[var(--success)] sm:block"></span>
                        <span id="catalog-visible-count">{{ $products->count() }} dari {{ $totalCount }} tampil</span>
                    </div>

                    <div class="relative">
                        <select name="sort"
                                aria-label="Urutkan produk"
                                class="h-10 w-full min-w-[10.5rem] cursor-pointer appearance-none rounded-full border border-[var(--border)] bg-[var(--surface-2)]/70 pl-4 pr-10 text-[11px] font-bold text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--brand-soft)]">
                            <option value="popular" {{ $activeSort === 'popular' ? 'selected' : '' }}>Terlaris</option>
                            <option value="latest" {{ $activeSort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ $activeSort === 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                            <option value="price_desc" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>Harga: Termahal</option>
                            <option value="name_asc" {{ $activeSort === 'name_asc' ? 'selected' : '' }}>Nama: A — Z</option>
                            <option value="name_desc" {{ $activeSort === 'name_desc' ? 'selected' : '' }}>Nama: Z — A</option>
                        </select>
                        <svg class="pointer-events-none absolute right-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[var(--text-subtle)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </form>

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

                        if (summary && nextSummary) {
                            summary.innerHTML = nextSummary.innerHTML;
                        }

                        if (count) {
                            count.textContent = documentFragment.querySelector('#catalog-count')?.textContent?.trim() || count.textContent;
                        }

                        if (visibleCount) {
                            visibleCount.textContent = documentFragment.querySelector('#catalog-visible-count')?.textContent?.trim() || visibleCount.textContent;
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
