@extends('frontend.layouts.app')

@section('title', 'Pesanan Saya — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Lihat riwayat dan status pesanan Anda di '.\App\Models\SiteSetting::storeName().'.')

@section('content')
    @php
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
            <span class="text-[var(--text)]" aria-current="page">Pesanan</span>
        </nav>

        <div class="relative mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Riwayat belanjamu</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--text)] sm:text-6xl">
                    Pesanan Saya
                </h1>
                <p class="mt-3 max-w-lg text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Pantau status, pembayaran, dan pengiriman setiap pesanan dalam satu tempat.
                </p>
            </div>

            <a href="{{ \App\Support\FrontendUrls::catalog() }}"
               class="inline-flex items-center gap-2 self-start rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 py-2.5 text-[10px] font-bold text-[var(--text)] transition hover:border-[var(--brand)] hover:text-[var(--brand)] sm:self-auto">
                Belanja lagi
                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
            </a>
        </div>
    </header>

    <div class="mt-6">
        @if ($orders->isNotEmpty())
            <div class="space-y-4">
                @foreach ($orders as $order)
                    @php
                        $firstItem = $order->items->first();
                        $firstProduct = $firstItem?->variant?->product;
                        $imagePath = $firstProduct?->primaryImage?->image_path;
                        $imageUrl = $imagePath
                            ? (str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/'.ltrim($imagePath, '/')))
                            : null;
                        $status = strtolower($order->status?->value ?? (string) $order->status);
                    @endphp

                    <article class="reveal overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] transition duration-250 ease-out hover:border-[var(--brand)]/30 hover:shadow-[0_24px_50px_-40px_rgba(50,19,95,0.5)]"
                             style="--reveal-delay: {{ ($loop->index % 3) * 60 }}ms">
                        <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                            <div class="flex min-w-0 items-center gap-3.5">
                                <div class="h-16 w-14 shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $firstProduct?->name }}" loading="lazy" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[var(--brand)]/40">
                                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3h6l1 5-3 2 3 11H8l3-11-3-2 1-5Z" /></svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">#{{ $order->order_number }}</p>
                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[9px] font-bold {{ $statusClasses[$status] ?? 'border-[var(--border)] bg-[var(--surface-2)] text-[var(--text-muted)]' }}">
                                            {{ $statusLabels[$status] ?? ucfirst((string) $status) }}
                                        </span>
                                    </div>
                                    <p class="mt-1.5 truncate font-display text-base font-semibold tracking-[-0.015em] text-[var(--text)]">
                                        {{ $firstProduct?->name ?? 'Pesanan '.\App\Models\SiteSetting::storeName() }}
                                        @if ($order->items->count() > 1)
                                            <span class="text-[var(--text-subtle)]">+{{ $order->items->count() - 1 }}</span>
                                        @endif
                                    </p>
                                    <p class="mt-1 text-[10px] text-[var(--text-muted)]">
                                        {{ $order->created_at->format('d M Y, H:i') }} · {{ $order->items->sum('quantity') }} item
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center justify-between gap-3 border-t border-[var(--border-light)] pt-3.5 sm:border-0 sm:pt-0">
                                <div class="text-left sm:text-right">
                                    <p class="text-[9px] font-semibold uppercase tracking-[0.1em] text-[var(--text-subtle)]">Total</p>
                                    <p class="mt-0.5 font-display text-lg font-semibold tracking-[-0.02em] text-[var(--brand-dark)]">Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="inline-flex h-9 items-center gap-1.5 rounded-full bg-[var(--brand-soft)] px-3.5 text-[10px] font-bold text-[var(--brand-dark)] transition hover:bg-[var(--brand)] hover:text-white">
                                    Detail
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
                                </a>
                            </div>
                        </div>

                        @if ($status === 'pending')
                            <div class="flex flex-wrap items-center gap-2 border-t border-[var(--border-light)] bg-[var(--bg)]/60 px-4 py-3 sm:px-5">
                                <a href="{{ route('checkout.payment', $order->id) }}"
                                   class="inline-flex h-8 items-center justify-center rounded-full bg-[var(--brand)] px-3.5 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                                    Bayar Sekarang
                                </a>
                                <form method="POST" action="{{ route('orders.cancel', $order->id) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex h-8 items-center justify-center rounded-full border border-[var(--error)]/25 bg-[var(--surface)] px-3.5 text-[10px] font-bold text-[var(--error)] transition hover:bg-[var(--error-soft)]">
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <x-empty-state
                class="mx-auto max-w-md py-16"
                title="Belum ada pesanan"
                message="Kamu belum melakukan pemesanan. Mulai belanja dan temukan dress favoritmu."
                action-label="Mulai Belanja"
                :action-url="\App\Support\FrontendUrls::catalog()"
            />
        @endif
    </div>
@endsection
