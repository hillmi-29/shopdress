@props([
    'paginator',
])

@if ($paginator->hasPages())
    @php
        $pageLink = 'inline-flex h-9 min-w-9 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-2 text-[11px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:bg-[var(--brand-soft)] hover:text-[var(--brand)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)]';
        $activePageLink = 'inline-flex h-9 min-w-9 items-center justify-center rounded-full border border-[var(--brand)] bg-[var(--brand)] px-2 text-[11px] font-bold text-white shadow-sm';
        $disabledPageLink = 'inline-flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-2 text-[11px] font-bold text-[var(--text-subtle)] opacity-50';
    @endphp

    <nav {{ $attributes->merge(['class' => 'mt-8 flex items-center justify-between gap-3', 'role' => 'navigation', 'aria-label' => 'Navigasi halaman']) }}>
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="{{ $disabledPageLink }}">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $pageLink }}">Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $pageLink }}">Berikutnya</a>
            @else
                <span class="{{ $disabledPageLink }}">Berikutnya</span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-[11px] text-[var(--text-subtle)]">
                @if (method_exists($paginator, 'firstItem') && $paginator->firstItem())
                    Menampilkan
                    <span class="font-bold text-[var(--text)]">{{ $paginator->firstItem() }}</span>
                    –
                    <span class="font-bold text-[var(--text)]">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-[var(--text)]">{{ $paginator->total() }}</span>
                    hasil
                @else
                    Halaman <span class="font-bold text-[var(--text)]">{{ $paginator->currentPage() }}</span>
                @endif
            </p>

            <div class="flex items-center gap-1.5">
                @if ($paginator->onFirstPage())
                    <span class="{{ $disabledPageLink }}" aria-disabled="true" aria-label="Halaman sebelumnya">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $pageLink }}" aria-label="Halaman sebelumnya">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.83 10l3.94 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="{{ $activePageLink }}" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="{{ $pageLink }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $pageLink }}" aria-label="Halaman berikutnya">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="{{ $disabledPageLink }}" aria-disabled="true" aria-label="Halaman berikutnya">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.17 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif

