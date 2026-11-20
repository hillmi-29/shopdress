@php
    $promoUrl = $promoUrl ?? \App\Support\FrontendUrls::promo();
    $featuredCoupon = $featuredCoupon ?? null;
    $storeName = \App\Models\SiteSetting::storeName();

    $fallbackPromo = [
        'eyebrow' => 'Promo aktif',
        'title' => 'Siap menemukan dress favoritmu?',
        'highlight' => $featuredCoupon
            ? $featuredCoupon->discountLabel().' untuk pesananmu.'
            : 'Ada penawaran baru setiap bulan.',
        'subtitle' => $featuredCoupon
            ? 'Gunakan kode di bawah saat checkout. '.$featuredCoupon->minimumLabel().'.'
            : 'Kunjungi halaman promo untuk melihat kupon yang sedang berjalan.',
        'coupon_code' => $featuredCoupon?->code,
        'image_url' => asset('images/editorial/promo.jpg'),
        'image_alt' => 'Pelanggan '.$storeName.' membawa tas belanja',
        'badge' => $featuredCoupon?->promoHighlight() ?? 'Promo',
        'cta_label' => 'Lihat semua promo',
        'cta_url' => $promoUrl,
        'footnote' => '*Syarat dan ketentuan berlaku. Tidak dapat digabung dengan promo lain.',
    ];

    $promo = isset($promoBanner) && $promoBanner
        ? [
            'eyebrow' => $promoBanner->eyebrow,
            'title' => $promoBanner->title,
            'highlight' => $promoBanner->highlight,
            'subtitle' => $promoBanner->subtitle,
            'coupon_code' => $promoBanner->coupon_code,
            'image_url' => $promoBanner->image_url ?: asset('images/editorial/promo.jpg'),
            'image_alt' => $promoBanner->image_alt ?: $promoBanner->title,
            'badge' => $promoBanner->badge,
            'cta_label' => $promoBanner->cta_label,
            'cta_url' => $promoBanner->resolvedCtaUrl() ?: $promoUrl,
            'footnote' => $promoBanner->footnote,
        ]
        : $fallbackPromo;
@endphp

{{-- Promo banner with valid coupon --}}
<section class="reveal mt-16 sm:mt-20"
         x-data="{
            copied: false,
            code: @js($promo['coupon_code'] ?? ''),
            copyCoupon() {
                if (! this.code) {
                    return;
                }

                const showCopied = () => {
                    this.copied = true;
                    this.$dispatch('toast', {
                        type: 'success',
                        title: 'Kupon disalin',
                        message: 'Kode ' + this.code + ' siap dipakai saat checkout.'
                    });
                    setTimeout(() => { this.copied = false; }, 3000);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(this.code).then(showCopied).catch(showCopied);
                } else {
                    showCopied();
                }
            }
         }">
    <div class="relative overflow-hidden rounded-[2rem] bg-[var(--brand-deep)] text-white shadow-[0_30px_80px_-55px_rgba(50,19,95,0.8)]">
        <div class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[var(--brand)]/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 right-1/3 h-64 w-64 rounded-full bg-[var(--accent)]/15 blur-3xl"></div>

        <div class="relative grid lg:grid-cols-[1.1fr_0.9fr]">
            {{-- Image (Mobile: top; Desktop: right column) --}}
            <div class="relative min-h-[15rem] overflow-hidden sm:min-h-[18rem] lg:min-h-full lg:col-start-2 lg:row-start-1">
                @if (filled($promo['image_url']))
                    <img src="{{ $promo['image_url'] }}"
                         alt="{{ $promo['image_alt'] }}"
                         loading="lazy"
                         class="h-full w-full object-cover object-center">
                @endif
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[var(--brand-deep)]/80 via-transparent to-transparent lg:bg-gradient-to-r lg:from-[var(--brand-deep)]/45 lg:via-transparent lg:to-transparent"></div>

                @if (filled($promo['badge']))
                    <div class="absolute right-5 top-5 flex h-16 w-16 -rotate-6 flex-col items-center justify-center rounded-full bg-[var(--accent)] text-[var(--brand-deep)] shadow-lg sm:h-20 sm:w-20">
                        <span class="text-lg font-black leading-none sm:text-xl">{{ $promo['badge'] }}</span>
                        <span class="mt-0.5 text-[8px] font-black uppercase tracking-[0.14em]">Off</span>
                    </div>
                @endif
            </div>

            {{-- Text content (Mobile: bottom; Desktop: left column) --}}
            <div class="flex flex-col justify-center px-6 py-9 sm:px-10 sm:py-12 lg:col-start-1 lg:row-start-1 lg:pl-12 lg:pr-10">
                @if (filled($promo['eyebrow']))
                    <div class="flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--accent)] text-[10px] font-black text-[var(--brand-deep)]">%</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#d9c6f5]">{{ $promo['eyebrow'] }}</span>
                    </div>
                @endif

                <h2 class="mt-4 max-w-xl font-display text-[2rem] font-medium leading-[1.03] tracking-[-0.035em] sm:text-[2.65rem] lg:text-[2.9rem]">
                    {{ $promo['title'] }}
                    @if (filled($promo['highlight']))
                        <span class="block text-[#d9c6f5]">{{ $promo['highlight'] }}</span>
                    @endif
                </h2>

                @if (filled($promo['subtitle']))
                    <p class="mt-4 max-w-lg text-xs leading-6 text-white/65 sm:text-sm">
                        {{ $promo['subtitle'] }}
                    </p>
                @endif

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @if (filled($promo['coupon_code']))
                        <div class="flex items-center gap-3 rounded-2xl border border-dashed border-white/25 bg-white/[0.06] py-2 pl-4 pr-2 backdrop-blur-sm">
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-white/45">Kode voucher</p>
                                <p class="mt-0.5 font-mono text-base font-black tracking-[0.08em] text-white" x-text="code">{{ $promo['coupon_code'] }}</p>
                            </div>
                            <button type="button"
                                    @click="copyCoupon()"
                                    class="inline-flex h-9 items-center gap-1.5 rounded-xl bg-[var(--accent)] px-3 text-[10px] font-black text-[var(--brand-deep)] transition-all duration-200 hover:bg-[#e0b359] active:scale-95 focus:outline-none">
                                <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M7 3.5A1.5 1.5 0 0 1 8.5 2h3.879a1.5 1.5 0 0 1 1.06.44l3.122 3.12A1.5 1.5 0 0 1 17 6.622V12.5a1.5 1.5 0 0 1-1.5 1.5h-1v-3.379a3 3 0 0 0-.879-2.121L10.5 5.379A3 3 0 0 0 8.379 4.5H7v-1Z"/><path fill-rule="evenodd" d="M4.5 6A1.5 1.5 0 0 0 3 7.5v9A1.5 1.5 0 0 0 4.5 18h7a1.5 1.5 0 0 0 1.5-1.5v-5.879a1.5 1.5 0 0 0-.44-1.06L9.44 6.439A1.5 1.5 0 0 0 8.378 6H4.5Zm4 5a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Zm0 3a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd"/></svg>
                                <svg x-show="copied" x-cloak class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                                <span x-text="copied ? 'Tersalin' : 'Salin'">Salin</span>
                            </button>
                        </div>
                    @endif

                    @if (filled($promo['cta_label']) && filled($promo['cta_url']))
                        <a href="{{ $promo['cta_url'] }}"
                           class="inline-flex h-12 items-center justify-center rounded-2xl bg-white px-5 text-[11px] font-bold text-[var(--brand-deep)] transition-colors duration-200 hover:bg-[#f4edff]">
                            {{ $promo['cta_label'] }}
                        </a>
                    @endif
                </div>

                @if (filled($promo['footnote']))
                    <p class="mt-4 text-[10px] text-white/40">{{ $promo['footnote'] }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
