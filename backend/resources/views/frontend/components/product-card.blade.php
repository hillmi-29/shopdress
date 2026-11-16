@props([
    'product',
    'wishlisted' => false,
    'productUrl' => null,
    'wishlistUrl' => null,
    'cartUrl' => null,
])

@php
    $productUrl = $productUrl ?: (\Illuminate\Support\Facades\Route::has('products.show')
        ? route('products.show', $product)
        : url('/produk/'.$product->slug));

    $imagePath = $product->primaryImage?->image_path;
    $imageUrl = null;

    if ($imagePath) {
        $imageUrl = str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')
            ? $imagePath
            : asset('storage/'.ltrim($imagePath, '/'));
    }

    // Default variant: first with stock > 0, fallback to first
    $defaultVariant = $product->variants->firstWhere(fn ($v) => $v->stock > 0)
        ?? $product->variants->first();

    $categoryName = $product->category?->name ?? 'Dress Modern';
    $formattedPrice = number_format((float) $product->price, 0, ',', '.');
    $totalStock = $product->variants->sum('stock');
    $isAvailable = $defaultVariant && $totalStock > 0;
@endphp

<article x-data="{
            wishlisted: @js($wishlisted),
            adding: false,
            added: false,
            async addToCart() {
                if (!@js($isAvailable) || this.adding || this.added) return;
                this.adding = true;
                try {
                    this.$dispatch('add-to-cart', {
                        variantId: {{ $defaultVariant?->id ?? 'null' }},
                        quantity: 1
                    });
                    this.added = true;
                    setTimeout(() => { this.added = false; }, 1800);
                } finally {
                    this.adding = false;
                }
            }
         }"
         @wishlist-changed.window="if ($event.detail.productId === {{ $product->id }}) wishlisted = ($event.detail.action === 'added')"
         class="product-card group relative flex h-full flex-col overflow-hidden rounded-[1.4rem] border border-[var(--border)] bg-[var(--surface)] p-2.5 shadow-[0_1px_0_rgba(36,29,46,0.02)] sm:p-3">

    {{-- Media --}}
    <div class="product-media relative aspect-[3/4] w-full overflow-hidden rounded-[1.05rem] bg-[var(--surface-2)]">
        <a href="{{ $productUrl }}"
           class="block h-full w-full focus:outline-none"
           aria-label="Lihat {{ $product->name }}">
            @if ($imageUrl)
                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     loading="lazy"
                     class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[var(--brand-soft)] via-white to-[var(--surface-2)] text-[var(--brand)]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1" class="h-14 w-14 opacity-35" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z" />
                    </svg>
                </div>
            @endif
        </a>

        {{-- Soft media gradient for badge legibility --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#241d2e]/15 to-transparent opacity-70"></div>

        {{-- Badges --}}
        <div class="absolute left-2.5 top-2.5 flex flex-col items-start gap-1">
            @if ($product->featured)
                <span class="inline-flex items-center gap-1 rounded-full bg-[var(--surface)]/95 px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.1em] text-[var(--brand)] shadow-sm backdrop-blur">
                    <svg class="h-2.5 w-2.5 fill-[var(--accent)] text-[var(--accent)]" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                    </svg>
                    Unggulan
                </span>
            @endif

            @if ($totalStock > 0 && $totalStock <= 3)
                <span class="inline-flex items-center rounded-full bg-[var(--accent)] px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.08em] text-white shadow-sm">
                    Sisa {{ $totalStock }}
                </span>
            @elseif (! $isAvailable)
                <span class="inline-flex items-center rounded-full bg-[var(--text)] px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.08em] text-white shadow-sm">
                    Habis
                </span>
            @endif
        </div>

        {{-- Wishlist --}}
        <button type="button"
                @click.prevent="$dispatch('wishlist-toggle', { productId: {{ $product->id }} })"
                class="absolute right-2.5 top-2.5 flex h-8 w-8 items-center justify-center rounded-full bg-[var(--surface)]/95 shadow-sm backdrop-blur transition-all duration-200 ease-out hover:scale-105 hover:bg-white"
                :class="wishlisted ? 'text-[#d1456b]' : 'text-[var(--text-muted)] hover:text-[#d1456b]'"
                :aria-label="wishlisted ? 'Hapus dari wishlist' : 'Simpan ke wishlist'"
                :aria-pressed="wishlisted.toString()">
            <svg class="h-[15px] w-[15px] transition-transform duration-200"
                 :class="wishlisted ? 'scale-110' : 'scale-100'"
                 viewBox="0 0 24 24"
                 :fill="wishlisted ? 'currentColor' : 'none'"
                 stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="flex flex-1 flex-col px-1 pb-0.5 pt-3">
        <p class="text-[9px] font-bold uppercase tracking-[0.14em] text-[var(--text-subtle)]">
            {{ $categoryName }}
        </p>

        <h3 class="mt-1.5 line-clamp-2 font-display text-[15px] font-semibold leading-snug text-[var(--text)] transition-colors duration-200 group-hover:text-[var(--brand)] sm:text-base">
            <a href="{{ $productUrl }}" class="focus:outline-none">
                {{ $product->name }}
            </a>
        </h3>

        <div class="mt-auto flex items-end justify-between gap-2 pt-3">
            <div>
                <p class="text-[9px] font-medium uppercase tracking-[0.08em] text-[var(--text-subtle)]">Mulai dari</p>
                <p class="mt-0.5 text-sm font-extrabold tracking-tight text-[var(--brand-dark)] sm:text-[15px]">
                    Rp {{ $formattedPrice }}
                </p>
            </div>

            @if ($isAvailable)
                <button type="button"
                        @click="addToCart"
                        :disabled="adding"
                        class="card-add relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-white shadow-[0_8px_18px_-10px_rgba(124,58,237,0.9)] focus:outline-none active:scale-95"
                        :class="added ? 'bg-[var(--success)]' : 'bg-[var(--brand)] hover:bg-[var(--brand-dark)]'"
                        aria-label="Tambah {{ $product->name }} ke keranjang">
                    <svg x-show="!adding && !added" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                    </svg>

                    <svg x-show="adding" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>

                    <svg x-show="added" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </button>
            @else
                <button type="button"
                        disabled
                        class="flex h-9 w-9 shrink-0 cursor-not-allowed items-center justify-center rounded-full bg-[var(--surface-2)] text-[var(--text-subtle)]"
                        title="Stok habis"
                        aria-label="{{ $product->name }} stok habis">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</article>
