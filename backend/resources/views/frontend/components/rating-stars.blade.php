@props([
    'rating' => 0,
    'size' => 'h-4 w-4',
])

@php
    $rating = max(0, min(5, (float) $rating));
    $filled = (int) round($rating);
@endphp

<div class="inline-flex items-center gap-0.5"
     role="img"
     aria-label="Rating {{ number_format($rating, 1, ',', '.') }} dari 5">
    @for ($i = 1; $i <= 5; $i++)
        <svg class="{{ $size }} {{ $i <= $filled ? 'text-amber-400' : 'text-gray-200' }}"
             viewBox="0 0 20 20"
             fill="currentColor"
             aria-hidden="true">
            <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.3 4.01a1 1 0 0 0 .95.69h4.22c.97 0 1.37 1.24.59 1.81l-3.42 2.48a1 1 0 0 0-.36 1.12l1.3 4.01c.3.92-.75 1.69-1.54 1.12l-3.42-2.48a1 1 0 0 0-1.18 0l-3.42 2.48c-.78.57-1.83-.2-1.53-1.12l1.3-4.01a1 1 0 0 0-.36-1.12L1.99 9.44c-.78-.57-.38-1.81.59-1.81h4.22a1 1 0 0 0 .95-.69l1.3-4.01Z" />
        </svg>
    @endfor
</div>
