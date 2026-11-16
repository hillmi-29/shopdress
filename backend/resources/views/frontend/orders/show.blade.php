@extends('frontend.layouts.app')

@section('title', 'Detail Pesanan #'.$order->order_number.' — ShopDress')
@section('meta_description', 'Detail pesanan #'.$order->order_number.' Anda di ShopDress.')

@section('content')
    @php
        $ordersUrl = route('orders.index');
        $status = strtolower($order->status?->value ?? (string) $order->status);
        $paymentStatus = $order->payment_status;
        $paymentLabel = $paymentStatus instanceof \App\Enums\PaymentStatus
            ? $paymentStatus->label()
            : ucfirst(strtolower((string) $paymentStatus));
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $statusClasses = [
            'pending' => 'border-[var(--accent)]/25 bg-[var(--accent-soft)] text-[#7a5a1f]',
            'paid' => 'border-[var(--success)]/20 bg-[var(--success-soft)] text-[var(--success)]',
            'processing' => 'border-[var(--brand)]/20 bg-[var(--brand-soft)] text-[var(--brand-dark)]',
            'shipped' => 'border-[#c7b8f5] bg-[#f1ebff] text-[#5b3bb5]',
            'completed' => 'border-[var(--success)]/20 bg-[var(--success-soft)] text-[var(--success)]',
            'cancelled' => 'border-[var(--error)]/20 bg-[var(--error-soft)] text-[var(--error)]',
        ];
    @endphp

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

        <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ $ordersUrl }}" class="transition hover:text-[var(--brand)]">Pesanan</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">#{{ $order->order_number }}</span>
        </nav>

        <div class="relative mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-kicker">Rincian pesanan</p>
                <h1 class="mt-2 font-display text-[2.3rem] font-semibold leading-[0.98] tracking-[-0.04em] text-[var(--text)] sm:text-5xl">
                    Detail Pesanan
                </h1>
                <p class="mt-2 text-[11px] text-[var(--text-muted)]">
                    #{{ $order->order_number }} · {{ $order->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            <span class="inline-flex items-center gap-2 self-start rounded-full border px-3.5 py-2 text-[10px] font-bold sm:self-auto {{ $statusClasses[$status] ?? 'border-[var(--border)] bg-[var(--surface-2)] text-[var(--text-muted)]' }}">
                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                {{ $statusLabels[$status] ?? ucfirst((string) $status) }}
            </span>
        </div>
    </header>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[1.45fr_0.85fr]">
        <div class="space-y-5">
            {{-- Items --}}
            <section class="reveal overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
                <div class="flex items-center justify-between gap-4 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Rincian</p>
                        <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Item Pesanan</h2>
                    </div>
                    <span class="rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[10px] font-bold text-[var(--text-muted)]">{{ $order->items->sum('quantity') }} item</span>
                </div>

                <div class="divide-y divide-[var(--border-light)] px-3 sm:px-4">
                    @foreach ($order->items as $item)
                        @php
                            $product = $item->variant?->product;
                            $imagePath = $product?->primaryImage?->image_path;
                            $imageUrl = $imagePath
                                ? (str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/'.ltrim($imagePath, '/')))
                                : null;
                        @endphp
                        <div class="flex items-center gap-3.5 py-3.5">
                            <div class="h-20 w-16 shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product?->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--brand)]/40">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6l1 5-3 2 3 11H8l3-11-3-2 1-5Z" /></svg>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-display text-sm font-semibold text-[var(--text)]">{{ $product?->name }}</p>
                                <div class="mt-1.5 flex flex-wrap gap-1.5">
                                    @if ($item->variant?->color)
                                        <span class="rounded-full bg-[var(--surface-2)] px-2 py-0.5 text-[9px] font-semibold text-[var(--text-muted)]">{{ $item->variant->color }}</span>
                                    @endif
                                    @if ($item->variant?->size)
                                        <span class="rounded-full bg-[var(--surface-2)] px-2 py-0.5 text-[9px] font-semibold text-[var(--text-muted)]">Size {{ $item->variant->size }}</span>
                                    @endif
                                </div>
                                <p class="mt-1.5 text-[10px] text-[var(--text-subtle)]">Rp {{ number_format((float) $item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                            </div>

                            <p class="shrink-0 text-sm font-extrabold tracking-tight text-[var(--brand-dark)]">
                                Rp {{ number_format((float) ($item->price * $item->quantity), 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Shipping --}}
            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-6" style="--reveal-delay: 80ms">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Alamat pengiriman</p>
                        <p class="mt-1 text-xs font-bold text-[var(--text)]">{{ $order->recipient_name }}</p>
                        <p class="mt-0.5 text-[11px] text-[var(--text-muted)]">{{ $order->recipient_phone }}</p>
                        <p class="mt-1 text-[11px] leading-5 text-[var(--text-muted)]">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                @if ($order->notes)
                    <div class="mt-4 rounded-xl bg-[var(--bg)] p-3.5">
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Catatan</p>
                        <p class="mt-1 text-[11px] leading-5 text-[var(--text-muted)]">{{ $order->notes }}</p>
                    </div>
                @endif
            </section>
        </div>

        <aside class="space-y-5">
            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: 60ms">
                <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Ringkasan</h2>

                <div class="mt-4 space-y-2.5 text-[11px]">
                    <div class="flex items-center justify-between text-[var(--text-muted)]">
                        <span>Subtotal produk</span>
                        <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[var(--text-muted)]">
                        <span>Ongkos kirim</span>
                        <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if ($order->discount_amount > 0)
                        <div class="flex items-center justify-between text-[var(--success)]">
                            <span class="font-semibold">Diskon</span>
                            <span class="font-bold">− Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 flex items-end justify-between border-t border-[var(--border)] pt-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Total</p>
                        <p class="mt-0.5 text-[9px] text-[var(--text-subtle)]">Termasuk ongkir</p>
                    </div>
                    <p class="font-display text-[1.8rem] font-semibold leading-none tracking-[-0.03em] text-[var(--brand-dark)]">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</p>
                </div>

                @if ($status === 'pending')
                    <a href="{{ route('checkout.payment', $order->id) }}"
                       class="mt-5 flex h-11 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)]">
                        Bayar Pesanan
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
                    </a>
                @endif
            </section>

            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: 100ms">
                <h2 class="font-display text-base font-semibold tracking-[-0.01em] text-[var(--text)]">Pembayaran</h2>
                <dl class="mt-3 space-y-2.5 text-[11px]">
                    <div class="flex items-start justify-between gap-4">
                        <dt class="text-[var(--text-muted)]">Metode</dt>
                        <dd class="text-right font-semibold text-[var(--text)]">Midtrans<br><span class="text-[10px] font-normal text-[var(--text-subtle)]">{{ $order->payment_method ?? 'Transfer Bank / VA / E-Wallet' }}</span></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-[var(--text-muted)]">Status</dt>
                        <dd class="font-bold text-[var(--text)]">{{ $paymentLabel }}</dd>
                    </div>
                </dl>

                <a href="{{ $ordersUrl }}" class="mt-4 flex h-10 w-full items-center justify-center gap-1.5 rounded-full border border-[var(--border)] text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                    Kembali ke daftar pesanan
                </a>
            </section>
        </aside>
    </div>
@endsection
