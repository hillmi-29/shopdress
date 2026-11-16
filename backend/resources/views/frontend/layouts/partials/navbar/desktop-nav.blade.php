@php
    $isHome = request()->is('/');
    $isCatalog = request()->is('produk*') || request()->is('products*');
    $isPromo = request()->is('promo*');
    $isAbout = request()->is('tentang-kami*') || request()->is('about*');

    $linkBase = 'group relative inline-flex items-center py-2 text-[13px] font-semibold tracking-tight transition-colors duration-200';
@endphp

<nav class="hidden items-center gap-1.5 lg:flex xl:gap-2.5" aria-label="Navigasi utama">
    {{-- Home --}}
    <a href="{{ $homeUrl }}"
       @class([$linkBase, 'text-[var(--brand)]' => $isHome, 'text-[var(--text-muted)] hover:text-[var(--text)]' => ! $isHome])>
        Home
        <span class="absolute inset-x-0 -bottom-0.5 h-[2px] origin-left rounded-full bg-[var(--brand)] transition-transform duration-200 ease-out {{ $isHome ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
    </a>

    {{-- Categories --}}
    @include('frontend.layouts.partials.navbar.category-dropdown')

    {{-- Products --}}
    <a href="{{ $catalogUrl }}"
       @class([$linkBase, 'text-[var(--brand)]' => $isCatalog, 'text-[var(--text-muted)] hover:text-[var(--text)]' => ! $isCatalog])>
        Produk
        <span class="absolute inset-x-0 -bottom-0.5 h-[2px] origin-left rounded-full bg-[var(--brand)] transition-transform duration-200 ease-out {{ $isCatalog ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
    </a>

    {{-- Promo pill --}}
    <a href="{{ $promoUrl }}"
       @class([
           'ml-1 inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.08em] transition-all duration-200 ease-out',
           'border-[var(--brand)] bg-[var(--brand)] text-white shadow-sm' => $isPromo,
           'border-[#e9d9b8] bg-[var(--accent-soft)] text-[#8a6420] hover:border-[var(--accent)] hover:bg-[#f7ecd3]' => ! $isPromo,
       ])>
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
        </svg>
        Promo
    </a>

    {{-- About --}}
    <a href="{{ $aboutUrl }}"
       @class([$linkBase, 'text-[var(--brand)]' => $isAbout, 'text-[var(--text-muted)] hover:text-[var(--text)]' => ! $isAbout])>
        Tentang Kami
        <span class="absolute inset-x-0 -bottom-0.5 h-[2px] origin-left rounded-full bg-[var(--brand)] transition-transform duration-200 ease-out {{ $isAbout ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
    </a>
</nav>
