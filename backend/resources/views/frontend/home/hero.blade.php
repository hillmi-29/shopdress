@php
    $catalogUrl = $catalogUrl ?? \App\Support\FrontendUrls::catalog();
    $promoUrl = $promoUrl ?? \App\Support\FrontendUrls::promo();
    $helpUrl = $helpUrl ?? \App\Support\FrontendUrls::help();
    $featuredCoupon = $featuredCoupon ?? null;
    $storeName = \App\Models\SiteSetting::storeName();

    $fallbackSlides = [
        [
            'eyebrow' => 'Koleksi 2026',
            'title' => 'Temukan dress yang membuatmu merasa {highlight}',
            'highlight' => 'paling percaya diri',
            'subtitle' => 'Potongan anggun, bahan premium yang sejuk, dan detail rapi — dirancang untuk menemani setiap langkahmu dengan nyaman.',
            'coupon_code' => null,
            'image_url' => asset('images/editorial/hero-1.jpg'),
            'image_alt' => 'Dress putih elegan dari koleksi terbaru '.$storeName,
            'caption' => 'The Soft Ivory Edit',
            'caption_sub' => 'Satin flow dress',
            'badge' => 'New arrival',
            'badge_style' => 'light',
            'cta_label' => 'Belanja Sekarang',
            'cta_url' => $catalogUrl,
            'secondary_label' => 'Lihat promo bulan ini',
            'secondary_url' => $promoUrl,
            'footnote_lines' => [],
            'show_rating' => true,
            'rating_text' => '4.9/5 dari 1.200+ pelanggan',
        ],
        [
            'eyebrow' => 'Promo aktif bulan ini',
            'title' => 'Potongan {highlight} untuk belanja pertamamu',
            'highlight' => $featuredCoupon?->promoHighlight() ?? 'spesial',
            'subtitle' => $featuredCoupon
                ? 'Gunakan kode '.$featuredCoupon->code.' saat checkout. '.$featuredCoupon->minimumLabel().'.'
                : 'Lihat kupon yang sedang berjalan dan gunakan saat checkout.',
            'coupon_code' => $featuredCoupon?->code,
            'image_url' => asset('images/editorial/hero-2.jpg'),
            'image_alt' => 'Dress ruffle pink dari koleksi '.$storeName,
            'caption' => 'The Blush Edit',
            'caption_sub' => 'Soft ruffle midi',
            'badge' => $featuredCoupon?->discountLabel() ?? 'Promo',
            'badge_style' => 'accent',
            'cta_label' => 'Klaim Promo',
            'cta_url' => $promoUrl,
            'secondary_label' => 'Lihat koleksi dulu',
            'secondary_url' => $catalogUrl,
            'footnote_lines' => [$featuredCoupon?->minimumLabel() ?? 'Lihat kupon aktif', 'Berlaku untuk semua kategori'],
            'show_rating' => false,
            'rating_text' => null,
        ],
        [
            'eyebrow' => 'Belanja tanpa ragu',
            'title' => 'Gratis ongkir & garansi retur 7 hari',
            'highlight' => null,
            'subtitle' => 'Bebas coba di rumah. Kalau ukuran belum pas, tukar dengan mudah dalam 7 hari — tanpa biaya admin tambahan.',
            'coupon_code' => null,
            'image_url' => asset('images/editorial/hero-3.jpg'),
            'image_alt' => 'Koleksi dress floral '.$storeName,
            'caption' => 'The Garden Edit',
            'caption_sub' => 'Floral everyday dress',
            'badge' => 'Retur 7 hari',
            'badge_style' => 'light_check',
            'cta_label' => 'Mulai Belanja',
            'cta_url' => $catalogUrl,
            'secondary_label' => 'Pelajari layanan',
            'secondary_url' => $helpUrl,
            'footnote_lines' => ['Dikirim dari Jakarta', 'Packing eksklusif'],
            'show_rating' => false,
            'rating_text' => null,
        ],
    ];

    $heroSlides = isset($heroBanners) && $heroBanners->isNotEmpty()
        ? $heroBanners->map(fn ($banner) => [
            'eyebrow' => $banner->eyebrow,
            'title' => $banner->title,
            'highlight' => $banner->highlight,
            'subtitle' => $banner->subtitle,
            'coupon_code' => $banner->coupon_code,
            'image_url' => $banner->image_url ?: asset('images/editorial/hero-1.jpg'),
            'image_alt' => $banner->image_alt ?: $banner->title,
            'caption' => $banner->caption,
            'caption_sub' => $banner->caption_sub,
            'badge' => $banner->badge,
            'badge_style' => $banner->badge_style ?: 'light',
            'cta_label' => $banner->cta_label,
            'cta_url' => $banner->resolvedCtaUrl(),
            'secondary_label' => $banner->secondary_label,
            'secondary_url' => $banner->resolvedSecondaryUrl(),
            'footnote_lines' => $banner->footnoteLines(),
            'show_rating' => $banner->show_rating,
            'rating_text' => $banner->rating_text,
        ])->values()->all()
        : $fallbackSlides;

    $totalSlides = count($heroSlides);
@endphp

<section x-data="{
            current: 0,
            total: {{ $totalSlides }},
            paused: false,
            timer: null,
            reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            init() {
                if (! this.reduceMotion) {
                    this.timer = setInterval(() => { if (! this.paused) this.next(); }, 6500);
                }
            },
            next() { this.current = (this.current + 1) % this.total; },
            prev() { this.current = (this.current - 1 + this.total) % this.total; },
            goTo(index) { this.current = index; }
         }"
         @mouseenter="paused = true"
         @mouseleave="paused = false"
         @focusin="paused = true"
         @focusout="paused = false"
         class="relative overflow-hidden rounded-3xl border border-[var(--border)] bg-white p-0 shadow-[0_30px_80px_-60px_rgba(50,19,95,0.45)]"
         aria-roledescription="carousel"
         aria-label="Sorotan koleksi {{ $storeName }}">

    <div class="relative lg:h-[660px]">
        @foreach ($heroSlides as $index => $slide)
            @php
                $titleHtml = e($slide['title']);

                if (! empty($slide['highlight'])) {
                    $highlightHtml = '<span class="relative inline-block text-[var(--brand)]">'.e($slide['highlight']);

                    if ($index === 0) {
                        $highlightHtml .= '<span class="absolute inset-x-0 -bottom-1 h-[0.18em] rounded-full bg-[var(--accent-soft)] -z-10"></span>';
                    }

                    $highlightHtml .= '</span>';

                    $titleHtml = str_contains($slide['title'], '{highlight}')
                        ? str_replace('{highlight}', $highlightHtml, $titleHtml)
                        : $titleHtml.' '.$highlightHtml;
                }

                $subtitleHtml = e($slide['subtitle'] ?? '');

                if (! empty($slide['coupon_code']) && str_contains($subtitleHtml, $slide['coupon_code'])) {
                    $subtitleHtml = str_replace(
                        e($slide['coupon_code']),
                        '<span class="rounded-md bg-[var(--brand-soft)] px-1.5 py-0.5 font-mono text-[11px] font-bold text-[var(--brand-dark)]">'.e($slide['coupon_code']).'</span>',
                        $subtitleHtml,
                    );
                }
            @endphp

            <article x-show="current === {{ $index }}"
                     @if ($index > 0) x-cloak @endif
                     :class="current === {{ $index }} ? 'relative z-10' : 'absolute inset-0 z-0 pointer-events-none'"
                     x-transition:enter="transition opacity ease-out duration-400"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition opacity ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="flex flex-col lg:absolute lg:inset-0 lg:grid lg:grid-cols-[1.04fr_0.96fr]"
                     role="group"
                     aria-roledescription="slide"
                     aria-label="{{ $index + 1 }} dari {{ $totalSlides }}">

                {{-- Hero Image: Top on mobile (full-bleed), Right on desktop --}}
                <div class="relative order-first h-[260px] w-full overflow-hidden rounded-t-3xl bg-[var(--surface-2)] sm:h-[300px] lg:order-last lg:h-full lg:rounded-none">
                    @if (! empty($slide['image_url']))
                        <img src="{{ $slide['image_url'] }}"
                             alt="{{ $slide['image_alt'] }}"
                             class="h-full w-full object-cover {{ $index === 0 ? 'object-[50%_22%]' : 'object-[50%_18%]' }}"
                             @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif>
                    @endif
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#241d2e]/30 via-transparent to-transparent"></div>

                    @if (! empty($slide['badge']))
                        @if ($slide['badge_style'] === 'accent')
                            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-[var(--accent)] px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-white shadow-sm">
                                {{ $slide['badge'] }}
                            </span>
                        @else
                            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-[var(--surface)]/90 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.12em] text-[var(--text)] backdrop-blur">
                                @if ($slide['badge_style'] === 'light_check')
                                    <svg class="h-3 w-3 text-[var(--success)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--success)]"></span>
                                @endif
                                {{ $slide['badge'] }}
                            </span>
                        @endif
                    @endif

                    @if (! empty($slide['caption']) || ! empty($slide['caption_sub']))
                        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                            <p class="max-w-[11rem] text-[10px] font-semibold leading-4 text-white drop-shadow-sm">
                                {{ $slide['caption'] }}
                                @if (! empty($slide['caption_sub']))
                                    <br><span class="font-normal text-white/70">{{ $slide['caption_sub'] }}</span>
                                @endif
                            </p>
                            <span class="hidden text-[9px] font-bold uppercase tracking-[0.18em] text-white/80 lg:block [writing-mode:vertical-rl]">{{ $storeName }} / 2026</span>
                        </div>
                    @endif
                </div>

                {{-- Text Content: Below image on mobile, Left on desktop --}}
                <div class="order-last flex flex-col justify-center p-6 lg:order-first lg:py-14 lg:pl-12 lg:pr-8 lg:pb-16 xl:pl-14">
                    @if (! empty($slide['eyebrow']))
                        <div class="flex items-center gap-2">
                            <span class="h-px w-8 {{ $slide['badge_style'] === 'accent' ? 'bg-[var(--brand)]' : 'bg-[var(--accent)]' }}"></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] {{ $slide['badge_style'] === 'accent' ? 'text-[var(--brand)]' : 'text-[#8a6420]' }}">{{ $slide['eyebrow'] }}</span>
                        </div>
                    @endif

                    @if ($index === 0)
                        <h1 class="mt-4 font-display text-[2.55rem] font-medium leading-[0.98] tracking-[-0.04em] text-[var(--text)] sm:text-[3.5rem] lg:text-[3.35rem] xl:text-[3.9rem]">
                            {!! $titleHtml !!}
                        </h1>
                    @else
                        <h2 class="mt-4 font-display text-[2.55rem] font-medium leading-[0.98] tracking-[-0.04em] text-[var(--text)] sm:text-[3.4rem] lg:text-[3.3rem] xl:text-[3.75rem]">
                            {!! $titleHtml !!}
                        </h2>
                    @endif

                    @if (filled($slide['subtitle']))
                        <p class="mt-5 max-w-md text-[13px] leading-6 text-[var(--text-muted)] sm:text-sm">
                            {!! $subtitleHtml !!}
                        </p>
                    @endif

                    @if ((! empty($slide['cta_label']) && ! empty($slide['cta_url'])) || (! empty($slide['secondary_label']) && ! empty($slide['secondary_url'])))
                        <div class="mt-7 flex flex-wrap items-center gap-3">
                            @if (! empty($slide['cta_label']) && ! empty($slide['cta_url']))
                                <a href="{{ $slide['cta_url'] }}"
                                   class="group inline-flex items-center gap-2 rounded-full bg-[var(--brand)] px-5 py-3 text-[11px] font-bold text-white shadow-[0_12px_26px_-14px_rgba(124,58,237,0.85)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] hover:shadow-[0_16px_30px_-14px_rgba(124,58,237,0.9)]">
                                    {{ $slide['cta_label'] }}
                                    <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                            @if (! empty($slide['secondary_label']) && ! empty($slide['secondary_url']))
                                <a href="{{ $slide['secondary_url'] }}"
                                   class="group inline-flex items-center gap-1.5 px-2 py-2 text-[11px] font-bold text-[var(--text)] underline decoration-[var(--border)] decoration-1 underline-offset-[6px] transition-colors duration-200 hover:text-[var(--brand)] hover:decoration-[var(--brand)]">
                                    {{ $slide['secondary_label'] }}
                                </a>
                            @endif
                        </div>
                    @endif

                    @if ($slide['show_rating'])
                        <div class="mt-7 flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#d9c6f5] text-[9px] font-bold text-[var(--brand-deep)] ring-2 ring-[var(--surface)]">SN</span>
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#f0d9c8] text-[9px] font-bold text-[#7a4a2b] ring-2 ring-[var(--surface)]">AR</span>
                                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#d7e6d2] text-[9px] font-bold text-[#3f5c39] ring-2 ring-[var(--surface)]">+1k</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-[var(--accent)]" aria-hidden="true">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z"/></svg>
                                    @endfor
                                </div>
                                <p class="mt-0.5 text-[10px] font-medium text-[var(--text-subtle)]">{{ $slide['rating_text'] ?: '4.9/5 dari 1.200+ pelanggan' }}</p>
                            </div>
                        </div>
                    @elseif (! empty($slide['footnote_lines']))
                        <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-2 text-[10px] font-medium text-[var(--text-muted)]">
                            @foreach ($slide['footnote_lines'] as $point)
                                <span class="inline-flex items-center gap-1.5"><span class="h-1.5 w-1.5 rounded-full bg-[var(--success)]"></span>{{ $point }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    {{-- Controls: in-flow with border-t on mobile, absolute on desktop --}}
    <div class="relative z-20 flex items-center justify-between border-t border-slate-100 px-6 pt-4 pb-5 lg:absolute lg:inset-x-0 lg:bottom-0 lg:border-t-0 lg:bg-gradient-to-t lg:from-[var(--surface)] lg:via-[var(--surface)]/80 lg:to-transparent lg:px-8 lg:pb-4 lg:pt-8">
        <div class="flex items-center gap-2.5">
            <template x-for="i in total" :key="i">
                <button type="button"
                        @click="goTo(i - 1)"
                        class="h-1.5 rounded-full transition-all duration-300 ease-out focus:outline-none"
                        :class="current === (i - 1) ? 'w-8 bg-[var(--brand)]' : 'w-2 bg-[var(--border)] hover:bg-[var(--text-subtle)]'"
                        :aria-label="'Buka slide ' + i"
                        :aria-current="current === (i - 1) ? 'true' : 'false'"></button>
            </template>
            <span class="ml-1 text-[10px] font-bold tabular-nums text-[var(--text-subtle)]">
                0<span x-text="current + 1"></span> / 0<span x-text="total"></span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button type="button"
                    @click="prev()"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)]/90 text-[var(--text-muted)] backdrop-blur transition-all duration-200 ease-out hover:border-[var(--brand)] hover:text-[var(--brand)] focus:outline-none"
                    aria-label="Slide sebelumnya">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 0 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
            </button>
            <button type="button"
                    @click="next()"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)]/90 text-[var(--text-muted)] backdrop-blur transition-all duration-200 ease-out hover:border-[var(--brand)] hover:text-[var(--brand)] focus:outline-none"
                    aria-label="Slide selanjutnya">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    </div>
</section>
