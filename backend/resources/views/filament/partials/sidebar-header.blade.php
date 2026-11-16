@php
    $sidebarBrandName = filament()->getBrandName() ?: config('app.name');
    $sidebarHomeUrl = filament()->getHomeUrl() ?? url('/admin');
@endphp

<div class="sd-sidebar-top">
    <div class="sd-sidebar-brand-row">
        <a href="{{ $sidebarHomeUrl }}" class="sd-sidebar-brand" aria-label="{{ $sidebarBrandName }}">
            <span class="sd-sidebar-brand-mark" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </span>
            <span class="sd-sidebar-brand-name">{{ $sidebarBrandName }}</span>
        </a>

        <button
            type="button"
            class="sd-sidebar-collapse"
            x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
            x-bind:aria-label="$store.sidebar.isOpen ? 'Ciutkan sidebar' : 'Buka sidebar'"
            x-bind:title="$store.sidebar.isOpen ? 'Ciutkan sidebar' : 'Buka sidebar'"
        >
            <svg x-cloak x-show="$store.sidebar.isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <svg x-cloak x-show="! $store.sidebar.isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>
</div>
