@php
    $cartApiUrl = url('api/cart');
    $wishlistApiUrl = url('api/wishlist/toggle');
@endphp

{{-- Toast notifications (top-center) --}}
<div x-data="customerActions()"
     @add-to-cart.window="addToCart($event.detail)"
     @wishlist-toggle.window="toggleWishlist($event.detail)"
     @toast.window="notify($event.detail.type || 'success', $event.detail.title || 'Info', $event.detail.message || '')"
     class="pointer-events-none fixed inset-x-0 top-20 z-[60] flex flex-col items-center gap-2.5 px-4 sm:px-0"
     aria-live="polite">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-3 scale-[0.97]"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             :class="toast.type === 'error' ? 'border-[var(--error)]/20' : 'border-[var(--success)]/20'"
             class="toast-top-enter pointer-events-auto flex w-full max-w-md items-center gap-3 rounded-2xl border bg-[var(--surface)] px-4 py-3 shadow-[0_20px_50px_-24px_rgba(36,29,46,0.45)] sm:w-auto sm:min-w-80"
             role="status">
            <span :class="toast.type === 'error' ? 'bg-[var(--error-soft)] text-[var(--error)]' : 'bg-[var(--success-soft)] text-[var(--success)]'"
                  class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full">
                <svg x-show="toast.type === 'error'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <svg x-show="toast.type !== 'error'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </span>

            <div class="min-w-0 flex-1 pr-1">
                <p class="text-xs font-bold text-[var(--text)]" x-text="toast.title"></p>
                <p class="mt-0.5 text-xs leading-5 text-[var(--text-muted)]" x-text="toast.message"></p>
            </div>

            <button type="button"
                    @click="dismiss(toast.id)"
                    class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] focus:outline-none"
                    aria-label="Tutup notifikasi">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('shop', {
            cartCount: {{ $cartCount }},
            wishlistCount: {{ $wishlistCount }},
            miniCartOpen: false,
            cartBouncing: false,
        });

        Alpine.data('customerActions', () => ({
            toasts: [],
            nextId: 0,

            addToCart(detail) {
                const variantId = detail ? detail.variantId : null;
                const quantity = Math.max(1, parseInt(detail ? detail.quantity : 1, 10) || 1);

                if (!variantId) {
                    this.notify('error', 'Keranjang', 'Pilih varian produk terlebih dahulu.');
                    return;
                }

                const formData = new URLSearchParams();
                formData.append('product_variant_id', variantId);
                formData.append('quantity', quantity);

                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

                fetch('{{ $cartApiUrl }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    credentials: 'same-origin',
                    body: formData.toString()
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal menambahkan produk.');
                    }
                    return data;
                })
                .then((data) => {
                    const store = Alpine.store('shop');
                    store.cartCount += quantity;
                    store.cartBouncing = true;
                    setTimeout(() => { store.cartBouncing = false; }, 400);

                    this.notify('success', 'Berhasil Ditambahkan',
                        data.message || 'Produk telah ditambahkan ke keranjang belanja.');

                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    setTimeout(() => {
                        store.miniCartOpen = true;
                    }, 180);
                })
                .catch((error) => {
                    this.notify('error', 'Keranjang', error.message || 'Tidak dapat menambahkan produk.');
                });
            },

            toggleWishlist(detail) {
                const productId = detail ? detail.productId : null;

                if (!productId) {
                    return;
                }

                const formData = new URLSearchParams();
                formData.append('product_id', productId);
                const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

                fetch('{{ $wishlistApiUrl }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    credentials: 'same-origin',
                    body: formData.toString()
                })
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok) {
                        if (res.status === 401) {
                            throw new Error('Silakan masuk untuk menyimpan wishlist.');
                        }
                        throw new Error(data.message || 'Gagal memperbarui wishlist.');
                    }
                    return data;
                })
                .then((data) => {
                    const action = data.action || 'added';
                    const store = Alpine.store('shop');

                    store.wishlistCount = Math.max(0, store.wishlistCount + (action === 'added' ? 1 : -1));

                    window.dispatchEvent(new CustomEvent('wishlist-changed', {
                        detail: { productId, action },
                    }));

                    this.notify('success', 'Wishlist', data.message || 'Wishlist berhasil diperbarui.');
                })
                .catch((err) => {
                    this.notify('error', 'Wishlist', err.message);
                });
            },

            notify(type, title, message) {
                const id = ++this.nextId;
                this.toasts.push({ id, type, title, message });
                setTimeout(() => this.dismiss(id), 4200);
            },

            dismiss(id) {
                this.toasts = this.toasts.filter((t) => t.id !== id);
            },
        }));
    });
</script>
