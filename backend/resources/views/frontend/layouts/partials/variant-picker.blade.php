{{-- Variant picker: dipakai card produk di beranda sebelum menambah ke keranjang. --}}
<div x-data="variantPicker()"
     @open-variant-picker.window="show($event.detail)"
     @keydown.escape.window="close()"
     x-effect="document.body.classList.toggle('overflow-hidden', open)"
     :class="open ? 'pointer-events-auto' : 'pointer-events-none'"
     class="fixed inset-0 z-[70]"
     x-cloak>

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="absolute inset-0 bg-[#1b1424]/60 backdrop-blur-[2px]"
         aria-hidden="true"></div>

    {{-- Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-2 sm:scale-[0.98]"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-2 sm:scale-[0.98]"
         class="absolute inset-x-0 bottom-0 z-10 max-h-[90vh] overflow-y-auto rounded-t-[1.75rem] bg-[var(--surface)] p-5 shadow-[0_-20px_60px_-30px_rgba(24,16,35,0.5)] sm:inset-0 sm:m-auto sm:h-fit sm:max-w-lg sm:rounded-[1.5rem] sm:p-6"
         role="dialog"
         aria-modal="true"
         aria-labelledby="variant-picker-title">

        <div class="flex items-start justify-between gap-3">
            <div class="flex min-w-0 gap-3">
                <div class="h-20 w-16 shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                    <template x-if="product?.image">
                        <img :src="product.image" :alt="product?.name" class="h-full w-full object-cover">
                    </template>
                    <template x-if="! product?.image">
                        <div class="flex h-full w-full items-center justify-center text-[var(--brand)]">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" class="h-7 w-7 opacity-40" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z" />
                            </svg>
                        </div>
                    </template>
                </div>

                <div class="min-w-0">
                    <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Pilih varian</p>
                    <h2 id="variant-picker-title"
                        class="mt-1 line-clamp-2 font-display text-lg font-semibold leading-snug tracking-[-0.02em] text-[var(--text)]"
                        x-text="product?.name"></h2>
                    <p class="mt-1 text-sm font-extrabold tracking-tight text-[var(--brand-dark)]">
                        Rp <span x-text="formatPrice(displayPrice)"></span>
                    </p>
                </div>
            </div>

            <button type="button"
                    @click="close()"
                    class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-[var(--border)] text-[var(--text-muted)] transition hover:border-[var(--text)] hover:bg-[var(--surface-2)] hover:text-[var(--text)]"
                    aria-label="Tutup pilihan varian">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- Warna --}}
        <div class="mt-5" x-show="colors.length > 1">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-bold text-[var(--text)]">Warna</p>
                <p class="text-[9px] font-medium text-[var(--text-subtle)]" x-text="selectedColor || 'Pilih warna'"></p>
            </div>
            <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="color in colors" :key="color">
                    <button type="button"
                            @click="chooseColor(color)"
                            class="rounded-full border px-3.5 py-2 text-[11px] font-semibold transition"
                            :class="selectedColor === color
                                ? 'border-[var(--brand)] bg-[var(--brand-soft)] text-[var(--brand-dark)] ring-2 ring-[var(--brand)]/15'
                                : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-muted)] hover:border-[var(--brand)]/50 hover:text-[var(--text)]'"
                            x-text="color"></button>
                </template>
            </div>
        </div>

        {{-- Ukuran --}}
        <div class="mt-4" x-show="sizes.length > 1">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-bold text-[var(--text)]">Ukuran</p>
                <p class="text-[9px] font-medium text-[var(--text-subtle)]" x-text="selectedSize || 'Pilih ukuran'"></p>
            </div>
            <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="size in sizes" :key="size">
                    <button type="button"
                            @click="chooseSize(size)"
                            class="min-w-10 rounded-xl border px-3 py-2 text-[11px] font-semibold transition"
                            :class="selectedSize === size
                                ? 'border-[var(--brand)] bg-[var(--brand-soft)] text-[var(--brand-dark)] ring-2 ring-[var(--brand)]/15'
                                : 'border-[var(--border)] bg-[var(--surface)] text-[var(--text-muted)] hover:border-[var(--brand)]/50 hover:text-[var(--text)]'"
                            x-text="size"></button>
                </template>
            </div>
        </div>

        {{-- Jumlah --}}
        <div class="mt-5 flex items-center justify-between gap-3 rounded-2xl border border-[var(--border-light)] bg-[var(--bg)]/70 px-3.5 py-3">
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-[var(--text)]">Jumlah</p>
                <p class="mt-0.5 text-[9px] text-[var(--text-subtle)]"
                   x-text="selectedVariant ? 'Stok tersisa ' + stock : 'Pilih varian dahulu'"></p>
            </div>
            <div class="inline-flex h-9 shrink-0 items-center rounded-full border border-[var(--border)] bg-[var(--surface)]">
                <button type="button"
                        @click="decrement()"
                        :disabled="quantity <= 1"
                        class="flex h-full w-9 items-center justify-center rounded-l-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Kurangi jumlah">−</button>
                <span class="w-7 text-center text-[11px] font-bold text-[var(--text)]" x-text="quantity"></span>
                <button type="button"
                        @click="increment()"
                        :disabled="! selectedVariant || quantity >= stock"
                        class="flex h-full w-9 items-center justify-center rounded-r-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Tambah jumlah">+</button>
            </div>
        </div>

        <button type="button"
                @click="addToCart()"
                :disabled="! canAdd"
                class="mt-4 inline-flex h-11 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)] disabled:cursor-not-allowed disabled:opacity-40">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
            </svg>
            Tambah ke Keranjang
        </button>

        <a :href="product?.url || '#'"
           class="mt-2.5 block text-center text-[10px] font-bold text-[var(--brand)] underline-offset-4 transition hover:underline">
            Lihat detail produk
        </a>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('variantPicker', () => ({
                open: false,
                product: null,
                variants: [],
                selectedColor: null,
                selectedSize: null,
                quantity: 1,

                show(detail) {
                    this.product = detail || {};
                    this.variants = (detail?.variants || []).filter(v => Number(v.stock) > 0);
                    this.selectedColor = null;
                    this.selectedSize = null;
                    this.quantity = 1;

                    const colors = this.colors;
                    if (colors.length === 1) {
                        this.selectedColor = colors[0];
                    }

                    const sizes = this.sizes;
                    if (sizes.length === 1) {
                        this.selectedSize = sizes[0];
                    }

                    this.open = true;
                },

                close() {
                    this.open = false;
                },

                get colors() {
                    return [...new Set(this.variants.map(v => v.color).filter(Boolean))];
                },

                get sizes() {
                    const variants = this.selectedColor
                        ? this.variants.filter(v => v.color === this.selectedColor)
                        : this.variants;

                    return [...new Set(variants.map(v => v.size).filter(Boolean))];
                },

                chooseColor(color) {
                    this.selectedColor = color;
                    this.quantity = 1;

                    const sizes = this.sizes;
                    if (sizes.length === 1) {
                        this.selectedSize = sizes[0];
                    } else if (! sizes.includes(this.selectedSize)) {
                        this.selectedSize = null;
                    }
                },

                chooseSize(size) {
                    this.selectedSize = size;
                    this.quantity = 1;
                },

                get selectedVariant() {
                    return this.variants.find(v =>
                        v.color === this.selectedColor && v.size === this.selectedSize
                    ) ?? null;
                },

                get stock() {
                    return this.selectedVariant ? Number(this.selectedVariant.stock) : 0;
                },

                get canAdd() {
                    return Boolean(this.selectedVariant) && this.quantity >= 1 && this.quantity <= this.stock;
                },

                get displayPrice() {
                    return this.selectedVariant
                        ? Number(this.selectedVariant.price)
                        : Number(this.product?.price || 0);
                },

                increment() {
                    if (this.quantity < this.stock) {
                        this.quantity++;
                    }
                },

                decrement() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },

                formatPrice(value) {
                    return new Intl.NumberFormat('id-ID').format(Number(value) || 0);
                },

                addToCart() {
                    if (! this.canAdd) {
                        return;
                    }

                    @guest
                        this.close();
                        this.$dispatch('login-required', { action: 'cart' });
                        return;
                    @endguest

                    const variantId = this.selectedVariant.id;
                    const quantity = this.quantity;

                    this.close();
                    this.$dispatch('add-to-cart', { variantId, quantity });
                },
            }));
        });
    </script>
@endpush
