@extends('frontend.layouts.app')

@section('title', $product->name.' — ShopDress')
@section('meta_description', Str::limit(strip_tags($product->description ?? ''), 160))

@section('content')
    @php
        $homeUrl = \App\Support\FrontendUrls::home();
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $categoryUrl = $product->category
            ? \App\Support\FrontendUrls::category($product->category)
            : $catalogUrl;
        $productUrl = fn ($item) => \App\Support\FrontendUrls::product($item);

        $imageUrl = function (?string $path): ?string {
            if (! $path) return null;
            return str_starts_with($path, 'http')
                ? $path
                : asset('storage/'.ltrim($path, '/'));
        };

        $primaryImageUrl = $imageUrl($product->primaryImage?->image_path);

        $galleryImages = $product->images
            ->map(fn ($image) => $imageUrl($image->image_path))
            ->filter()
            ->unique()
            ->values();

        if ($primaryImageUrl && ! $galleryImages->contains($primaryImageUrl)) {
            $galleryImages->prepend($primaryImageUrl);
        }

        if ($galleryImages->isEmpty()) {
            $galleryImages->push('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="800" fill="%23f5f3ff"><rect width="600" height="800"/><text x="300" y="400" font-size="28" fill="%237c3aed" text-anchor="middle" font-family="sans-serif">ShopDress Premium</text></svg>');
        }

        $defaultVariant = $product->variants->firstWhere(fn ($variant) => $variant->stock > 0)
            ?? $product->variants->first();

        $variantsData = $product->variants->map(fn ($variant) => [
            'id' => $variant->id,
            'size' => (string) $variant->size,
            'color' => (string) $variant->color,
            'stock' => (int) $variant->stock,
            'additional_price' => (float) $variant->additional_price,
            'final_price' => (float) $product->price + (float) $variant->additional_price,
            'sku' => $variant->sku,
        ])->values();

        $colors = $product->variants->pluck('color')->filter()->unique()->values();

        $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $sizes = $product->variants->pluck('size')->filter()->unique()
            ->sortBy(function ($size) use ($sizeOrder) {
                $index = array_search(strtoupper((string) $size), $sizeOrder, true);
                return $index === false ? 99 : $index;
            })
            ->values();

        $swatches = [
            'sage-green' => '#9caf88',
            'mocca-nude' => '#c9a98d',
            'hitam-pekat' => '#1f2937',
            'hitam' => '#111827',
            'midnight-black' => '#171a21',
            'putih' => '#ffffff',
            'off-white' => '#f6f2e9',
            'navy' => '#1e3a8a',
            'navy-blue' => '#21365f',
            'dusty-rose' => '#e7c0c0',
            'dusty-pink' => '#d9a7ad',
            'soft-cream' => '#f3ead9',
            'cream' => '#fbf3e4',
            'maroon' => '#7f1d1d',
            'emerald' => '#047857',
            'emerald-green' => '#0f6b53',
            'abu-abu' => '#9ca3af',
            'terracotta' => '#b66a4c',
            'caramel-beige' => '#c49d74',
        ];

        $reviews = $product->reviews;
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? round((float) $reviews->avg('rating'), 1) : 0;

        $ratingBreakdown = collect([5, 4, 3, 2, 1])->map(fn ($star) => [
            'star' => $star,
            'count' => $reviews->where('rating', $star)->count(),
            'percent' => $reviewCount > 0
                ? (int) round(($reviews->where('rating', $star)->count() / $reviewCount) * 100)
                : 0,
        ]);

        $totalStock = (int) $product->variants->sum('stock');

        $alpineConfig = [
            'variants' => $variantsData,
            'basePrice' => (float) $product->price,
            'initialVariantId' => $defaultVariant?->id,
            'productId' => $product->id,
            'wishlisted' => (bool) $wishlisted,
            'images' => $galleryImages,
        ];
    @endphp

    {{-- Breadcrumb --}}
    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ $homeUrl }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ $catalogUrl }}" class="transition hover:text-[var(--brand)]">Produk</a>
        @if ($product->category)
            <span aria-hidden="true">/</span>
            <a href="{{ $categoryUrl }}" class="transition hover:text-[var(--brand)]">{{ $product->category->name }}</a>
        @endif
        <span aria-hidden="true">/</span>
        <span class="max-w-[12rem] truncate text-[var(--text)] sm:max-w-none" aria-current="page">{{ $product->name }}</span>
    </nav>

    <div x-data="productDetail(@js($alpineConfig))"
         @wishlist-changed.window="if ($event.detail.productId === {{ $product->id }}) wishlisted = $event.detail.action === 'added'"
         class="space-y-14 sm:space-y-16">

        {{-- Product hero --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-10 xl:gap-12">
            {{-- Gallery --}}
            <div class="lg:col-span-7">
                <div class="lg:sticky lg:top-24">
                    <div class="group relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface-2)]">
                        <div class="relative aspect-[3/4] w-full sm:aspect-[4/5] lg:aspect-[3/4]">
                            <template x-for="(img, idx) in images" :key="idx">
                                <img x-show="activeImage === idx"
                                     :src="img"
                                     alt="{{ $product->name }}"
                                     class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.015]"
                                     :loading="idx === 0 ? 'eager' : 'lazy'">
                            </template>

                            <div class="pointer-events-none absolute inset-x-0 top-0 h-28 bg-gradient-to-b from-[#241d2e]/25 to-transparent"></div>

                            <div class="absolute left-4 top-4 flex flex-col items-start gap-2">
                                @if ($product->featured)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--surface)]/95 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-[var(--brand)] shadow-sm backdrop-blur">
                                        <svg class="h-2.5 w-2.5 fill-[var(--accent)] text-[var(--accent)]" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                                        </svg>
                                        Unggulan
                                    </span>
                                @endif

                                @if ($totalStock <= 0)
                                    <span class="rounded-full bg-[var(--text)] px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.1em] text-white">Habis</span>
                                @elseif ($totalStock <= 5)
                                    <span class="rounded-full bg-[var(--accent)] px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.1em] text-white">Stok terbatas</span>
                                @endif
                            </div>

                            <div class="absolute bottom-4 right-4 flex items-center gap-1.5 rounded-full bg-[var(--surface)]/90 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[0.12em] text-[var(--text-muted)] backdrop-blur">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75 12 21l9.75-5.25M2.25 12 12 17.25 21.75 12M12 2.25 2.25 7.5 12 12.75 21.75 7.5 12 2.25Z" />
                                </svg>
                                <span x-text="(activeImage + 1) + ' / ' + images.length">1 / {{ $galleryImages->count() }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($galleryImages->count() > 1)
                        <div class="no-scrollbar mt-3 flex gap-2.5 overflow-x-auto pb-1">
                            <template x-for="(img, idx) in images" :key="'thumb-' + idx">
                                <button type="button"
                                        @click="activeImage = idx"
                                        class="relative h-24 w-[4.5rem] shrink-0 overflow-hidden rounded-xl border-2 bg-[var(--surface-2)] transition-all duration-200 ease-out focus:outline-none sm:h-28 sm:w-20"
                                        :class="activeImage === idx ? 'border-[var(--brand)] ring-2 ring-[var(--brand-soft)]' : 'border-transparent opacity-65 hover:opacity-100'"
                                        :aria-label="'Lihat foto ' + (idx + 1)">
                                    <img :src="img" alt="" class="h-full w-full object-cover">
                                </button>
                            </template>
                        </div>
                    @endif

                    <div class="mt-3 flex items-center justify-between gap-3 px-1 text-[10px] font-medium text-[var(--text-subtle)]">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-[var(--brand)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                <circle cx="12" cy="12" r="2.25" />
                            </svg>
                            Foto asli koleksi ShopDress
                        </span>
                        <span class="hidden sm:inline">Geser untuk melihat detail</span>
                    </div>
                </div>
            </div>

            {{-- Product info --}}
            <div class="lg:col-span-5">
                <div class="rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-7">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        @if ($product->category)
                            <a href="{{ $categoryUrl }}"
                               class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-[0.16em] text-[var(--brand)] transition hover:text-[var(--brand-dark)]">
                                <span class="h-1 w-4 rounded-full bg-[var(--accent)]"></span>
                                {{ $product->category->name }}
                            </a>
                        @endif

                        <span class="rounded-full bg-[var(--surface-2)] px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.08em] text-[var(--text-subtle)]">
                            {{ $product->variants->count() }} varian
                        </span>
                    </div>

                    <h1 class="mt-3 font-display text-[2rem] font-semibold leading-[1.02] tracking-[-0.04em] text-[var(--text)] sm:text-[2.6rem]">
                        {{ $product->name }}
                    </h1>

                    {{-- Rating --}}
                    <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1.5">
                        <div class="flex items-center gap-0.5 text-[var(--accent)]" role="img" aria-label="{{ $averageRating }} dari 5 bintang">
                            @for ($star = 1; $star <= 5; $star++)
                                <svg class="h-3.5 w-3.5 {{ $star <= round($averageRating) ? 'fill-current' : 'fill-[var(--border)]' }}" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[11px] font-bold text-[var(--text)]">{{ number_format($averageRating, 1, ',', '.') }}</span>
                        <span class="text-[10px] text-[var(--text-subtle)]">({{ $reviewCount }} ulasan)</span>
                        <span class="hidden h-3 w-px bg-[var(--border)] sm:block"></span>
                        <span class="text-[10px] font-medium text-[var(--text-muted)]">{{ $totalStock }} pcs tersedia</span>
                    </div>

                    {{-- Price --}}
                    <div class="mt-5 flex flex-wrap items-end gap-x-3 gap-y-1">
                        <p class="text-[2rem] font-extrabold leading-none tracking-[-0.04em] text-[var(--brand-dark)]">
                            <span class="text-sm font-bold text-[var(--text-muted)]">Rp</span>
                            <span x-text="formatPrice(price)">{{ number_format((float) $product->price, 0, ',', '.') }}</span>
                        </p>
                        <span x-show="selectedVariant && selectedVariant.additional_price > 0" x-cloak class="rounded-full bg-[var(--brand-soft)] px-2.5 py-1 text-[10px] font-bold text-[var(--brand)]">
                            + Rp <span x-text="formatPrice(selectedVariant?.additional_price ?? 0)"></span>
                        </span>
                    </div>
                    <p class="mt-1.5 text-[10px] text-[var(--text-subtle)]">Harga sudah termasuk PPN. Ongkir dihitung saat checkout.</p>

                    <div class="my-5 h-px bg-[var(--border-light)]"></div>

                    {{-- Live stock --}}
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-[var(--surface-2)]/70 px-3.5 py-2.5">
                        <span class="inline-flex items-center gap-2 text-[10px] font-bold">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60" :class="available ? 'bg-[var(--success)]' : 'bg-[var(--error)]'"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full" :class="available ? 'bg-[var(--success)]' : 'bg-[var(--error)]'"></span>
                            </span>
                            <span :class="available ? 'text-[var(--success)]' : 'text-[var(--error)]'"
                                  x-text="available ? 'Stok tersedia · ' + stock + ' pcs' : 'Stok habis untuk varian ini'"></span>
                        </span>
                        <span class="text-[9px] font-medium text-[var(--text-subtle)]">Realtime</span>
                    </div>

                    {{-- Color --}}
                    @if ($colors->isNotEmpty())
                        <div class="mt-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[10px] font-black uppercase tracking-[0.12em] text-[var(--text-muted)]">Warna</span>
                                <span class="text-[10px] font-bold text-[var(--brand)]" x-text="selectedColor">{{ $defaultVariant?->color }}</span>
                            </div>
                            <div class="mt-2.5 flex flex-wrap gap-2">
                                @foreach ($colors as $color)
                                    @php $hex = $swatches[Str::slug($color)] ?? '#cbd5e1'; @endphp
                                    <button type="button"
                                            @click="selectColor(@js($color))"
                                            class="group inline-flex h-10 items-center gap-2 rounded-full border px-2.5 pr-3.5 text-[10px] font-bold transition focus:outline-none"
                                            :class="selectedColor === @js($color) ? 'border-[var(--brand)] bg-[var(--brand-soft)]/60 text-[var(--brand-dark)]' : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-muted)] hover:border-[var(--text-subtle)]'"
                                            aria-label="Pilih warna {{ $color }}">
                                        <span class="h-5 w-5 rounded-full border border-black/5 shadow-inner" style="background-color: {{ $hex }}"></span>
                                        {{ $color }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Size --}}
                    @if ($sizes->isNotEmpty())
                        <div class="mt-5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[10px] font-black uppercase tracking-[0.12em] text-[var(--text-muted)]">Ukuran</span>
                                <span class="text-[10px] font-bold text-[var(--brand)]" x-text="selectedSize">{{ $defaultVariant?->size }}</span>
                            </div>
                            <div class="mt-2.5 flex flex-wrap gap-2">
                                @foreach ($sizes as $size)
                                    <button type="button"
                                            @click="selectSize(@js($size))"
                                            class="h-10 min-w-[3rem] rounded-full border px-3.5 text-[10px] font-bold transition focus:outline-none"
                                            :class="selectedSize === @js($size) ? 'border-[var(--brand)] bg-[var(--brand)] text-white shadow-[0_8px_18px_-12px_rgba(124,58,237,0.9)]' : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-muted)] hover:border-[var(--brand)] hover:text-[var(--brand)]'">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quantity --}}
                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                        <span class="text-[10px] font-black uppercase tracking-[0.12em] text-[var(--text-muted)]">Jumlah</span>
                        <div class="inline-flex h-10 items-center rounded-full border border-[var(--border)] bg-[var(--surface)]">
                            <button type="button"
                                    @click="decrement()"
                                    :disabled="quantity <= 1"
                                    class="flex h-full w-10 items-center justify-center rounded-l-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                    aria-label="Kurangi jumlah">−</button>
                            <span class="w-9 text-center text-[11px] font-bold text-[var(--text)]" x-text="quantity">1</span>
                            <button type="button"
                                    @click="increment()"
                                    :disabled="quantity >= stock"
                                    class="flex h-full w-10 items-center justify-center rounded-r-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                    aria-label="Tambah jumlah">+</button>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-5 flex gap-2.5">
                        <button type="button"
                                @click="addToCart()"
                                :disabled="!available || adding"
                                class="inline-flex h-12 flex-1 items-center justify-center gap-2 rounded-full px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out disabled:cursor-not-allowed disabled:opacity-45"
                                :class="added ? 'bg-[var(--success)]' : 'bg-[var(--brand)] hover:bg-[var(--brand-dark)]'">
                            <svg x-show="!adding && !added" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
                            </svg>
                            <svg x-show="adding" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <svg x-show="added" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span x-show="!adding && !added">Tambah ke Keranjang</span>
                            <span x-show="adding" x-cloak>Menambahkan…</span>
                            <span x-show="added" x-cloak>Ditambahkan</span>
                        </button>

                        <button type="button"
                                @click="$dispatch('wishlist-toggle', { productId: {{ $product->id }} })"
                                class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full border transition-all duration-200 focus:outline-none"
                                :class="wishlisted ? 'border-[#d1456b]/30 bg-[#d1456b]/10 text-[#d1456b]' : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-muted)] hover:border-[#d1456b]/40 hover:text-[#d1456b]'"
                                :aria-label="wishlisted ? 'Hapus dari wishlist' : 'Simpan ke wishlist'"
                                :aria-pressed="wishlisted.toString()">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" :fill="wishlisted ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
                            </svg>
                        </button>
                    </div>

                    <button type="button"
                            @click="shareProduct()"
                            class="mt-3 inline-flex items-center gap-1.5 text-[10px] font-bold text-[var(--text-muted)] transition hover:text-[var(--brand)]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                        Bagikan produk ini
                    </button>

                    {{-- Service notes --}}
                    <div class="mt-5 grid grid-cols-1 gap-2 border-t border-[var(--border-light)] pt-4 sm:grid-cols-3">
                        @foreach ([['Dikirim dari Jakarta', 'Packing eksklusif'], ['Retur 7 hari', 'Tukar ukuran mudah'], ['Pembayaran aman', 'Via Midtrans']] as $note)
                            <div class="flex items-center gap-2 text-[9px] font-medium leading-4 text-[var(--text-muted)]">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <span>{{ $note[0] }}<br><span class="text-[var(--text-subtle)]">{{ $note[1] }}</span></span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Description and reviews --}}
        <section class="border-t border-[var(--border)] pt-10" x-data="{ tab: 'deskripsi' }">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="section-kicker">Detail koleksi</p>
                    <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                        Kenali potongannya
                    </h2>
                </div>

                <div class="flex items-center gap-1 rounded-full border border-[var(--border)] bg-[var(--surface)] p-1">
                    <button type="button"
                            @click="tab = 'deskripsi'"
                            class="rounded-full px-4 py-2 text-[10px] font-bold transition duration-200 focus:outline-none"
                            :class="tab === 'deskripsi' ? 'bg-[var(--brand)] text-white shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text)]'">
                        Deskripsi
                    </button>
                    <button type="button"
                            @click="tab = 'ulasan'"
                            class="rounded-full px-4 py-2 text-[10px] font-bold transition duration-200 focus:outline-none"
                            :class="tab === 'ulasan' ? 'bg-[var(--brand)] text-white shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text)]'">
                        Ulasan Pembeli
                        <span class="ml-1 rounded-full px-1.5 py-0.5 text-[9px]" :class="tab === 'ulasan' ? 'bg-white/20' : 'bg-[var(--surface-2)]'">{{ $reviewCount }}</span>
                    </button>
                </div>
            </div>

            <div class="mt-7">
                {{-- Description --}}
                <div x-show="tab === 'deskripsi'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
                    <div class="rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-6 sm:p-8">
                        <p class="text-[13px] leading-7 text-[var(--text-muted)] sm:text-sm">
                            {{ $product->description ?: 'Deskripsi detail produk belum tersedia.' }}
                        </p>

                        <dl class="mt-6 grid gap-4 border-t border-[var(--border-light)] pt-5 sm:grid-cols-2">
                            <div>
                                <dt class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Kategori</dt>
                                <dd class="mt-1 text-xs font-bold text-[var(--text)]">{{ $product->category?->name ?? 'Busana wanita' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Total varian</dt>
                                <dd class="mt-1 text-xs font-bold text-[var(--text)]">{{ $product->variants->count() }} pilihan warna &amp; ukuran</dd>
                            </div>
                            <div>
                                <dt class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Kode produk</dt>
                                <dd class="mt-1 text-xs font-bold text-[var(--text)]" x-text="selectedVariant?.sku || '—'">—</dd>
                            </div>
                            <div>
                                <dt class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Ketersediaan</dt>
                                <dd class="mt-1 text-xs font-bold" :class="available ? 'text-[var(--success)]' : 'text-[var(--error)]'" x-text="available ? 'Siap dikirim' : 'Menunggu restock'"></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="space-y-3">
                        @foreach ([
                            ['Bahan pilihan', 'Satin, linen, dan katun premium yang sejuk untuk iklim tropis.'],
                            ['Detail yang rapi', 'Jahitan ganda dan QC berlapis agar tahan dipakai bertahun-tahun.'],
                            ['Perawatan mudah', 'Cuci lembut dengan air dingin, jangan gunakan pemutih.'],
                        ] as $detail)
                            <div class="rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-4">
                                <div class="flex items-start gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-[11px] font-bold text-[var(--text)]">{{ $detail[0] }}</p>
                                        <p class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">{{ $detail[1] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Reviews --}}
                <div x-show="tab === 'ulasan'" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @if ($reviewCount > 0)
                        <div class="grid gap-6 lg:grid-cols-[0.72fr_1.28fr]">
                            <div class="rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-6 lg:sticky lg:top-24 lg:self-start">
                                <div class="flex items-end gap-3">
                                    <p class="font-display text-5xl font-semibold leading-none text-[var(--brand)]">
                                        {{ number_format($averageRating, 1, ',', '.') }}
                                    </p>
                                    <div class="pb-1">
                                        <div class="flex items-center gap-0.5 text-[var(--accent)]" aria-hidden="true">
                                            @for ($star = 1; $star <= 5; $star++)
                                                <svg class="h-3 w-3 {{ $star <= round($averageRating) ? 'fill-current' : 'fill-[var(--border)]' }}" viewBox="0 0 20 20">
                                                    <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="mt-1 text-[10px] text-[var(--text-subtle)]">dari {{ $reviewCount }} ulasan</p>
                                    </div>
                                </div>

                                <div class="mt-5 space-y-2">
                                    @foreach ($ratingBreakdown as $rating)
                                        <div class="flex items-center gap-2">
                                            <span class="w-8 text-[9px] font-bold text-[var(--text-muted)]">{{ $rating['star'] }} bintang</span>
                                            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-[var(--surface-2)]">
                                                <div class="h-full rounded-full bg-[var(--accent)] transition-all duration-500" style="width: {{ $rating['percent'] }}%"></div>
                                            </div>
                                            <span class="w-5 text-right text-[9px] tabular-nums text-[var(--text-subtle)]">{{ $rating['count'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach ($reviews as $rev)
                                    <article class="rounded-2xl border border-[var(--border)] bg-[var(--surface)] p-5">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-center gap-2.5">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[11px] font-bold text-[var(--brand)]">
                                                    {{ mb_strtoupper(mb_substr($rev->user?->name ?? 'Pelanggan', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-[11px] font-bold text-[var(--text)]">{{ $rev->user?->name ?? 'Pelanggan' }}</p>
                                                    <p class="mt-0.5 flex items-center gap-1.5 text-[9px] text-[var(--text-subtle)]">
                                                        <svg class="h-2.5 w-2.5 text-[var(--success)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Pembeli terverifikasi · {{ $rev->created_at?->translatedFormat('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-0.5 text-[var(--accent)]" role="img" aria-label="{{ $rev->rating }} dari 5 bintang">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="h-3 w-3 {{ $i <= $rev->rating ? 'fill-current' : 'fill-[var(--border)]' }}" viewBox="0 0 20 20" aria-hidden="true">
                                                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if ($rev->comment)
                                            <p class="mt-3 text-[11px] leading-6 text-[var(--text-muted)] sm:text-xs">{{ $rev->comment }}</p>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-14 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m8-2a9 9 0 1 1-4.5-7.79L21 3l-1.2 5.21A8.96 8.96 0 0 1 21 12Z" />
                                </svg>
                            </div>
                            <p class="mt-4 font-display text-xl font-semibold text-[var(--text)]">Belum ada ulasan</p>
                            <p class="mx-auto mt-1.5 max-w-sm text-[11px] leading-5 text-[var(--text-muted)]">
                                Jadilah yang pertama membagikan pengalamanmu memakai koleksi ini.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Related --}}
        @if ($relatedProducts->isNotEmpty())
            <section class="border-t border-[var(--border)] pt-10">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="section-kicker">Lengkapi lemari</p>
                        <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                            Produk Serupa
                        </h2>
                        <p class="mt-2 text-xs text-[var(--text-muted)]">Potongan lain yang mungkin kamu sukai.</p>
                    </div>
                    <a href="{{ $catalogUrl }}" class="group inline-flex items-center gap-2 text-[11px] font-bold text-[var(--brand)]">
                        Lihat semua koleksi
                        <span class="flex h-7 w-7 items-center justify-center rounded-full border border-[var(--brand)]/25 transition-all duration-200 group-hover:border-[var(--brand)] group-hover:bg-[var(--brand)] group-hover:text-white">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/></svg>
                        </span>
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($relatedProducts as $rel)
                        <div class="reveal" style="--reveal-delay: {{ $loop->index * 55 }}ms">
                            <x-product-card
                                :product="$rel"
                                :wishlisted="in_array($rel->id, $wishlistProductIds, true)"
                                :productUrl="$productUrl($rel)"
                            />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productDetail', (config) => ({
                variants: config.variants || [],
                basePrice: Number(config.basePrice) || 0,
                selectedColor: null,
                selectedSize: null,
                quantity: 1,
                activeImage: 0,
                wishlisted: Boolean(config.wishlisted),
                images: config.images || [],
                adding: false,
                added: false,

                init() {
                    const initial = this.variants.find((v) => v.id === config.initialVariantId)
                        ?? this.variants.find((v) => v.stock > 0)
                        ?? this.variants[0];

                    if (initial) {
                        this.selectedColor = initial.color;
                        this.selectedSize = initial.size;
                    }
                },

                get selectedVariant() {
                    return this.variants.find((v) =>
                        v.color === this.selectedColor && v.size === this.selectedSize
                    ) ?? null;
                },

                get stock() {
                    return this.selectedVariant ? Number(this.selectedVariant.stock) : 0;
                },

                get price() {
                    return this.selectedVariant ? Number(this.selectedVariant.final_price) : this.basePrice;
                },

                get available() {
                    return Boolean(this.selectedVariant) && this.stock > 0;
                },

                formatPrice(val) {
                    return (Math.round(Number(val) || 0)).toLocaleString('id-ID');
                },

                selectColor(color) {
                    this.selectedColor = color;
                    const combo = this.variants.find(v => v.color === color && v.size === this.selectedSize && v.stock > 0);
                    if (!combo) {
                        const fallback = this.variants.find(v => v.color === color && v.stock > 0);
                        if (fallback) this.selectedSize = fallback.size;
                    }
                    this.clampQuantity();
                },

                selectSize(size) {
                    this.selectedSize = size;
                    const combo = this.variants.find(v => v.size === size && v.color === this.selectedColor && v.stock > 0);
                    if (!combo) {
                        const fallback = this.variants.find(v => v.size === size && v.stock > 0);
                        if (fallback) this.selectedColor = fallback.color;
                    }
                    this.clampQuantity();
                },

                clampQuantity() {
                    const max = Math.max(1, this.stock);
                    this.quantity = Math.max(1, Math.min(max, parseInt(this.quantity, 10) || 1));
                },

                increment() {
                    this.quantity = Math.min(Math.max(1, this.stock), this.quantity + 1);
                },

                decrement() {
                    this.quantity = Math.max(1, this.quantity - 1);
                },

                addToCart() {
                    if (!this.available || this.adding) return;
                    this.adding = true;
                    this.$dispatch('add-to-cart', {
                        variantId: this.selectedVariant.id,
                        quantity: this.quantity,
                    });
                    setTimeout(() => {
                        this.adding = false;
                        this.added = true;
                        setTimeout(() => { this.added = false; }, 1600);
                    }, 550);
                },

                shareProduct() {
                    const url = window.location.href;
                    const done = () => {
                        this.$dispatch('toast', {
                            type: 'success',
                            title: 'Link disalin',
                            message: 'Tautan produk berhasil disalin ke clipboard.'
                        });
                    };

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(url).then(done).catch(() => this.fallbackCopy(url, done));
                        return;
                    }

                    this.fallbackCopy(url, done);
                },

                fallbackCopy(text, done) {
                    const field = document.createElement('textarea');
                    field.value = text;
                    field.setAttribute('readonly', '');
                    field.style.position = 'absolute';
                    field.style.left = '-9999px';
                    document.body.appendChild(field);
                    field.select();
                    try { document.execCommand('copy'); } catch (error) {}
                    document.body.removeChild(field);
                    done();
                },
            }));
        });
    </script>
@endpush
