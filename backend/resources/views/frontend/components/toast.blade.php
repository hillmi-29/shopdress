@props([
    'type' => 'success',
    // $slot berisi isi pesan, boleh statis (session flash) maupun
    // dinamis (`<span x-text="toast.message"></span>` dari JS).
])

@php
    $styles = match ($type) {
        'error' => [
            'border' => 'border-red-200',
            'iconBg' => 'bg-red-50 text-red-600',
            'title' => 'Terjadi kesalahan',
            'role' => 'alert',
            'ariaLive' => 'assertive',
            'path' => 'M12 8v5m0 3h.01M10.3 4.4 3.6 16a2 2 0 0 0 1.7 3h13.4a2 2 0 0 0 1.7-3L13.7 4.4a2 2 0 0 0-3.4 0Z',
        ],
        default => [
            'border' => 'border-emerald-200',
            'iconBg' => 'bg-emerald-50 text-emerald-600',
            'title' => 'Berhasil',
            'role' => 'status',
            'ariaLive' => 'polite',
            'path' => 'm5 12 4 4L19 6',
        ],
    };
@endphp

<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 5000)"
     x-show="show"
     x-transition
     class="pointer-events-auto flex w-full items-start gap-3 rounded-xl border {{ $styles['border'] }} bg-white p-4 shadow-lg"
     role="{{ $styles['role'] }}"
     aria-live="{{ $styles['ariaLive'] }}">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $styles['iconBg'] }}">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $styles['path'] }}" />
        </svg>
    </span>

    <div class="min-w-0 flex-1">
        <p class="text-sm font-semibold text-gray-900">{{ $styles['title'] }}</p>
        {{ $slot }}
    </div>

    <button type="button"
            @click="show = false"
            class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-700"
            aria-label="Tutup notifikasi">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18" />
        </svg>
    </button>
</div>

