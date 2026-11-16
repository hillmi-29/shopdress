@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'primary' => 'bg-primary-50 text-primary-700 ring-primary-100',
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-100',
        'danger' => 'bg-red-50 text-red-700 ring-red-100',
        'neutral' => 'bg-ink-100 text-ink-600 ring-ink-200',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '.($variants[$variant] ?? $variants['neutral']),
]) }}>
    {{ $slot }}
</span>

