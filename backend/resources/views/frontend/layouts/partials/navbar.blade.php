@php
    $isHome = request()->is('/');
    $isCatalog = request()->is('produk*') || request()->is('products*');
    $isCategory = request()->is('kategori*') || request()->is('categories*');
@endphp

<header x-data="{ mobileOpen: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 8 }, { passive: true })"
        @keydown.escape.window="mobileOpen = false"
        class="sticky top-0 z-50">
    <div class="relative z-30 border-b bg-[var(--surface)]/95 backdrop-blur-md transition-all duration-200"
         :class="scrolled || mobileOpen ? 'border-[var(--border)] shadow-[0_8px_30px_-24px_rgba(36,29,46,0.5)]' : 'border-transparent'">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:h-[76px] lg:gap-6 lg:px-8 lg:py-0">
            <!-- Left: Brand -->
            <div class="flex items-center gap-2.5 shrink-0">
                @include('frontend.layouts.partials.navbar.brand')
            </div>

            <!-- Center: Desktop nav + Search -->
            <div class="hidden flex-1 lg:flex lg:items-center lg:gap-6">
                @include('frontend.layouts.partials.navbar.desktop-nav', [
                    'isHome' => $isHome,
                    'isCatalog' => $isCatalog,
                    'isCategory' => $isCategory,
                ])

                @include('frontend.layouts.partials.navbar.search', [
                    'id' => 'navbar-search',
                    'formClass' => 'relative ml-auto min-w-0 flex-1 md:max-w-[17rem] lg:max-w-xs',
                ])
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-1.5 shrink-0">
                @include('frontend.layouts.partials.navbar.actions')
            </div>
        </div>
    </div>

    {{-- Dark overlay behind the slide-down mobile menu --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 bg-[#241d2e]/35 lg:hidden"></div>

    @include('frontend.layouts.partials.navbar.mobile-menu', [
        'isHome' => $isHome,
        'isCatalog' => $isCatalog,
        'isCategory' => $isCategory,
    ])
</header>
