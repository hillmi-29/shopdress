@php
    $linkClass = $linkClass ?? 'block px-4 py-2.5 text-sm text-gray-600 transition hover:bg-primary-50 hover:text-primary-700';
    $emptyClass = $emptyClass ?? 'px-4 py-2.5 text-sm text-gray-500';
@endphp

@forelse ($navCategories as $category)
    <a href="{{ \App\Support\FrontendUrls::category($category) }}"
       @if ($mobile ?? false) x-on:click="mobileOpen = false" @endif
       class="{{ $linkClass }}">
        {{ $category->name }}
    </a>
@empty
    <p class="{{ $emptyClass }}">Belum ada kategori.</p>
@endforelse

