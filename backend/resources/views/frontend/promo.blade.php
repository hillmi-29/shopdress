@extends('frontend.layouts.app')

@section('title', 'Promo & Kupon Diskon — ShopDress')
@section('meta_description', 'Kumpulan promo diskon, voucher belanja, dan penawaran khusus busana ShopDress.')

@section('content')
    @php
        $catalogUrl = \App\Support\FrontendUrls::catalog();
        $promoUrl = \App\Support\FrontendUrls::promo();

        $coupons = [
            [
                'code' => 'SHOPDRESSNEW',
                'badge' => 'Diskon 20%',
                'title' => 'Pelanggan baru',
                'description' => 'Potongan 20% untuk pesanan pertamamu, tanpa perlu menunggu momen khusus.',
                'minimum' => 'Min. belanja Rp150.000',
                'note' => 'Berlaku untuk semua koleksi.',
                'featured' => true,
            ],
            [
                'code' => 'HEMAT50RB',
                'badge' => 'Potongan Rp50.000',
                'title' => 'Belanja lebih hemat',
                'description' => 'Potongan langsung Rp50.000 untuk belanja pilihanmu.',
                'minimum' => 'Min. belanja Rp250.000',
                'note' => 'Cocok untuk melengkapi beberapa item sekaligus.',
                'featured' => false,
            ],
            [
                'code' => 'GRATISONGKIR',
                'badge' => 'Subsidi ongkir',
                'title' => 'Gratis ongkir',
                'description' => 'Subsidi ongkos kirim hingga Rp20.000 ke seluruh Indonesia.',
                'minimum' => 'Min. belanja Rp100.000',
                'note' => 'Otomatis mengurangi biaya pengiriman.',
                'featured' => false,
            ],
        ];
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">Promo</span>
    </nav>

    <div x-data="{
            copied: null,
            copyCoupon(code) {
                const done = () => {
                    this.copied = code;
                    this.$dispatch('toast', {
                        type: 'success',
                        title: 'Kupon disalin',
                        message: 'Kode ' + code + ' siap dipakai saat checkout.'
                    });
                    setTimeout(() => { if (this.copied === code) this.copied = null; }, 3000);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(code).then(done).catch(done);
                } else {
                    done();
                }
            }
         }">

        <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-8 sm:px-8 sm:py-10">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/4 h-40 w-40 rounded-full bg-[var(--accent-soft)]/80 blur-2xl"></div>

            <div class="relative max-w-2xl">
                <p class="section-kicker">Penawaran spesial</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--brand)] sm:text-6xl">
                    Promo &amp; kupon
                </h1>
                <p class="mt-3 max-w-lg text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Sedikit bonus untuk melengkapi lemari. Salin kode favoritmu dan gunakan saat checkout — potongan langsung terlihat di ringkasan pesanan.
                </p>
                <div class="mt-5 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[9px] font-bold text-[var(--text-muted)]">
                        <span class="h-1.5 w-1.5 rounded-full bg-[var(--success)]"></span>
                        Aktif sekarang
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[9px] font-bold text-[var(--text-muted)]">
                        Tidak dapat digabung
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[9px] font-bold text-[var(--text-muted)]">
                        Berlaku hingga 31 Des 2026
                    </span>
                </div>
            </div>
        </header>

        {{-- Coupons --}}
        <div class="mt-5 grid gap-4 sm:grid-cols-2">
            @foreach ($coupons as $coupon)
                @if ($coupon['featured'])
                    <article class="reveal relative overflow-hidden rounded-[1.5rem] bg-[var(--brand-deep)] p-5 text-white shadow-[0_26px_60px_-42px_rgba(50,19,95,0.9)] sm:col-span-2 sm:p-6">
                        <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[var(--brand)]/40 blur-3xl"></div>
                        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-[var(--accent)]/15 blur-3xl"></div>

                        <div class="relative flex h-full flex-col justify-between gap-6 sm:flex-row sm:items-end">
                            <div class="max-w-sm">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--accent)] px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.1em] text-[var(--brand-deep)]">
                                    {{ $coupon['badge'] }}
                                </span>
                                <h2 class="mt-3 font-display text-3xl font-semibold leading-none tracking-[-0.03em] sm:text-4xl">
                                    {{ $coupon['title'] }}
                                </h2>
                                <p class="mt-2 text-[11px] leading-5 text-white/65">{{ $coupon['description'] }}</p>
                                <p class="mt-3 text-[9px] font-semibold text-white/45">{{ $coupon['minimum'] }} · {{ $coupon['note'] }}</p>
                            </div>

                            <div class="w-full max-w-[15rem] shrink-0 rounded-2xl border border-dashed border-white/25 bg-white/[0.06] p-3 backdrop-blur-sm">
                                <p class="text-[8px] font-bold uppercase tracking-[0.16em] text-white/45">Kode voucher</p>
                                <div class="mt-1.5 flex items-center justify-between gap-2">
                                    <span class="font-mono text-sm font-black tracking-[0.05em] text-white">{{ $coupon['code'] }}</span>
                                    <button type="button"
                                            @click="copyCoupon(@js($coupon['code']))"
                                            class="inline-flex h-8 items-center gap-1.5 rounded-lg bg-[var(--accent)] px-2.5 text-[9px] font-black text-[var(--brand-deep)] transition hover:bg-[#e0b359] active:scale-95">
                                        <svg x-show="copied !== @js($coupon['code'])" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M7 3.5A1.5 1.5 0 0 1 8.5 2h3.879a1.5 1.5 0 0 1 1.06.44l3.122 3.12A1.5 1.5 0 0 1 17 6.622V12.5a1.5 1.5 0 0 1-1.5 1.5h-1v-3.379a3 3 0 0 0-.879-2.121L10.5 5.379A3 3 0 0 0 8.379 4.5H7v-1Z" />
                                            <path fill-rule="evenodd" d="M4.5 6A1.5 1.5 0 0 0 3 7.5v9A1.5 1.5 0 0 0 4.5 18h7a1.5 1.5 0 0 0 1.5-1.5v-5.879a1.5 1.5 0 0 0-.44-1.06L9.44 6.439A1.5 1.5 0 0 0 8.378 6H4.5Zm4 5a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Zm0 3a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                                        </svg>
                                        <svg x-show="copied === @js($coupon['code'])" x-cloak class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="copied === @js($coupon['code']) ? 'Tersalin' : 'Salin'">Salin</span>
                                    </button>
                                </div>
                                <a href="{{ $catalogUrl }}" class="mt-2 flex h-8 w-full items-center justify-center rounded-lg bg-white text-[9px] font-bold text-[var(--brand-deep)] transition hover:bg-[var(--brand-soft)]">
                                    Belanja sekarang
                                </a>
                            </div>
                        </div>
                    </article>
                @else
                    <article class="reveal relative flex flex-col overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                        <div class="flex items-start justify-between gap-3">
                            <span class="inline-flex items-center rounded-full bg-[var(--brand-soft)] px-2.5 py-1 text-[9px] font-black uppercase tracking-[0.08em] text-[var(--brand-dark)]">
                                {{ $coupon['badge'] }}
                            </span>
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--accent-soft)] text-[var(--accent)]">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.59l9.091 9.09c.888.889 2.329.889 3.217 0l5.251-5.25a2.276 2.276 0 0 0 0-3.217l-9.09-9.09A2.25 2.25 0 0 0 9.568 3Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                </svg>
                            </span>
                        </div>

                        <h2 class="mt-4 font-display text-2xl font-semibold leading-none tracking-[-0.025em] text-[var(--text)]">{{ $coupon['title'] }}</h2>
                        <p class="mt-2 flex-1 text-[10px] leading-5 text-[var(--text-muted)]">{{ $coupon['description'] }}</p>
                        <p class="mt-3 text-[9px] font-semibold text-[var(--text-subtle)]">{{ $coupon['minimum'] }}</p>

                        <div class="mt-3 flex items-center justify-between gap-2 rounded-xl border border-dashed border-[var(--brand)]/30 bg-[var(--brand-soft)]/45 p-2.5">
                            <span class="font-mono text-[11px] font-black tracking-[0.04em] text-[var(--brand-dark)]">{{ $coupon['code'] }}</span>
                            <button type="button"
                                    @click="copyCoupon(@js($coupon['code']))"
                                    class="inline-flex h-7 items-center rounded-lg bg-[var(--brand)] px-2.5 text-[9px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                                <span x-text="copied === @js($coupon['code']) ? 'Tersalin' : 'Salin'">Salin</span>
                            </button>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>

        {{-- Terms --}}
        <section class="mt-8 grid gap-4 rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:grid-cols-3 sm:p-6">
            @foreach ([
                ['title' => 'Satu kupon per pesanan', 'desc' => 'Pilih satu kode yang paling menguntungkan. Kupon tidak dapat digabung dengan promo lain.'],
                ['title' => 'Masukkan saat checkout', 'desc' => 'Tempel kode di kolom voucher pada ringkasan pesanan, lalu tekan Pakai.'],
                ['title' => 'Syarat minimum', 'desc' => 'Minimum belanja dihitung dari subtotal produk sebelum ongkos kirim.'],
            ] as $term)
                <div class="flex gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[var(--surface-2)] text-[9px] font-black text-[var(--brand)]">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div>
                        <p class="text-[11px] font-bold text-[var(--text)]">{{ $term['title'] }}</p>
                        <p class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">{{ $term['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </section>

        @include('frontend.home.promo-banner', ['promoUrl' => $promoUrl])
    </div>
@endsection
