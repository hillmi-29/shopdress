@extends('frontend.layouts.app')

@section('title', 'Keranjang Belanja — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Kelola keranjang belanja Anda di '.\App\Models\SiteSetting::storeName().'. Periksa produk, ubah jumlah, dan lanjutkan ke checkout.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $promoUrl = \App\Support\FrontendUrls::promo();
        $checkoutUrl = route('checkout.index', ['mode' => 'cart']);
        $itemCount = $cart?->total_quantity ?? 0;
        $featuredCoupon = $featuredCoupon ?? null;
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">Keranjang</span>
    </nav>

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="relative flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-kicker">Langkah terakhir</p>
                <h1 class="mt-2 font-display text-[2.4rem] font-semibold leading-none tracking-[-0.045em] text-[var(--brand)] sm:text-5xl">
                    Keranjang Belanja
                </h1>
                <p class="mt-3 max-w-lg text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Periksa kembali pilihanmu sebelum melanjutkan ke pembayaran. Stok akan dikunci saat checkout.
                </p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-[var(--surface-2)] px-3.5 py-2 text-[10px] font-bold text-[var(--text-muted)]">
                <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand)]"></span>
                {{ $itemCount }} item dipilih
            </span>
        </div>
    </header>

    @if ($cart && $cart->items->isNotEmpty())
        <div x-data="cartPage()" class="mt-6 grid items-start gap-6 lg:grid-cols-[1.45fr_0.75fr] lg:gap-8">
            {{-- Items --}}
            <section class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Produk pilihanmu</h2>
                    <a href="{{ $catalogUrl }}" class="text-[10px] font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">
                        + Tambah produk
                    </a>
                </div>

                @foreach ($cart->items as $item)
                    @php
                        $variant = $item->variant;
                        $product = $variant->product;
                        $imagePath = $product->primaryImage?->image_path;
                        $imageUrl = $imagePath
                            ? (str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/'.ltrim($imagePath, '/')))
                            : null;
                        $unitPrice = number_format((float) $variant->final_price, 0, ',', '.');
                        $subtotal = number_format((float) ($variant->final_price * $item->quantity), 0, ',', '.');
                    @endphp

                    <article class="rounded-[1.4rem] border border-[var(--border)] bg-[var(--surface)] p-3 transition-colors duration-200 hover:border-[var(--text-subtle)]/40 sm:p-4"
                             x-data="{ qty: {{ $item->quantity }}, removing: false }">
                        <div class="flex gap-3.5 sm:gap-4">
                            <a href="{{ \App\Support\FrontendUrls::product($product) }}"
                               class="group relative h-[7.5rem] w-[5.75rem] shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)] sm:h-[9rem] sm:w-[6.75rem]">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--brand)]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-8 w-8 opacity-40">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">{{ $product->category?->name ?? 'Dress Modern' }}</p>
                                        <h3 class="mt-1 line-clamp-2 text-[13px] font-semibold leading-snug text-[var(--text)] sm:text-sm">
                                            <a href="{{ \App\Support\FrontendUrls::product($product) }}" class="transition hover:text-[var(--brand)]">{{ $product->name }}</a>
                                        </h3>
                                        <p class="mt-2 flex flex-wrap items-center gap-1.5 text-[9px] font-semibold text-[var(--text-muted)]">
                                            <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">{{ $variant->color ?? '-' }}</span>
                                            <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">Size {{ $variant->size ?? '-' }}</span>
                                        </p>
                                    </div>

                                    <button type="button"
                                            @click="removing = true; removeItem({{ $item->id }})"
                                            :disabled="removing"
                                            class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--error-soft)] hover:text-[var(--error)] disabled:opacity-40"
                                            aria-label="Hapus {{ $product->name }} dari keranjang">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-.894.553L7.382 4H4a1 1 0 0 0 0 2v10a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a1 1 0 1 0-2 0v10H6V6h8V4h-3.382l-.724-1.447A1 1 0 0 0 9 2ZM7 8a1 1 0 0 1 2 0v6a1 1 0 1 1-2 0V8Zm5-1a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V8a1 1 0 0 0-1-1Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-auto flex flex-wrap items-end justify-between gap-2 pt-3">
                                    <div class="inline-flex h-8 items-center rounded-full border border-[var(--border)]">
                                        <button type="button"
                                                @click="qty = Math.max(1, qty - 1); updateQty({{ $item->id }}, qty)"
                                                :disabled="qty <= 1"
                                                class="flex h-full w-8 items-center justify-center rounded-l-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                                aria-label="Kurangi jumlah">−</button>
                                        <span class="w-7 text-center text-[11px] font-bold text-[var(--text)]" x-text="qty">{{ $item->quantity }}</span>
                                        <button type="button"
                                                @click="qty = Math.min({{ $variant->stock }}, qty + 1); updateQty({{ $item->id }}, qty)"
                                                :disabled="qty >= {{ $variant->stock }}"
                                                class="flex h-full w-8 items-center justify-center rounded-r-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                                aria-label="Tambah jumlah">+</button>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-[9px] text-[var(--text-subtle)]">Rp {{ $unitPrice }} × <span x-text="qty">{{ $item->quantity }}</span></p>
                                        <p class="mt-0.5 text-sm font-extrabold tracking-tight text-[var(--brand-dark)]">Rp {{ $subtotal }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Promo hint --}}
                <a href="{{ $promoUrl }}"
                   class="group flex items-center justify-between gap-4 rounded-[1.25rem] border border-dashed border-[var(--brand)]/30 bg-[var(--brand-soft)]/50 px-4 py-3.5 transition hover:border-[var(--brand)]/60 hover:bg-[var(--brand-soft)]/80">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--brand)] shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.59l9.091 9.09c.888.889 2.329.889 3.217 0l5.251-5.25a2.276 2.276 0 0 0 0-3.217l-9.09-9.09A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-bold text-[var(--brand-dark)]">Punya kode promo?</p>
                            @if ($featuredCoupon)
                                <p class="mt-0.5 text-[10px] text-[var(--text-muted)]">Pakai <span class="font-mono font-bold text-[var(--brand)]">{{ $featuredCoupon->code }}</span> dan dapatkan {{ lcfirst($featuredCoupon->discountLabel()) }}.</p>
                            @else
                                <p class="mt-0.5 text-[10px] text-[var(--text-muted)]">Lihat promo dan kupon yang sedang berjalan.</p>
                            @endif
                        </div>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-[var(--brand)] transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </section>

            {{-- Summary --}}
            <aside class="lg:sticky lg:top-24">
                <div class="rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Ringkasan</h2>
                        <span class="text-[9px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">{{ $cart->total_quantity }} item</span>
                    </div>

                    <div class="mt-5 space-y-3 text-[11px]">
                        <div class="flex items-center justify-between text-[var(--text-muted)]">
                            <span>Subtotal produk</span>
                            <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $cart->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[var(--text-muted)]">
                            <span>Ongkos kirim</span>
                            <span class="font-semibold text-[var(--text)]">Dihitung saat checkout</span>
                        </div>
                        <div class="flex items-center justify-between text-[var(--text-muted)]">
                            <span>Diskon</span>
                            <span class="text-[var(--text-subtle)]">Masukkan kode di checkout</span>
                        </div>
                    </div>

                    <div class="my-4 h-px bg-[var(--border-light)]"></div>

                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Total sementara</p>
                            <p class="mt-1 text-[10px] text-[var(--text-muted)]">Belum termasuk ongkir</p>
                        </div>
                        <p class="font-display text-[1.8rem] font-semibold leading-none tracking-[-0.03em] text-[var(--brand-dark)]">
                            Rp {{ number_format((float) $cart->total_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <a href="{{ $checkoutUrl }}"
                       class="mt-5 flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)]">
                        Lanjut ke Checkout
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd"/>
                        </svg>
                    </a>

                    <a href="{{ $catalogUrl }}"
                       class="mt-2 flex h-10 w-full items-center justify-center rounded-full border border-[var(--border)] px-5 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--text)] hover:text-[var(--text)]">
                        Tambah busana lain
                    </a>

                    <div class="mt-5 flex items-center justify-center gap-1.5 border-t border-[var(--border-light)] pt-4 text-[9px] font-medium text-[var(--text-subtle)]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        Pembayaran aman dan terenkripsi
                    </div>
                </div>
            </aside>
        </div>
    @else
        <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-6 py-16 text-center">
            <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
                </svg>
                <span class="absolute -right-1 top-1 flex h-7 w-7 items-center justify-center rounded-full bg-[var(--accent-soft)] text-[var(--accent)] ring-4 ring-[var(--surface)]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                </span>
            </div>
            <h2 class="mt-5 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)]">Keranjang masih kosong</h2>
            <p class="mx-auto mt-2 max-w-md text-xs leading-6 text-[var(--text-muted)]">
                Yuk jelajahi koleksi dress, gamis, dan busana cantik kami untuk mulai mengisi keranjang belanja.
            </p>
            <a href="{{ $catalogUrl }}"
               class="mt-6 inline-flex items-center gap-2 rounded-full bg-[var(--brand)] px-6 py-3 text-[11px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                Mulai belanja sekarang
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartPage', () => ({
                updateQty(id, qty) {
                    fetch(`{{ url('api/cart') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ quantity: qty })
                    }).then(() => window.location.reload());
                },

                removeItem(id) {
                    fetch(`{{ url('api/cart') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    }).then(() => window.location.reload());
                }
            }));
        });
    </script>
@endpush
