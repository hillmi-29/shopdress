@props([
    'product',
    'wishlisted' => false,
    'productUrl' => null,
    'wishlistUrl' => null,
    'cartUrl' => null,
    'compact' => false,
    'pickVariantOnAdd' => true,
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
    $variantCount = $product->variants->count();
    $isAvailable = $defaultVariant && $totalStock > 0;

    // Data varian untuk popup pemilih ukuran/warna di beranda.
    $variantPayload = $product->variants
        ->filter(fn ($variant) => $variant->stock > 0)
        ->map(fn ($variant) => [
            'id' => $variant->id,
            'size' => $variant->size ?: 'All Size',
            'color' => $variant->color ?: 'Standar',
            'stock' => (int) $variant->stock,
            'price' => (float) $product->price + (float) $variant->additional_price,
        ])
        ->values();
@endphp

<article x-data="{
            wishlisted: @js($wishlisted),
            adding: false,
            added: false,
            pickVariantOnAdd: @js($pickVariantOnAdd),
            @if ($pickVariantOnAdd)
            openVariantPicker() {
                this.$dispatch('open-variant-picker', {
                    productId: {{ $product->id }},
                    name: @js($product->name),
                    image: @js($imageUrl),
                    url: @js($productUrl),
                    price: {{ (float) $product->price }},
                    variants: @js($variantPayload),
                });
            },
            @endif
            async addToCart() {
                if (!@js($isAvailable) || this.adding || this.added) return;
                @guest
                    this.$dispatch('login-required', { action: 'cart' });
                    return;
                @endguest
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
         class="product-card group relative flex h-full flex-col overflow-hidden border border-[var(--border)] bg-[var(--surface)] shadow-[0_1px_0_rgba(36,29,46,0.02)] {{ $compact ? 'rounded-[1rem] p-1.5 sm:p-2' : 'rounded-[1.4rem] p-2.5 sm:p-3' }}">

    {{-- Media --}}
    <div class="product-media relative w-full overflow-hidden bg-[var(--surface-2)] {{ $compact ? 'aspect-square rounded-[0.8rem]' : 'aspect-[3/4] rounded-[1.05rem]' }}">
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
        <div class="absolute flex flex-col items-start {{ $compact ? 'left-2 top-2 gap-0.5' : 'left-2.5 top-2.5 gap-1' }}">
            @if ($product->featured)
                <span class="inline-flex items-center gap-1 rounded-full bg-[var(--surface)]/95 font-black uppercase tracking-[0.1em] text-[var(--brand)] shadow-sm backdrop-blur {{ $compact ? 'px-2 py-0.5 text-[8px]' : 'px-2.5 py-1 text-[9px]' }}">
                    <svg class="fill-[var(--accent)] text-[var(--accent)] {{ $compact ? 'h-2 w-2' : 'h-2.5 w-2.5' }}" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.94a1 1 0 0 0 .95.69h4.15c.97 0 1.37 1.24.59 1.81l-3.36 2.44a1 1 0 0 0-.36 1.12l1.28 3.94c.3.92-.76 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.54-1.12l1.28-3.94a1 1 0 0 0-.36-1.12L2.08 9.37c-.78-.57-.38-1.81.59-1.81h4.15a1 1 0 0 0 .95-.69l1.28-3.94Z" />
                    </svg>
                    Unggulan
                </span>
            @endif

            @if ($totalStock > 0 && $totalStock <= 3)
                <span class="inline-flex items-center rounded-full bg-[var(--accent)] font-black uppercase tracking-[0.08em] text-white shadow-sm {{ $compact ? 'px-2 py-0.5 text-[8px]' : 'px-2.5 py-1 text-[9px]' }}">
                    Sisa {{ $totalStock }}
                </span>
            @elseif (! $isAvailable)
                <span class="inline-flex items-center rounded-full bg-[var(--text)] font-black uppercase tracking-[0.08em] text-white shadow-sm {{ $compact ? 'px-2 py-0.5 text-[8px]' : 'px-2.5 py-1 text-[9px]' }}">
                    Habis
                </span>
            @endif
        </div>

        {{-- Wishlist --}}
        <button type="button"
                @click.prevent="$dispatch('wishlist-toggle', { productId: {{ $product->id }} })"
                class="absolute flex items-center justify-center rounded-full bg-[var(--surface)]/95 shadow-sm backdrop-blur transition-all duration-200 ease-out hover:scale-105 hover:bg-white {{ $compact ? 'right-2 top-2 h-6 w-6' : 'right-2.5 top-2.5 h-8 w-8' }}"
                :class="wishlisted ? 'text-[#d1456b]' : 'text-[var(--text-muted)] hover:text-[#d1456b]'"
                :aria-label="wishlisted ? 'Hapus dari wishlist' : 'Simpan ke wishlist'"
                :aria-pressed="wishlisted.toString()">
            <svg class="transition-transform duration-200 {{ $compact ? 'h-3 w-3' : 'h-[15px] w-[15px]' }}"
                 :class="wishlisted ? 'scale-110' : 'scale-100'"
                 viewBox="0 0 24 24"
                 :fill="wishlisted ? 'currentColor' : 'none'"
                 stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
            </svg>
        </button>
    </div>

    {{-- Content --}}
    <div class="flex flex-1 flex-col {{ $compact ? 'px-0.5 pb-0 pt-2' : 'px-1 pb-0.5 pt-3' }}">
        <p class="font-bold uppercase text-[var(--text-subtle)] {{ $compact ? 'text-[8px] tracking-[0.12em]' : 'text-[9px] tracking-[0.14em]' }}">
            {{ $categoryName }}
        </p>

        <h3 class="line-clamp-2 font-display font-semibold leading-snug text-[var(--text)] transition-colors duration-200 group-hover:text-[var(--brand)] {{ $compact ? 'mt-1 text-[12px] sm:text-[13px]' : 'mt-1.5 text-[15px] sm:text-base' }}">
            <a href="{{ $productUrl }}" class="focus:outline-none">
                {{ $product->name }}
            </a>
        </h3>

        <div class="mt-auto flex items-end justify-between gap-2 {{ $compact ? 'pt-2' : 'pt-3' }}">
            <div>
                @unless ($compact)
                    <p class="text-[9px] font-medium uppercase tracking-[0.08em] text-[var(--text-subtle)]">Mulai dari</p>
                @endunless
                <p class="font-extrabold tracking-tight text-[var(--brand-dark)] {{ $compact ? 'text-xs sm:text-[13px]' : 'mt-0.5 text-sm sm:text-[15px]' }}">
                    Rp {{ $formattedPrice }}
                </p>
            </div>

            @if ($isAvailable)
                <button type="button"
                        @click="pickVariantOnAdd ? openVariantPicker() : addToCart()"
                        :disabled="adding"
                        class="card-add relative flex shrink-0 items-center justify-center rounded-full text-white shadow-[0_8px_18px_-10px_rgba(124,58,237,0.9)] focus:outline-none active:scale-95 {{ $compact ? 'h-7 w-7' : 'h-9 w-9' }}"
                        :class="added ? 'bg-[var(--success)]' : 'bg-[var(--brand)] hover:bg-[var(--brand-dark)]'"
                        aria-label="Tambah {{ $product->name }} ke keranjang">
                    <svg x-show="!adding && !added" class="{{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                    </svg>

                    <svg x-show="adding" x-cloak class="animate-spin {{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>

                    <svg x-show="added" x-cloak class="{{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </button>
            @else
                <button type="button"
                        disabled
                        class="flex shrink-0 cursor-not-allowed items-center justify-center rounded-full bg-[var(--surface-2)] text-[var(--text-subtle)] {{ $compact ? 'h-7 w-7' : 'h-9 w-9' }}"
                        title="Stok habis"
                        aria-label="{{ $product->name }} stok habis">
                    <svg class="{{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        {{-- Buy now: single variant goes straight to checkout, multi variant picks size/color first. --}}
        @if ($isAvailable)
            @if ($variantCount === 1)
                <button type="button"
                        @click="$dispatch('buy-now', { variantId: {{ $defaultVariant->id }}, quantity: 1 })"
                        class="mt-2 inline-flex w-full items-center justify-center gap-1.5 rounded-full border border-[var(--brand)]/25 bg-[var(--brand-soft)] font-bold text-[var(--brand-dark)] transition-colors duration-200 hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 {{ $compact ? 'h-7 text-[9px]' : 'h-9 text-[10px]' }}"
                        aria-label="Beli {{ $product->name }} sekarang">
                    <svg class="{{ $compact ? 'h-2.5 w-2.5' : 'h-3 w-3' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                    Beli Sekarang
                </button>
            @else
                <a href="{{ $productUrl }}"
                   class="mt-2 inline-flex w-full items-center justify-center gap-1.5 rounded-full border border-[var(--brand)]/25 bg-[var(--brand-soft)] font-bold text-[var(--brand-dark)] transition-colors duration-200 hover:border-[var(--brand)] hover:bg-[var(--brand)] hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 {{ $compact ? 'h-7 text-[9px]' : 'h-9 text-[10px]' }}"
                   title="Pilih ukuran dan warna terlebih dahulu"
                   aria-label="Beli {{ $product->name }} sekarang, pilih ukuran dan warna terlebih dahulu">
                    <svg class="{{ $compact ? 'h-2.5 w-2.5' : 'h-3 w-3' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                    Beli Sekarang
                </a>
            @endif
        @else
            <button type="button"
                    disabled
                    class="mt-2 inline-flex w-full cursor-not-allowed items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface-2)] font-bold text-[var(--text-subtle)] {{ $compact ? 'h-7 text-[9px]' : 'h-9 text-[10px]' }}">
                Stok Habis
            </button>
        @endif
    </div>
</article>
