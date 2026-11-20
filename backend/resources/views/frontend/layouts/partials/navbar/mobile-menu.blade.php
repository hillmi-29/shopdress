@php
    $isHome = request()->is('/');
    $isCatalog = request()->is('produk*') || request()->is('products*');
    $isPromo = request()->is('promo*');
    $isAbout = request()->is('tentang-kami*') || request()->is('about*');

    $mobileLink = 'flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition-colors duration-200';
@endphp

{{-- Menu mobile layar penuh: bar atas sendiri, isi bisa di-scroll, aksi akun menetap di bawah. --}}
<div x-show="mobileOpen"
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     x-cloak
     id="mobile-menu"
     class="fixed inset-0 z-[55] flex flex-col bg-[var(--surface)] lg:hidden"
     role="dialog"
     aria-modal="true"
     aria-label="Menu navigasi">

    {{-- Bar atas --}}
    <div class="flex shrink-0 items-center justify-between gap-3 border-b border-[var(--border)] px-4 py-3"
         style="padding-top: max(0.75rem, env(safe-area-inset-top))">
        @include('frontend.layouts.partials.navbar.brand')

        <button type="button"
                @click="mobileOpen = false"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] text-[var(--text)] transition hover:border-[var(--brand)]/40 hover:bg-[var(--brand-soft)] hover:text-[var(--brand)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)]/30"
                aria-label="Tutup menu navigasi">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    {{-- Isi menu (scroll) --}}
    <div class="flex-1 overflow-y-auto overscroll-contain px-4 pb-8 pt-4">
        {{-- Search --}}
        <div class="pb-4">
            @include('frontend.layouts.partials.navbar.search', [
                'id' => 'mobile-search',
                'formClass' => 'relative block w-full',
            ])
        </div>

        {{-- Main links --}}
        <nav class="space-y-1 border-t border-[var(--border-light)] pt-4" aria-label="Navigasi mobile">
            <a href="{{ $homeUrl }}"
               @click="mobileOpen = false"
               @class([$mobileLink, 'bg-[var(--brand-soft)] text-[var(--brand)]' => $isHome, 'text-[var(--text)] hover:bg-[var(--surface-2)]' => ! $isHome])>
                <span>Home</span>
                @if ($isHome)
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand)]"></span>
                @endif
            </a>

            <a href="{{ $catalogUrl }}"
               @click="mobileOpen = false"
               @class([$mobileLink, 'bg-[var(--brand-soft)] text-[var(--brand)]' => $isCatalog, 'text-[var(--text)] hover:bg-[var(--surface-2)]' => ! $isCatalog])>
                <span>Produk</span>
                @if ($isCatalog)
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand)]"></span>
                @endif
            </a>

            <a href="{{ $promoUrl }}"
               @click="mobileOpen = false"
               @class([$mobileLink, 'bg-[var(--accent-soft)] text-[#8a6420]' => $isPromo, 'text-[#8a6420] hover:bg-[var(--accent-soft)]' => ! $isPromo])>
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                    </svg>
                    Promo & Diskon
                </span>
                <span class="rounded-full bg-white/70 px-2 py-0.5 text-[9px] font-black uppercase tracking-wider">Hemat</span>
            </a>

            <a href="{{ $aboutUrl }}"
               @click="mobileOpen = false"
               @class([$mobileLink, 'bg-[var(--brand-soft)] text-[var(--brand)]' => $isAbout, 'text-[var(--text)] hover:bg-[var(--surface-2)]' => ! $isAbout])>
                <span>Tentang Kami</span>
                @if ($isAbout)
                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand)]"></span>
                @endif
            </a>

            <a href="{{ $wishlistUrl }}"
               @click="mobileOpen = false"
               class="{{ $mobileLink }} text-[var(--text-muted)] hover:bg-[var(--surface-2)] hover:text-[var(--text)]">
                <span>Wishlist</span>
                <span class="rounded-full bg-[var(--brand-soft)] px-2 py-0.5 text-[10px] font-bold text-[var(--brand)]">{{ $wishlistCount }}</span>
            </a>
        </nav>

        {{-- Categories --}}
        @if ($navCategories->isNotEmpty())
            <div class="mt-5 border-t border-[var(--border-light)] pt-4">
                <p class="px-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--text-subtle)]">Kategori pilihan</p>
                <div class="mt-2.5 grid grid-cols-2 gap-2">
                    @foreach ($navCategories->take(6) as $cat)
                        <a href="{{ \App\Support\FrontendUrls::category($cat) }}"
                           @click="mobileOpen = false"
                           class="group flex min-h-11 items-center justify-between gap-2 rounded-2xl border border-[var(--border)] bg-[var(--surface-2)]/50 px-3 py-2.5 text-[11px] font-semibold text-[var(--text)] transition hover:border-[var(--brand)]/50 hover:bg-[var(--brand-soft)] hover:text-[var(--brand)] {{ $loop->last && $loop->count % 2 === 1 ? 'col-span-2' : '' }}">
                            <span class="truncate">{{ $cat->name }}</span>
                            <svg class="h-3 w-3 shrink-0 text-[var(--text-subtle)] transition-all duration-200 group-hover:translate-x-0.5 group-hover:text-[var(--brand)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endforeach
                </div>

                <a href="{{ \App\Support\FrontendUrls::categoryIndex() }}"
                   @click="mobileOpen = false"
                   class="mt-2.5 flex min-h-11 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand-soft)] px-4 py-2.5 text-[11px] font-bold text-[var(--brand)] transition hover:bg-[var(--brand)] hover:text-white">
                    Lihat semua kategori
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        @endif
    </div>

    {{-- Aksi akun (menetap di bawah) --}}
    <div class="shrink-0 border-t border-[var(--border)] bg-[var(--surface)] px-4 pt-3 shadow-[0_-14px_30px_-26px_rgba(36,29,46,0.45)]"
         style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom))">
        @auth
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-sm font-bold text-[var(--brand)]">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-[var(--text)]">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[11px] text-[var(--text-muted)]">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ $logoutUrl }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-[var(--border)] px-3 py-1.5 text-[11px] font-semibold text-[var(--error)] transition hover:bg-[var(--error-soft)]">
                        Keluar
                    </button>
                </form>
            </div>
        @else
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ $loginUrl }}"
                   @click="mobileOpen = false"
                   class="flex items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] py-3 text-xs font-bold text-[var(--text)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                    Masuk
                </a>
                <a href="{{ $registerUrl }}"
                   @click="mobileOpen = false"
                   class="flex items-center justify-center rounded-full bg-[var(--brand)] py-3 text-xs font-bold text-white shadow-sm transition hover:bg-[var(--brand-dark)]">
                    Daftar Akun
                </a>
            </div>
        @endauth
    </div>
</div>
