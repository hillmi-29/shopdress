@extends('frontend.layouts.app')

@section('title', 'Pembayaran Pesanan #'.$order->order_number.' — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Selesaikan pembayaran pesanan '.\App\Models\SiteSetting::storeName().' Anda melalui Midtrans yang aman.')

@section('content')
    @php
        $ordersUrl = route('orders.index');
        $orderUrl = route('orders.show', $order->id);
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $snapReady = ! empty($snapToken);
    @endphp

    <div class="mx-auto max-w-2xl" x-data="paymentHandler()">
        <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ $ordersUrl }}" class="transition hover:text-[var(--brand)]">Pesanan</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">Pembayaran</span>
        </nav>

        <section class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-8 sm:px-9 sm:py-10">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/4 h-40 w-40 rounded-full bg-[var(--accent-soft)]/80 blur-2xl"></div>

            <div class="relative text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </div>

                <p class="section-kicker mt-5">Langkah terakhir</p>
                <h1 class="mt-2 font-display text-[2.2rem] font-semibold leading-none tracking-[-0.04em] text-[var(--brand)] sm:text-4xl">
                    Selesaikan pembayaran
                </h1>
                <p class="mx-auto mt-3 max-w-sm text-[11px] leading-6 text-[var(--text-muted)]">
                    Pesanan <span class="font-mono font-bold text-[var(--text)]">{{ $order->order_number }}</span> sudah siap. Pilih metode pembayaran favoritmu di halaman Midtrans.
                </p>
            </div>

            {{-- Amount --}}
            <div class="relative mt-6 overflow-hidden rounded-[1.35rem] bg-[var(--brand-deep)] px-5 py-5 text-white sm:px-6">
                <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[var(--brand)]/35 blur-2xl"></div>
                <div class="relative flex items-end justify-between gap-4">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/45">Total yang harus dibayar</p>
                        <p class="mt-1.5 font-display text-[2rem] font-semibold leading-none tracking-[-0.03em] sm:text-[2.4rem]">
                            Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}
                        </p>
                        @if ((float) $order->discount_amount > 0)
                            <p class="mt-1.5 text-[9px] text-white/50">Termasuk hemat Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</p>
                        @else
                            <p class="mt-1.5 text-[9px] text-white/50">Termasuk ongkir Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-white/70">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Summary --}}
            <div class="mt-5 space-y-2.5 rounded-[1.25rem] border border-[var(--border)] bg-[var(--bg)] p-4 text-[10px]">
                <div class="flex items-center justify-between">
                    <span class="text-[var(--text-muted)]">Subtotal produk</span>
                    <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[var(--text-muted)]">Ongkos kirim</span>
                    <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @if ((float) $order->discount_amount > 0)
                    <div class="flex items-center justify-between font-bold text-[var(--success)]">
                        <span>Diskon</span>
                        <span>− Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between border-t border-[var(--border)] pt-2.5">
                    <span class="font-bold text-[var(--text)]">Total</span>
                    <span class="font-display text-lg font-semibold text-[var(--brand-dark)]">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if ($snapReady)
                <button type="button"
                        id="pay-button"
                        @click="openSnap()"
                        class="group mt-5 flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)] active:scale-[0.99]">
                    Buka Pembayaran Midtrans
                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <p class="mt-2.5 text-center text-[9px] text-[var(--text-subtle)]">Jendela pembayaran akan terbuka otomatis. Jika tidak, tekan tombol di atas.</p>
                <a href="{{ $orderUrl }}" class="mt-3 flex h-10 w-full items-center justify-center rounded-full border border-[var(--border)] px-5 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                    Lihat rincian pesanan
                </a>
            @else
                <div class="mt-5 rounded-[1.25rem] border border-[var(--accent)]/25 bg-[var(--accent-soft)] p-4 text-center">
                    <p class="text-[11px] font-bold text-[#7a5a1f]">Pembayaran belum siap</p>
                    <p class="mt-1 text-[10px] leading-5 text-[#7a5a1f]/80">Token pembayaran tidak tersedia. Coba muat ulang halaman atau hubungi customer care.</p>
                </div>
                <a href="{{ $orderUrl }}" class="mt-4 flex h-11 w-full items-center justify-center rounded-full bg-[var(--brand)] px-5 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                    Lihat status pesanan
                </a>
            @endif

            <div class="mt-5 flex flex-col items-center gap-2 border-t border-[var(--border-light)] pt-4 text-center">
                <p class="flex items-center gap-1.5 text-[9px] font-medium text-[var(--text-subtle)]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Transaksi diproses aman oleh Midtrans
                </p>
                <a href="{{ $catalogUrl }}" class="text-[9px] font-bold text-[var(--text-muted)] underline-offset-4 transition hover:text-[var(--brand)] hover:underline">Kembali ke katalog</a>
            </div>
        </section>
    </div>

    @php
        $isProduction = config('midtrans.is_production', false);
        $clientKey = config('midtrans.client_key');
        $snapUrl = $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

    @push('scripts')
        <script>
            function paymentHandler() {
                return {
                    token: @js($snapToken),
                    init() {
                        if (this.token) {
                            setTimeout(() => this.openSnap(), 600);
                        }
                    },
                    openSnap() {
                        if (!this.token || !window.snap) {
                            alert('Token pembayaran belum siap. Silakan muat ulang halaman.');
                            return;
                        }
                        window.snap.pay(this.token, {
                            onSuccess: () => {
                                window.location.href = "{{ route('checkout.success', $order->id) }}";
                            },
                            onPending: () => {
                                window.location.href = "{{ route('orders.show', $order->id) }}";
                            },
                            onError: () => {
                                alert('Pembayaran gagal atau dibatalkan.');
                            },
                            onClose: () => {
                                console.log('Customer menutup popup Snap.');
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
@endsection
