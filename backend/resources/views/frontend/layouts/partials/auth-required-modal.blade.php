{{-- Dialog "wajib masuk": pengganti toast + redirect paksa saat tamu mencoba
     menambah ke keranjang, beli sekarang, atau menyimpan wishlist. --}}
<div x-data="authRequiredDialog()"
     x-show="open"
     x-cloak
     @login-required.window="show($event.detail)"
     @keydown.escape.window="close()"
     x-effect="document.body.classList.toggle('overflow-hidden', open)"
     class="fixed inset-0 z-[75] flex items-end justify-center px-4 pb-6 pt-20 sm:items-center sm:py-6"
     role="dialog"
     aria-modal="true"
     aria-labelledby="auth-required-title">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="absolute inset-0 bg-[#1b1424]/55 backdrop-blur-[2px]"
         aria-hidden="true"></div>

    {{-- Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-2 sm:scale-[0.97]"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-1 sm:scale-[0.98]"
         class="relative z-10 w-full max-w-md overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] shadow-[0_40px_100px_-40px_rgba(26,18,38,0.65)]">

        {{-- Header --}}
        <div class="relative overflow-hidden border-b border-[var(--border-light)] bg-gradient-to-br from-[var(--brand-soft)] via-[var(--surface)] to-[var(--surface)] px-6 pb-6 pt-7 text-center sm:px-8">
            <div class="pointer-events-none absolute -right-12 -top-16 h-40 w-40 rounded-full bg-[var(--brand)]/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-12 h-40 w-40 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

            <button type="button"
                    @click="close()"
                    class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)]/30"
                    aria-label="Tutup dialog">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>

            <span class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--brand)] text-white shadow-[0_16px_30px_-18px_rgba(124,58,237,0.9)]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 8h12l-1 12H7L6 8Z" />
                    <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    <path d="M9.5 13.5a2.5 2.5 0 0 0 5 0" />
                </svg>
            </span>

            <p class="relative mt-4 text-[9px] font-black uppercase tracking-[0.22em] text-[var(--brand)]" x-text="preset.kicker"></p>
            <h2 id="auth-required-title"
                class="relative mt-1.5 font-display text-2xl font-semibold tracking-[-0.03em] text-[var(--text)]"
                x-text="preset.title"></h2>
            <p class="relative mx-auto mt-2 max-w-xs text-[11px] leading-5 text-[var(--text-muted)]" x-text="preset.message"></p>
        </div>

        {{-- Manfaat akun --}}
        <div class="px-6 pt-5 sm:px-8">
            <ul class="space-y-2.5">
                <template x-for="benefit in benefits" :key="benefit">
                    <li class="flex items-start gap-2.5 text-[11px] font-medium leading-5 text-[var(--text-muted)]">
                        <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--success-soft)] text-[var(--success)]">
                            <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span x-text="benefit"></span>
                    </li>
                </template>
            </ul>
        </div>

        {{-- Aksi --}}
        <div class="space-y-2.5 px-6 pb-6 pt-5 sm:px-8">
            <a :href="loginHref"
               x-ref="authPrimary"
               class="flex h-11 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 active:scale-[0.99]">
                Masuk ke akun
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </a>

            <a :href="registerHref"
               class="flex h-11 w-full items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-5 text-[11px] font-bold text-[var(--text)] transition-all duration-200 ease-out hover:border-[var(--brand)]/40 hover:bg-[var(--brand-soft)]/60 hover:text-[var(--brand-dark)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)]/30">
                Daftar gratis
            </a>

            <button type="button"
                    @click="close()"
                    class="w-full pt-1 text-center text-[11px] font-semibold text-[var(--text-subtle)] transition hover:text-[var(--text-muted)] focus:outline-none">
                Nanti saja
            </button>

            <p class="pt-1 text-center text-[10px] leading-5 text-[var(--text-subtle)]">
                Setelah masuk, kamu kembali ke halaman ini.
            </p>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('authRequiredDialog', () => ({
                open: false,
                action: 'default',
                loginUrl: @js(route('login')),
                registerUrl: @js(route('register')),
                benefits: [
                    'Keranjang dan wishlist tersimpan otomatis',
                    'Status pesanan mudah dipantau kapan saja',
                    'Checkout lebih cepat di kunjungan berikutnya',
                ],
                presets: {
                    cart: {
                        kicker: 'Keranjang belanja',
                        title: 'Masuk untuk mulai belanja',
                        message: 'Simpan dress pilihanmu di keranjang dan lanjutkan checkout kapan saja.',
                    },
                    'buy-now': {
                        kicker: 'Beli sekarang',
                        title: 'Masuk untuk melanjutkan',
                        message: 'Masuk sebentar supaya pesananmu aman dan bisa dilacak sampai tiba.',
                    },
                    wishlist: {
                        kicker: 'Wishlist',
                        title: 'Simpan produk favoritmu',
                        message: 'Masuk atau daftar gratis agar wishlist tersimpan dan mudah dibuka kembali.',
                    },
                    default: {
                        kicker: 'Akun toko',
                        title: 'Masuk untuk melanjutkan',
                        message: 'Masuk atau daftar gratis untuk melanjutkan aktivitas belanjamu.',
                    },
                },

                show(payload) {
                    this.action = payload?.action || 'default';
                    this.open = true;

                    this.$nextTick(() => this.$refs.authPrimary?.focus());
                },

                close() {
                    this.open = false;
                },

                get preset() {
                    return this.presets[this.action] || this.presets.default;
                },

                get redirectPath() {
                    return encodeURIComponent(window.location.pathname + window.location.search);
                },

                get loginHref() {
                    return this.loginUrl + '?redirect=' + this.redirectPath;
                },

                get registerHref() {
                    return this.registerUrl + '?redirect=' + this.redirectPath;
                },
            }));
        });
    </script>
@endpush
