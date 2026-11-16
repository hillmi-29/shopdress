<div class="flex items-center gap-1 sm:gap-1.5">
    {{-- Wishlist --}}
    <a href="{{ $wishlistUrl }}"
       class="group relative hidden h-10 w-10 items-center justify-center rounded-full text-[var(--text-muted)] transition-colors duration-200 hover:bg-[var(--brand-soft)] hover:text-[var(--brand)] focus:outline-none sm:inline-flex"
       aria-label="Wishlist ({{ $wishlistCount }} produk)">
        <svg class="h-[19px] w-[19px] transition-transform duration-200 ease-out group-hover:scale-105" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" />
        </svg>
        <span class="absolute right-0.5 top-0.5 inline-flex min-h-4 min-w-4 items-center justify-center rounded-full bg-[var(--brand)] px-1 text-[9px] font-bold leading-none text-white ring-2 ring-[var(--surface)]"
              x-show="$store.shop.wishlistCount > 0"
              @if ($wishlistCount === 0) style="display: none" @endif>
            <span x-text="$store.shop.wishlistCount > 99 ? '99+' : $store.shop.wishlistCount">{{ $wishlistCount > 99 ? '99+' : $wishlistCount }}</span>
        </span>
    </a>

    {{-- Cart --}}
    <button type="button"
            @click="$dispatch('open-mini-cart')"
            class="group relative inline-flex h-9 w-9 items-center justify-center rounded-full text-[var(--text-muted)] transition-colors duration-200 hover:bg-[var(--brand-soft)] hover:text-[var(--brand)] focus:outline-none sm:h-10 sm:w-10"
            aria-label="Buka keranjang belanja">
        <svg class="h-5 w-5 transition-transform duration-200 ease-out group-hover:scale-105" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l1 12H4L5 9Z" />
        </svg>
        <span class="absolute right-0.5 top-0.5 inline-flex min-h-4 min-w-4 items-center justify-center rounded-full bg-[var(--brand)] px-1 text-[9px] font-bold leading-none text-white ring-2 ring-[var(--surface)]"
              :class="{ 'badge-bounce': $store.shop.cartBouncing }"
              x-show="$store.shop.cartCount > 0"
              @if ($cartCount === 0) style="display: none" @endif>
            <span x-text="$store.shop.cartCount > 99 ? '99+' : $store.shop.cartCount">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
        </span>
    </button>

    {{-- Account --}}
    @auth
        @include('frontend.layouts.partials.navbar.user-menu')
    @else
        <a href="{{ $loginUrl }}"
           class="ml-1 hidden items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] py-2 pl-2.5 pr-4 text-[11px] font-bold text-[var(--text)] shadow-[0_1px_0_rgba(36,29,46,0.02)] transition-all duration-200 ease-out hover:border-[var(--brand)] hover:text-[var(--brand)] hover:shadow-sm lg:inline-flex">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </span>
            <span>Masuk</span>
        </a>

        <a href="{{ $registerUrl }}"
           class="hidden items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--surface)] py-2 pl-2.5 pr-4 text-[11px] font-bold text-[var(--text)] shadow-[0_1px_0_rgba(36,29,46,0.02)] transition-all duration-200 ease-out hover:border-[var(--brand)] hover:text-[var(--brand)] hover:shadow-sm xl:inline-flex">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 6.75a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0ZM4 19.1a6.5 6.5 0 0 1 13 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 8.5v5m2.5-2.5h-5" />
                </svg>
            </span>
            <span>Daftar</span>
        </a>

        <a href="{{ $loginUrl }}"
           class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)] transition-colors hover:bg-[var(--brand)] hover:text-white sm:h-10 sm:w-10 lg:hidden"
           aria-label="Masuk ke akun">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </a>
    @endauth

    {{-- Mobile menu toggle --}}
    <button type="button"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen.toString()"
            aria-controls="mobile-menu"
            class="inline-flex h-9 w-9 items-center justify-center rounded-full text-[var(--text)] transition-colors duration-200 hover:bg-[var(--surface-2)] focus:outline-none sm:h-10 sm:w-10 lg:hidden"
            aria-label="Menu navigasi">
        <svg x-show="!mobileOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h10" />
        </svg>
        <svg x-show="mobileOpen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
        </svg>
    </button>
</div>
