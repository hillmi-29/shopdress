{{-- Floating WhatsApp help button --}}
@php
    $siteSettings = $siteSettings ?? \App\Models\SiteSetting::values();
    $whatsappUrl = $siteSettings['whatsapp_product_url'] ?? null;
@endphp

@if (filled($whatsappUrl))
    <div class="fixed bottom-5 right-5 z-40 sm:bottom-6 sm:right-6" x-data="{ label: false }">
        <a href="{{ $whatsappUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           @mouseenter="label = true"
           @mouseleave="label = false"
           @focus="label = true"
           @blur="label = false"
           aria-label="Hubungi {{ \App\Models\SiteSetting::storeName() }} via WhatsApp"
           class="group flex items-center gap-0 rounded-full bg-[#1f9d64] py-2 pl-2 pr-2 text-white shadow-[0_10px_30px_-10px_rgba(31,157,100,0.7)] transition-all duration-300 ease-out hover:bg-[#178453] hover:shadow-[0_14px_34px_-12px_rgba(31,157,100,0.8)] focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-300/50 sm:hover:pr-5">
            <span class="relative flex h-10 w-10 items-center justify-center">
                <span class="absolute inset-0 rounded-full bg-white/15 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2m4.52 13.53c-.25-.13-1.47-.72-1.7-.81-.23-.09-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.44.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.21-.18-.46-.31"/>
                </svg>
            </span>

            <span class="max-w-0 overflow-hidden whitespace-nowrap text-xs font-bold opacity-0 transition-all duration-300 ease-out group-hover:max-w-40 group-hover:opacity-100 group-focus:max-w-40 group-focus:opacity-100 sm:pr-1"
                  aria-hidden="true">
                Butuh bantuan?
            </span>
        </a>
    </div>
@endif
