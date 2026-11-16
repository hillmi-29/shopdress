@if (! filament()->isGlobalSearchEnabled())
    @php
        $searchItems = collect();

        foreach (filament()->getNavigation() as $navigationGroup) {
            $groupLabel = (string) ($navigationGroup->getLabel() ?? '');

            foreach ($navigationGroup->getItems() as $navigationItem) {
                $items = collect([$navigationItem])
                    ->merge($navigationItem->getChildItems());

                foreach ($items as $item) {
                    $url = $item->getUrl();

                    if (blank($url)) {
                        continue;
                    }

                    $searchItems->push([
                        'group' => $groupLabel,
                        'icon' => $item->getIcon(),
                        'label' => (string) $item->getLabel(),
                        'newTab' => $item->shouldOpenUrlInNewTab(),
                        'url' => $url,
                    ]);
                }
            }
        }
    @endphp

    <div x-data="{ query: '' }" class="sd-topbar-search">
        <x-filament::dropdown placement="bottom-end" teleport>
            <x-slot name="trigger">
                <button
                    type="button"
                    class="sd-topbar-icon-btn"
                    aria-label="Cari menu"
                    title="Cari menu"
                    x-on:click="$nextTick(() => document.getElementById('sd-topbar-menu-search')?.focus())"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />
                    </svg>
                </button>
            </x-slot>

            <div class="sd-topbar-search-panel">
                <div class="sd-topbar-search-field">
                    <svg class="sd-topbar-search-field-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />
                    </svg>

                    <input
                        id="sd-topbar-menu-search"
                        type="search"
                        class="sd-topbar-search-input"
                        placeholder="Cari menu..."
                        autocomplete="off"
                        x-model="query"
                    />
                </div>

                <div class="sd-topbar-search-results" role="list">
                    @foreach ($searchItems as $searchItem)
                        <a
                            href="{{ $searchItem['url'] }}"
                            class="sd-topbar-search-result"
                            role="listitem"
                            data-search="{{ Str::lower($searchItem['label'] . ' ' . $searchItem['group']) }}"
                            x-show="query === '' || $el.dataset.search.includes(query.toLowerCase())"
                            @if ($searchItem['newTab']) target="_blank" rel="noopener noreferrer" @endif
                        >
                            @if (is_string($searchItem['icon']))
                                <x-filament::icon :icon="$searchItem['icon']" class="sd-topbar-search-result-icon" />
                            @endif

                            <span class="sd-topbar-search-result-label">{{ $searchItem['label'] }}</span>
                            <span class="sd-topbar-search-result-group">{{ $searchItem['group'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-filament::dropdown>
    </div>
@endif
