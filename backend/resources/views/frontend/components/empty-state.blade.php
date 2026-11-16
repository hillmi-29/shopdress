@props([
    'title' => 'Belum ada data',
    'message' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-12 text-center shadow-sm sm:py-16']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary-700">
        @if (isset($icon))
            {{ $icon }}
        @else
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12l1 13H5L6 7Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a3 3 0 0 1 6 0v2" />
            </svg>
        @endif
    </div>

    <h2 class="mt-4 text-base font-semibold text-gray-900">{{ $title }}</h2>

    @if ($message)
        <p class="mt-1 max-w-md text-sm leading-6 text-gray-500">{{ $message }}</p>
    @endif

    @if (isset($action))
        <div class="mt-5">{{ $action }}</div>
    @elseif ($actionLabel && $actionUrl)
        <x-button :href="$actionUrl" variant="primary" class="mt-5">{{ $actionLabel }}</x-button>
    @endif
</div>

