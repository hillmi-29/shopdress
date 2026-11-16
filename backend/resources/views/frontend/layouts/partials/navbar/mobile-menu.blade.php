@php
    $isHome = request()->is('/');
    $isCatalog = request()->is('produk*') || request()->is('products*');
    $isPromo = request()->is('promo*');
    $isAbout = request()->is('tentang-kami*') || request()->is('about*');

    $mobileLink = 'flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold transition-colors duration-200';
@endphp

<div x-show="mobileOpen"
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="opacity-0 -translate-y-3"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-3"
     x-cloak
     id="mobile-menu"
     class="relative z-40 max-h-[calc(100vh-4rem)] overflow-y-auto border-b border-[var(--border)] bg-white px-4 pb-7 pt-4 shadow-[0_24px_40px_-30px_rgba(36,29,46,0.35)] lg:hidden">

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
            <div class="mt-2.5 flex flex-wrap gap-1.5">
                @foreach ($navCategories->take(6) as $cat)
                    <a href="{{ \App\Support\FrontendUrls::category($cat) }}"
                       @click="mobileOpen = false"
                       class="rounded-full border border-[var(--border)] bg-[var(--surface-2)]/60 px-3 py-1.5 text-[11px] font-semibold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:bg-[var(--brand-soft)] hover:text-[var(--brand)]">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Account --}}
    <div class="mt-5 border-t border-[var(--border-light)] pt-4">
        @auth
            <div class="flex items-center gap-3 px-1">
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
                   class="flex items-center justify-center rounded-full border border-[var(--border)] bg-white py-3 text-xs font-bold text-[var(--text)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
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
