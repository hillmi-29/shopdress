{{-- Mini cart drawer, slides in from the right --}}
<div x-data="miniCartDrawer()"
     @open-mini-cart.window="openDrawer()"
     @cart-updated.window="fetchCart()"
     @keydown.escape.window="$store.shop.miniCartOpen = false"
     x-effect="document.body.classList.toggle('overflow-hidden', $store.shop.miniCartOpen)"
     x-cloak>

    {{-- Dark backdrop --}}
    <div x-show="$store.shop.miniCartOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.shop.miniCartOpen = false"
         class="fixed inset-0 z-50 bg-[#1b1424]/55 backdrop-blur-[2px]"
         aria-hidden="true"></div>

    {{-- Drawer --}}
    <aside x-show="$store.shop.miniCartOpen"
           x-transition:enter="transform transition ease-out duration-300"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transform transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full"
           class="fixed inset-y-0 right-0 z-50 flex w-full max-w-[26rem] flex-col bg-[var(--surface)] shadow-[0_0_60px_rgba(24,16,35,0.28)]"
           role="dialog"
           aria-modal="true"
           aria-labelledby="mini-cart-title">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 border-b border-[var(--border-light)] px-5 py-5 sm:px-6">
            <div>
                <p class="section-kicker">Pilihanmu</p>
                <h2 id="mini-cart-title" class="mt-1 font-display text-[1.6rem] font-semibold text-[var(--text)]">
                    Keranjang Belanja
                </h2>
                <p class="mt-1 text-[11px] font-medium text-[var(--text-muted)]">
                    <span x-text="$store.shop.cartCount"></span> item dipilih
                </p>
            </div>
            <button type="button"
                    @click="$store.shop.miniCartOpen = false"
                    class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-[var(--border)] text-[var(--text-muted)] transition-colors duration-200 hover:border-[var(--text)] hover:bg-[var(--surface-2)] hover:text-[var(--text)]"
                    aria-label="Tutup keranjang">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        {{-- Items --}}
        <div class="flex-1 overflow-y-auto overscroll-contain px-5 py-4 sm:px-6">
            {{-- Loading skeleton --}}
            <div x-show="loading" class="space-y-3" aria-hidden="true">
                <template x-for="i in 3" :key="i">
                    <div class="flex gap-3.5 rounded-2xl border border-[var(--border-light)] p-3">
                        <div class="skeleton-shimmer h-[6.25rem] w-20 shrink-0 rounded-xl"></div>
                        <div class="flex-1 space-y-2.5 py-1">
                            <div class="skeleton-shimmer h-3 w-4/5 rounded-full"></div>
                            <div class="skeleton-shimmer h-2.5 w-1/2 rounded-full"></div>
                            <div class="skeleton-shimmer mt-6 h-7 w-24 rounded-lg"></div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Empty --}}
            <template x-if="!loading && items.length === 0">
                <div class="flex h-full flex-col items-center justify-center py-14 text-center">
                    <div class="relative flex h-24 w-24 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
                        </svg>
                        <span class="absolute -right-1 top-1 flex h-7 w-7 items-center justify-center rounded-full bg-[var(--accent-soft)] text-[var(--accent)] ring-4 ring-[var(--surface)]">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                    </div>
                    <p class="mt-5 font-display text-xl font-semibold text-[var(--text)]">Keranjang masih kosong</p>
                    <p class="mt-1.5 max-w-[16rem] text-xs leading-5 text-[var(--text-muted)]">
                        Temukan potongan favoritmu dan mulai lengkapi lemari dengan koleksi pilihan kami.
                    </p>
                    <a href="{{ $catalogUrl }}"
                       @click="$store.shop.miniCartOpen = false"
                       class="mt-5 inline-flex items-center gap-2 rounded-full bg-[var(--brand)] px-5 py-2.5 text-[11px] font-bold text-white transition-colors duration-200 hover:bg-[var(--brand-dark)]">
                        Jelajahi Produk
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </template>

            {{-- Item list --}}
            <div x-show="!loading && items.length > 0" class="space-y-3">
                <template x-for="item in items" :key="item.id">
                    <article class="group flex gap-3.5 rounded-2xl border border-[var(--border-light)] bg-[var(--surface)] p-3 transition-colors duration-200 hover:border-[var(--border)]">
                        <div class="h-[6.25rem] w-20 shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                            <img :src="item.image_url" :alt="item.product_name" class="h-full w-full object-cover">
                        </div>

                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="line-clamp-2 text-[13px] font-semibold leading-snug text-[var(--text)]" x-text="item.product_name"></h3>
                                <button type="button"
                                        @click="deleteItem(item.id)"
                                        class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[var(--text-subtle)] transition-colors hover:bg-[var(--error-soft)] hover:text-[var(--error)]"
                                        aria-label="Hapus item">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-.894.553L7.382 4H4a1 1 0 0 0 0 2v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a1 1 0 1 0-2 0v10H6V6h8V4h-3.382l-.724-1.447A1 1 0 0 0 9 2ZM7 8a1 1 0 0 1 2 0v6a1 1 0 1 1-2 0V8Zm5-1a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-1 flex flex-wrap items-center gap-x-1.5 gap-y-0.5 text-[10px] font-medium text-[var(--text-muted)]">
                                <span class="rounded-full bg-[var(--surface-2)] px-1.5 py-0.5" x-text="item.color || '-'"></span>
                                <span class="rounded-full bg-[var(--surface-2)] px-1.5 py-0.5" x-text="'Size ' + (item.size || '-')"></span>
                            </p>

                            <div class="mt-auto flex items-end justify-between gap-2 pt-2">
                                <div class="inline-flex h-7 items-center rounded-full border border-[var(--border)]">
                                    <button type="button"
                                            @click="updateItemQty(item.id, Math.max(1, item.quantity - 1))"
                                            :disabled="item.quantity <= 1"
                                            class="flex h-full w-7 items-center justify-center rounded-l-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                            aria-label="Kurangi jumlah">
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd"/></svg>
                                    </button>
                                    <span class="w-6 text-center text-[11px] font-bold text-[var(--text)]" x-text="item.quantity"></span>
                                    <button type="button"
                                            @click="updateItemQty(item.id, item.quantity + 1)"
                                            class="flex h-full w-7 items-center justify-center rounded-r-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)]"
                                            aria-label="Tambah jumlah">
                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 4a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 10 4Z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>

                                <p class="text-xs font-bold text-[var(--brand-dark)]">
                                    Rp <span x-text="formatNumber(item.price * item.quantity)"></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-[var(--border-light)] bg-[var(--surface-2)]/45 px-5 pb-6 pt-5 sm:px-6">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-[var(--text-subtle)]">Subtotal</p>
                    <p class="mt-1 text-[11px] text-[var(--text-muted)]">Ongkir dihitung saat checkout</p>
                </div>
                <p class="font-display text-2xl font-semibold text-[var(--text)]">
                    Rp <span x-text="formatNumber(subtotal)"></span>
                </p>
            </div>

            <div x-show="items.length > 0" class="mt-5 space-y-2.5">
                <a href="{{ route('checkout.index', ['mode' => 'cart']) }}"
                   @click="$store.shop.miniCartOpen = false"
                   class="flex w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 py-3.5 text-xs font-bold text-white shadow-[0_10px_24px_-14px_rgba(124,58,237,0.8)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] hover:shadow-[0_14px_28px_-14px_rgba(124,58,237,0.9)]">
                    Checkout
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="{{ $cartUrl }}"
                   @click="$store.shop.miniCartOpen = false"
                   class="flex w-full items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-5 py-3 text-xs font-bold text-[var(--text)] transition-colors duration-200 hover:border-[var(--text)] hover:bg-[var(--surface)]">
                    Lihat Keranjang
                </a>
            </div>

            <p class="mt-4 flex items-center justify-center gap-1.5 text-[10px] text-[var(--text-subtle)]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                Pembayaran aman & terenkripsi
            </p>
        </div>
    </aside>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('miniCartDrawer', () => ({
            items: [],
            subtotal: 0,
            loading: true,

            init() {
                this.fetchCart();
            },

            openDrawer() {
                this.$store.shop.miniCartOpen = true;
                this.fetchCart();
            },

            formatNumber(num) {
                return (Number(num) || 0).toLocaleString('id-ID');
            },

            fetchCart() {
                if (!this.items.length) {
                    this.loading = true;
                }

                fetch('{{ url("api/cart") }}', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                .then(r => r.json())
                .then(data => {
                    if (data && data.data && data.data.cart) {
                        const rawItems = data.data.cart.items || [];
                        this.items = rawItems.map(it => {
                            const variant = it.variant || {};
                            const prod = variant.product || {};
                            let img = prod.primary_image ? prod.primary_image.image_path : '';
                            if (img && !img.startsWith('http')) {
                                img = '{{ asset("storage") }}/' + img.replace(/^\//, '');
                            }
                            if (!img) {
                                img = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="100" fill="%23f4edff"><rect width="80" height="100"/><text x="40" y="55" font-size="11" fill="%237c3aed" text-anchor="middle">' + @json(\App\Models\SiteSetting::storeName()) + '</text></svg>';
                            }
                            const price = Number(variant.additional_price || 0) + Number(prod.price || 0);
                            return {
                                id: it.id,
                                product_name: prod.name || 'Produk',
                                color: variant.color || '-',
                                size: variant.size || '-',
                                quantity: Number(it.quantity) || 1,
                                price: price,
                                image_url: img
                            };
                        });
                        this.subtotal = data.data.total_amount || this.items.reduce((acc, i) => acc + (i.price * i.quantity), 0);
                        this.$store.shop.cartCount = data.data.total_quantity || this.items.reduce((acc, i) => acc + i.quantity, 0);
                    }
                })
                .catch(() => {})
                .finally(() => {
                    this.loading = false;
                });
            },

            updateItemQty(id, qty) {
                fetch(`{{ url("api/cart") }}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ quantity: qty })
                })
                .then(r => r.json())
                .then(() => {
                    this.fetchCart();
                });
            },

            deleteItem(id) {
                fetch(`{{ url("api/cart") }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin'
                })
                .then(r => r.json())
                .then(() => {
                    this.fetchCart();
                });
            }
        }));
    });
</script>
