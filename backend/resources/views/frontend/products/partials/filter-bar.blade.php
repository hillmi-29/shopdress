@php
    use App\Support\FrontendUrls;

    $ajax = $ajax ?? false;
    $categoryMode = $categoryMode ?? 'filter';
    $activeCatSlug = $activeCatSlug ?? '';
    $activeSort = $activeSort ?? 'popular';
    $totalCount = $totalCount ?? 0;
    $visibleCount = $visibleCount ?? $totalCount;
    $allProductsUrl = $allProductsUrl ?? FrontendUrls::catalog();
    $categoryPageBase = rtrim(url('/kategori'), '/');
    $sortOptions = ['popular', 'latest', 'price_asc', 'price_desc', 'name_asc', 'name_desc'];
    $activeSort = in_array($activeSort, $sortOptions, true) ? $activeSort : 'popular';
    $sortSuffix = $activeSort === 'popular' ? '' : '?sort='.urlencode($activeSort);
@endphp

<form method="GET"
      action="{{ $filterAction }}"
      @if ($ajax) x-ref="filterForm" @change="applyFilters()" @endif
      class="rounded-[1.35rem] border border-[var(--border)] bg-[var(--surface)] p-3 sm:p-4">
    @if (request()->filled('search'))
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
                        @if ($categoryMode !== 'page') name="category" @endif
                        @if ($categoryMode === 'page')
                            onchange="window.location.assign(this.value ? '{{ $categoryPageBase }}/' + encodeURIComponent(this.value) + '{{ $sortSuffix }}' : '{{ $allProductsUrl }}{{ $sortSuffix }}')"
                        @endif
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
                <span id="catalog-visible-count">{{ $visibleCount }} dari {{ $totalCount }} tampil</span>
            </div>

            <div class="relative">
                <select name="sort"
                        aria-label="Urutkan produk"
                        @if (! $ajax) onchange="this.form.submit()" @endif
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
