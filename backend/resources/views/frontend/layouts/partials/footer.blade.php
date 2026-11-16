@php
    $helpUrl = \App\Support\FrontendUrls::help();
@endphp

<footer class="relative mt-6 overflow-hidden bg-[var(--brand-deep)] text-white">
    {{-- Soft glow + editorial texture --}}
    <div class="pointer-events-none absolute -right-24 -top-32 h-80 w-80 rounded-full bg-[var(--brand)]/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-20 h-72 w-72 rounded-full bg-[var(--accent)]/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-16">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-3 lg:gap-14">
            {{-- Column 1 — Brand --}}
            <div>
                <a href="{{ $homeUrl }}" class="inline-flex items-center gap-2.5" aria-label="ShopDress — Beranda">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white ring-1 ring-white/15">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4.5 w-4.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z" />
                        </svg>
                    </span>
                    <span class="font-display text-2xl font-semibold tracking-[-0.03em]">
                        Shop<span class="text-[#c9b3f5]">Dress</span>
                    </span>
                </a>

                <p class="mt-5 max-w-xs text-xs leading-6 text-white/65">
                    Busana wanita modern yang dikurasi dengan bahan premium dan detail rapi — untuk menemani setiap momen, dari hari biasa sampai acara spesial.
                </p>

                <div class="mt-6 space-y-2.5 text-xs text-white/70">
                    <p class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 shrink-0 text-[#c9b3f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657 13.414 20.9a1.998 1.998 0 0 1-2.827 0l-4.244-4.243a8 8 0 1 1 11.314 0Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        Jakarta, Indonesia
                    </p>
                    <a href="mailto:halo@shopdress.com" class="flex items-center gap-2.5 transition hover:text-white">
                        <svg class="h-4 w-4 shrink-0 text-[#c9b3f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z"/></svg>
                        halo@shopdress.com
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2.5 transition hover:text-white">
                        <svg class="h-4 w-4 shrink-0 text-[#c9b3f5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6V5Z"/></svg>
                        +62 812-3456-7890
                    </a>
                </div>
            </div>

            {{-- Column 2 — Explore --}}
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#c9b3f5]">Jelajahi</h3>
                    <ul class="mt-4 space-y-2.5 text-xs text-white/70">
                        <li><a href="{{ $homeUrl }}" class="transition hover:text-white">Home</a></li>
                        <li><a href="{{ $catalogUrl }}" class="transition hover:text-white">Semua Produk</a></li>
                        <li><a href="{{ $promoUrl }}" class="transition hover:text-white">Promo & Diskon</a></li>
                        <li><a href="{{ $wishlistUrl }}" class="transition hover:text-white">Wishlist</a></li>
                        <li><a href="{{ $aboutUrl }}" class="transition hover:text-white">Tentang Kami</a></li>
                        <li><a href="{{ $helpUrl }}" class="transition hover:text-white">Bantuan & FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#c9b3f5]">Kategori</h3>
                    <ul class="mt-4 space-y-2.5 text-xs text-white/70">
                        @foreach ($navCategories->take(6) as $cat)
                            <li>
                                <a href="{{ \App\Support\FrontendUrls::category($cat) }}" class="transition hover:text-white">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Column 3 — Service --}}
            <div class="md:justify-self-end md:max-w-xs">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#c9b3f5]">Layanan Pelanggan</h3>
                <p class="mt-4 text-xs leading-6 text-white/65">
                    Tim customer care kami siap membantu urusan ukuran, pemesanan, dan pengiriman.
                </p>

                <dl class="mt-5 space-y-3 rounded-2xl border border-white/10 bg-white/[0.04] p-4 text-xs">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-white/55">Jam operasional</dt>
                        <dd class="font-bold text-white">09.00 – 21.00 WIB</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-white/55">Hari kerja</dt>
                        <dd class="font-bold text-white">Senin – Minggu</dd>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-white/55">Garansi retur</dt>
                        <dd class="font-bold text-[#9be7bd]">7 hari tukar ukuran</dd>
                    </div>
                </dl>

                <a href="https://wa.me/6281234567890?text=Halo%20ShopDress,%20saya%20ingin%20bertanya"
                   target="_blank" rel="noopener noreferrer"
                   class="mt-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-[11px] font-bold text-[var(--brand-deep)] transition hover:bg-[#f4edff]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m4.52 13.53c-.25-.13-1.47-.72-1.7-.81-.23-.09-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.44.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.21-.18-.46-.31"/></svg>
                    Chat dengan kami
                </a>
            </div>
        </div>

        {{-- Payment + copyright --}}
        <div class="mt-12 flex flex-col gap-5 border-t border-white/10 pt-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-1.5" aria-label="Metode pembayaran">
                @foreach (['BCA', 'MANDIRI', 'BNI', 'BRI', 'QRIS', 'GOPAY'] as $method)
                    <span class="rounded-md bg-white/95 px-2 py-1 text-[9px] font-black tracking-wide text-[#33304a]">{{ $method }}</span>
                @endforeach
                <span class="ml-1 inline-flex items-center gap-1.5 text-[10px] font-semibold text-white/50">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                    Pembayaran aman via Midtrans
                </span>
            </div>

            <div class="flex flex-col gap-1.5 text-[10px] text-white/45 sm:flex-row sm:items-center sm:gap-3">
                <p>&copy; {{ date('Y') }} ShopDress. Hak cipta dilindungi.</p>
                <span class="hidden h-1 w-1 rounded-full bg-white/30 sm:block"></span>
                <p>Dibuat dengan teliti untuk wanita Indonesia.</p>
            </div>
        </div>
    </div>
</footer>
