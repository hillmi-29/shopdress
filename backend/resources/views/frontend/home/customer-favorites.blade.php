{{-- Customer favorites --}}
<section class="mt-12 sm:mt-16">
    <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-xl">
            <p class="section-kicker">Paling dicari</p>
            <h2 class="mt-2 font-display text-2xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-3xl">
                Kesukaan Pelanggan
            </h2>
            <p class="mt-2 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Produk yang paling sering dipesan dan paling dicari pelanggan ShopDress.
            </p>
        </div>

        <a href="{{ $catalogUrl }}"
           class="group inline-flex shrink-0 items-center gap-2 text-[11px] font-bold text-[var(--brand)]">
            <span>Lihat selengkapnya</span>
            <span class="flex h-7 w-7 items-center justify-center rounded-full border border-[var(--brand)]/25 transition-all duration-200 ease-out group-hover:border-[var(--brand)] group-hover:bg-[var(--brand)] group-hover:text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </span>
        </a>
    </div>

    @if ($customerFavorites->isNotEmpty())
        <div class="mt-6 grid grid-cols-2 gap-2.5 sm:grid-cols-3 sm:gap-3 lg:grid-cols-4">
            @foreach ($customerFavorites as $product)
                <div class="reveal" style="--reveal-delay: {{ $loop->index * 40 }}ms">
                    <x-product-card
                        :product="$product"
                        :wishlisted="in_array($product->id, $wishlistProductIds, true)"
                        compact
                    />
                </div>
            @endforeach
        </div>
    @else
        <div class="reveal mt-6 rounded-3xl border border-dashed border-[var(--border)] bg-[var(--surface)] p-8 text-center">
            <p class="font-display text-lg font-semibold text-[var(--text)]">Belum ada favorit pelanggan</p>
            <p class="mt-1.5 text-xs text-[var(--text-muted)]">Jadilah yang pertama menemukan koleksi favorit baru di katalog kami.</p>
            <a href="{{ $catalogUrl }}" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-[var(--brand)] px-4 py-2.5 text-[11px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                Jelajahi katalog
            </a>
        </div>
    @endif
</section>
