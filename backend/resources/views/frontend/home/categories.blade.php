{{-- Category cards --}}
<section class="mt-16 sm:mt-20">
    <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-xl">
            <p class="section-kicker">Mulai dari gayamu</p>
            <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                Kategori Pilihan
            </h2>
            <p class="mt-2 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Dari busana harian sampai gaun pesta — temukan potongan yang paling sesuai dengan momenmu.
            </p>
        </div>

        <a href="{{ $categoryIndexUrl }}"
           class="group inline-flex shrink-0 items-center gap-2 text-[11px] font-bold text-[var(--brand)]">
            <span>Lihat semua kategori</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-full border border-[var(--brand)]/25 transition-all duration-200 ease-out group-hover:border-[var(--brand)] group-hover:bg-[var(--brand)] group-hover:text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </span>
        </a>
    </div>

    <div class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ($categories as $category)
            @php
                $url = $categoryUrl($category);
                $productCount = $category->products_count ?? $category->products()->count();
            @endphp

            @php
                $catImg = $category->image
                    ? asset('storage/'.ltrim($category->image, '/'))
                    : null;
                // fallback: pakai primary image produk pertama di kategori
                if (! $catImg) {
                    $firstProduct = $category->products()->with('primaryImage')->first();
                    $fp = $firstProduct?->primaryImage?->image_path;
                    $catImg = $fp
                        ? (str_starts_with($fp, 'http') ? $fp : asset('storage/'.ltrim($fp, '/')))
                        : null;
                }
            @endphp
            <a href="{{ $url }}"
               class="category-card reveal group relative flex min-h-[9.5rem] flex-col justify-between overflow-hidden rounded-[1.35rem] border border-[var(--border)] bg-[var(--surface)] p-4 hover:border-transparent hover:shadow-[0_18px_36px_-26px_rgba(124,58,237,0.65)] sm:min-h-[10.5rem] sm:p-5"
               style="--reveal-delay: {{ $loop->index * 60 }}ms">

                {{-- Background image when available --}}
                @if ($catImg)
                    <img src="{{ $catImg }}"
                         alt="Koleksi {{ $category->name }}"
                         loading="lazy"
                         class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-[1.06]">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#241d2e]/85 via-[#241d2e]/30 to-[#241d2e]/5"></div>
                @else
                    <div class="pointer-events-none absolute -right-5 -top-6 h-20 w-20 rounded-full border border-[var(--brand)]/10 transition-colors duration-300 group-hover:border-white/20"></div>
                @endif

                <div class="relative flex items-start justify-between gap-2">
                    <span class="text-[10px] font-bold tabular-nums tracking-[0.14em] {{ $catImg ? 'text-white/60' : 'text-[var(--text-subtle)] group-hover:text-white/50' }} transition-colors duration-200">
                        {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full border {{ $catImg ? 'border-white/30 bg-white/10 text-white backdrop-blur-sm group-hover:border-white group-hover:bg-white group-hover:text-[var(--brand)]' : 'border-[var(--border)] text-[var(--text-muted)] group-hover:border-white/30 group-hover:bg-white group-hover:text-[var(--brand)]' }} transition-all duration-250 ease-out">
                        <svg class="h-3.5 w-3.5 -rotate-45 transition-transform duration-250 ease-out group-hover:rotate-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="relative mt-6">
                    <h3 class="font-display text-[1.05rem] font-semibold leading-[1.15] tracking-[-0.02em] {{ $catImg ? 'text-white' : 'text-[var(--text)] group-hover:text-white' }} transition-colors duration-200 sm:text-lg">
                        {{ $category->name }}
                    </h3>
                    <p class="mt-1.5 text-[10px] font-medium {{ $catImg ? 'text-white/65' : 'text-[var(--text-subtle)] group-hover:text-white/65' }} transition-colors duration-200">
                        {{ $productCount }} produk
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</section>
