@extends('frontend.layouts.app')

@section('title', 'Pesanan Diterima — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Pesanan '.\App\Models\SiteSetting::storeName().' Anda berhasil dibuat. Simpan nomor pesanan untuk melacak status pengiriman.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $ordersUrl = route('orders.index');
        $helpUrl = \App\Support\FrontendUrls::help();
        $orderUrl = route('orders.show', $order->id);
        $paymentValue = $order->payment_status->value ?? (string) $order->payment_status;
        $paymentLabel = $order->payment_status->label() ?? ucfirst(strtolower($paymentValue));
        $paymentTone = match ($paymentValue) {
            'SETTLED' => 'border-[var(--success)]/15 bg-[var(--success-soft)] text-[var(--success)]',
            'FAILED', 'EXPIRED' => 'border-[var(--error)]/15 bg-[var(--error-soft)] text-[var(--error)]',
            default => 'border-[var(--accent)]/20 bg-[var(--accent-soft)] text-[#7a5a1f]',
        };
        $firstName = str(auth()->user()->name ?? 'Kamu')->before(' ')->toString();
    @endphp

    <div class="mx-auto max-w-4xl">
        <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ $ordersUrl }}" class="transition hover:text-[var(--brand)]">Pesanan</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">Pesanan diterima</span>
        </nav>

        {{-- Hero --}}
        <section class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-9 text-center sm:px-10 sm:py-11">
            <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/90 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -right-10 h-56 w-56 rounded-full bg-[var(--accent-soft)]/90 blur-2xl"></div>

            <div class="relative">
                <div class="pop-check mx-auto flex h-18 w-18 items-center justify-center rounded-full bg-[var(--success)] text-white shadow-[0_18px_36px_-20px_rgba(21,128,61,0.7)] ring-8 ring-[var(--success-soft)]">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>

                <p class="section-kicker mt-6">Pesanan tercatat</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-none tracking-[-0.045em] text-[var(--brand)] sm:text-5xl">
                    Terima kasih, {{ $firstName }}!
                </h1>
                <p class="mx-auto mt-3 max-w-md text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Pesananmu sudah kami terima dan sedang disiapkan. Detail dan status pengiriman bisa dicek kapan saja di halaman pesanan.
                </p>

                <div class="mx-auto mt-6 flex w-fit max-w-full flex-wrap items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-[var(--surface-2)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)]">
                        <span class="text-[var(--text-subtle)]">No. pesanan</span>
                        <span class="font-mono tracking-[0.04em] text-[var(--text)]">{{ $order->order_number }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border px-3.5 py-2 text-[10px] font-bold {{ $paymentTone }}">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $paymentLabel }}
                    </span>
                </div>
            </div>
        </section>

        <div class="mt-6 grid items-start gap-5 lg:grid-cols-[1.35fr_0.8fr]">
            <div class="space-y-5">
            {{-- Order detail --}}
            <section class="reveal overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
                <div class="flex items-center justify-between gap-4 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Rincian</p>
                        <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Produk dalam pesanan</h2>
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
                            $variantDetails = $item->variant_details ?? [];
                            $color = $variantDetails['color'] ?? $item->variant?->color ?? '-';
                            $size = $variantDetails['size'] ?? $item->variant?->size ?? '-';
                        @endphp

                        <div class="flex items-center gap-3 py-4 sm:gap-4">
                            <div class="h-[5.75rem] w-[4.35rem] shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--brand)]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6 opacity-40" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[13px] font-semibold leading-snug text-[var(--text)]">{{ $item->product_name }}</p>
                                <p class="mt-1.5 flex flex-wrap items-center gap-1.5 text-[9px] font-semibold text-[var(--text-muted)]">
                                    <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">{{ $color }}</span>
                                    <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">Size {{ $size }}</span>
                                </p>
                                <p class="mt-2 text-[10px] text-[var(--text-subtle)]">Rp {{ number_format((float) $item->price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                            </div>

                            <p class="shrink-0 text-sm font-extrabold tracking-tight text-[var(--brand-dark)]">
                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-[var(--border-light)] bg-[var(--surface-2)]/40 px-5 py-4 sm:px-6">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--brand)] shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m19.5 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M3 9.75h11.25m-11.25 9h.375" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[11px] font-bold text-[var(--text)]">Dikirim ke {{ $order->recipient_name ?: (auth()->user()->name ?? 'alamatmu') }}</p>
                            <p class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">
                                {{ $order->shipping_address ?: 'Alamat pengiriman akan dikonfirmasi oleh tim kami.' }}
                            </p>
                            @if ($order->recipient_phone)
                                <p class="mt-1 text-[10px] text-[var(--text-subtle)]">{{ $order->recipient_phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- Next steps --}}
            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-6" style="--reveal-delay: 100ms">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Selanjutnya</p>
                        <h2 class="mt-1 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Apa yang terjadi sekarang?</h2>
                    </div>
                    <a href="{{ $helpUrl }}" class="inline-flex items-center gap-1.5 text-[10px] font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">
                        Butuh bantuan?
                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <ol class="mt-4 grid gap-3 sm:grid-cols-3">
                    @foreach ([
                        ['title' => 'Pembayaran diverifikasi', 'desc' => 'Status otomatis diperbarui setelah pembayaran dikonfirmasi Midtrans.'],
                        ['title' => 'Pesanan disiapkan', 'desc' => 'Tim gudang memeriksa kualitas dan mengemas pesananmu dengan rapi.'],
                        ['title' => 'Paket dikirim', 'desc' => 'Nomor resi muncul di halaman pesanan saat paket mulai dikirim.'],
                    ] as $step)
                        <li class="rounded-xl bg-[var(--bg)] p-3.5">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[9px] font-black text-[var(--brand)]">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <p class="mt-2.5 text-[10px] font-bold text-[var(--text)]">{{ $step['title'] }}</p>
                            <p class="mt-1 text-[9px] leading-4 text-[var(--text-muted)]">{{ $step['desc'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </section>
            </div>

            {{-- Summary --}}
            <aside class="space-y-5">
                <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: 80ms">
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
                        @if ((float) $order->discount_amount > 0)
                            <div class="flex items-center justify-between font-bold text-[var(--success)]">
                                <span>Diskon</span>
                                <span>− Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 flex items-end justify-between border-t border-[var(--border-light)] pt-4">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Total</p>
                            <p class="mt-1 text-[9px] text-[var(--text-muted)]">Termasuk ongkir</p>
                        </div>
                        <p class="font-display text-[1.7rem] font-semibold leading-none tracking-[-0.03em] text-[var(--brand-dark)]">
                            Rp {{ number_format((float) $order->grand_total, 0, ',', '.') }}
                        </p>
                    </div>

                    <a href="{{ $orderUrl }}"
                       class="mt-5 flex h-11 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[10px] font-bold text-white shadow-[0_12px_26px_-18px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)]">
                        Lihat Status Pesanan
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </a>

                    <a href="{{ $catalogUrl }}"
                       class="mt-2 flex h-10 w-full items-center justify-center rounded-full border border-[var(--border)] px-5 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                        Lanjut belanja
                    </a>
                </section>
            </aside>
        </div>
    </div>
@endsection
