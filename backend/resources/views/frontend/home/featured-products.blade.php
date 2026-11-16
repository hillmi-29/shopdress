{{-- Recommended products --}}
<section class="mt-16 sm:mt-20">
    <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-xl">
            <p class="section-kicker">Koleksi unggulan</p>
            <h2 class="mt-2 font-display text-3xl font-semibold tracking-[-0.03em] text-[var(--text)] sm:text-4xl">
                Rekomendasi untukmu
            </h2>
            <p class="mt-2 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                Potongan favorit pelanggan dengan bahan premium yang nyaman dipakai dari pagi sampai malam.
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

    @if ($featuredProducts->isNotEmpty())
        <div class="mt-7 grid grid-cols-2 gap-3.5 sm:gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($featuredProducts as $product)
                <div class="reveal" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <x-product-card
                        :product="$product"
                        :wishlisted="in_array($product->id, $wishlistProductIds, true)"
                    />
                </div>
            @endforeach
        </div>
    @else
        <div class="reveal mt-7 rounded-3xl border border-dashed border-[var(--border)] bg-[var(--surface)] p-10 text-center">
            <p class="font-display text-xl font-semibold text-[var(--text)]">Koleksi sedang disiapkan</p>
            <p class="mt-2 text-xs text-[var(--text-muted)]">Belum ada produk rekomendasi saat ini. Jelajahi seluruh katalog untuk melihat koleksi lain.</p>
            <a href="{{ $catalogUrl }}" class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-[var(--brand)] px-4 py-2.5 text-[11px] font-bold text-white transition hover:bg-[var(--brand-dark)]">
                Lihat semua koleksi
            </a>
        </div>
    @endif
</section>
