@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' => 'mb-4', 'aria-label' => 'Breadcrumb']) }}>
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500">
        <li>
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-1.5 rounded-md px-1 py-0.5 transition hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 11 9-8 9 8" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10v10h14V10" />
                </svg>
                <span>Beranda</span>
            </a>
        </li>

        @foreach ($items as $item)
            <li aria-hidden="true" class="text-gray-300">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                @if (! empty($item['url']))
                    <a href="{{ $item['url'] }}"
                       class="rounded-md px-1 py-0.5 transition hover:text-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-700">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span aria-current="page" class="font-medium text-gray-900">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

