<form method="GET" action="{{ $catalogUrl }}" class="{{ $formClass ?? 'relative hidden min-w-0 flex-1 md:block max-w-xs' }}" role="search">
    <label for="{{ $id ?? 'navbar-search' }}" class="sr-only">Cari busana</label>
    <div class="group relative">
        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--text-subtle)] transition-colors duration-200 group-focus-within:text-[var(--brand)]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M9 3.5A5.5 5.5 0 003.5 9c0 3.04 2.46 5.5 5.5 5.5 1.387 0 2.632-.516 3.573-1.362l3.394 3.395a.75.75 0 0 0 1.06-1.06l-3.394-3.395A5.5 5.5 0 009 3.5Z" clip-rule="evenodd" />
        </svg>
        <input type="search"
               id="{{ $id ?? 'navbar-search' }}"
               name="search"
               value="{{ request('search') }}"
               placeholder="Cari dress, gamis..."
               autocomplete="off"
               class="h-10 w-full rounded-full border border-[var(--border)] bg-[var(--surface-2)]/70 pl-10 pr-4 text-xs font-medium text-[var(--text)] placeholder:text-[var(--text-subtle)] transition-all duration-200 ease-out hover:border-[#d9cec1] focus:border-[var(--brand)] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[var(--brand-soft)]">
    </div>
</form>
