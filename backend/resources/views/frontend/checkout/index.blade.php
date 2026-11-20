@extends('frontend.layouts.app')

@section('title', 'Checkout — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Lengkapi alamat pengiriman dan selesaikan pembayaran pesanan '.\App\Models\SiteSetting::storeName().' Anda dengan aman.')

@section('content')
    @php
        $cartUrl = route('cart.index');
        $promoUrl = \App\Support\FrontendUrls::promo();
        $helpUrl = \App\Support\FrontendUrls::help();
        $user = auth()->user();
        $itemCount = $cart->total_quantity;
        $featuredCoupon = $featuredCoupon ?? null;
        $checkoutMode = $checkoutMode ?? 'cart';
        $isBuyNow = $checkoutMode === 'buy_now';
        $buyNowProduct = $isBuyNow ? $cart->items->first()?->variant?->product : null;
        $buyNowProductUrl = $buyNowProduct ? \App\Support\FrontendUrls::product($buyNowProduct) : $cartUrl;
    @endphp

    <nav class="mb-5 flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
        <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="{{ $isBuyNow ? $buyNowProductUrl : $cartUrl }}" class="transition hover:text-[var(--brand)]">{{ $isBuyNow ? 'Produk' : 'Keranjang' }}</a>
        <span aria-hidden="true">/</span>
        <span class="text-[var(--text)]" aria-current="page">Checkout</span>
    </nav>

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 right-1/3 h-32 w-32 rounded-full bg-[var(--accent-soft)]/80 blur-2xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Checkout aman</p>
                <h1 class="mt-2 font-display text-[2.4rem] font-semibold leading-none tracking-[-0.045em] text-[var(--brand)] sm:text-5xl">
                    Selesaikan pesananmu
                </h1>
                <p class="mt-3 text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Tinggal satu langkah lagi. Periksa pesanan, lengkapi alamat, lalu lanjutkan ke pembayaran yang aman.
                </p>
            </div>

            <ol class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-[0.1em] sm:gap-2" aria-label="Tahapan checkout">
                <li class="flex items-center gap-1.5 rounded-full bg-[var(--success-soft)] px-2.5 py-1.5 text-[var(--success)]">
                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    {{ $isBuyNow ? 'Produk' : 'Keranjang' }}
                </li>
                <li class="h-px w-4 bg-[var(--border)] sm:w-6" aria-hidden="true"></li>
                <li class="rounded-full bg-[var(--brand)] px-2.5 py-1.5 text-white" aria-current="step">Checkout</li>
                <li class="h-px w-4 bg-[var(--border)] sm:w-6" aria-hidden="true"></li>
                <li class="rounded-full border border-[var(--border)] px-2.5 py-1.5 text-[var(--text-subtle)]">Pembayaran</li>
            </ol>
        </div>
    </header>

    @if (session('error'))
        <div class="mt-5 flex items-start gap-3 rounded-[1.25rem] border border-[var(--error)]/20 bg-[var(--error-soft)] p-4 text-xs font-medium text-[var(--error)]" role="alert">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10A8 8 0 1 1 2 10a8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST"
          action="{{ route('checkout.process') }}"
          x-data="checkoutForm()"
          @submit="submitting = true"
          enctype="multipart/form-data"
          class="mt-6 grid items-start gap-6 lg:grid-cols-[1.4fr_0.72fr] lg:gap-8">
        @csrf
        <input type="hidden" name="checkout_mode" value="{{ $checkoutMode }}">

        <div class="space-y-5">
            {{-- Order card --}}
            <section class="reveal overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
                <div class="flex items-center justify-between gap-4 border-b border-[var(--border-light)] px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Langkah 1</p>
                        <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Pesananmu</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-[var(--surface-2)] px-3 py-1.5 text-[10px] font-bold text-[var(--text-muted)]">{{ $itemCount }} item</span>
                        <a href="{{ $isBuyNow ? $buyNowProductUrl : $cartUrl }}" class="text-[10px] font-bold text-[var(--brand)] underline-offset-4 transition hover:underline">Ubah</a>
                    </div>
                </div>

                <div class="divide-y divide-[var(--border-light)] px-3 sm:px-4">
                    @foreach ($cart->items as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant->product;
                            $imagePath = $product->primaryImage?->image_path;
                            $imageUrl = $imagePath
                                ? (str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/'.ltrim($imagePath, '/')))
                                : null;
                            $unitPrice = number_format((float) $variant->final_price, 0, ',', '.');
                            $subtotal = number_format((float) ($variant->final_price * $item->quantity), 0, ',', '.');
                        @endphp

                        <div class="flex gap-3 py-4 sm:gap-4" x-data="{ itemQty: {{ $item->quantity }} }">
                            <a href="{{ \App\Support\FrontendUrls::product($product) }}"
                               class="group relative h-[6.75rem] w-[5.1rem] shrink-0 overflow-hidden rounded-xl bg-[var(--surface-2)]">
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[var(--brand)]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-7 w-7 opacity-40" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a2 2 0 1 0-2-2m2 2v2m0 0-7.5 5.25A2 2 0 0 0 5.6 18h12.8a2 2 0 0 0 1.1-3.75L12 9Z" />
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-bold uppercase tracking-[0.12em] text-[var(--text-subtle)]">{{ $product->category?->name ?? 'Dress Modern' }}</p>
                                        <h3 class="mt-1 line-clamp-2 text-[13px] font-semibold leading-snug text-[var(--text)]">
                                            <a href="{{ \App\Support\FrontendUrls::product($product) }}" class="transition hover:text-[var(--brand)]">{{ $product->name }}</a>
                                        </h3>
                                        <p class="mt-2 flex flex-wrap items-center gap-1.5 text-[9px] font-semibold text-[var(--text-muted)]">
                                            <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">{{ $variant->color ?? '-' }}</span>
                                            <span class="rounded-full bg-[var(--surface-2)] px-2 py-1">Size {{ $variant->size ?? '-' }}</span>
                                        </p>
                                    </div>

                                    <button type="button"
                                            @click="deleteCheckoutItem({{ $item->id ?? 'null' }})"
                                            class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--error-soft)] hover:text-[var(--error)]"
                                            aria-label="Hapus {{ $product->name }} dari pesanan">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m-9 0 1 12h8l1-12M10 11v4m4-4v4" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-auto flex flex-wrap items-end justify-between gap-2 pt-3">
                                    <div class="inline-flex h-8 items-center rounded-full border border-[var(--border)]">
                                        <button type="button"
                                                @click="updateCheckoutItem({{ $item->id ?? 'null' }}, Math.max(1, itemQty - 1))"
                                                :disabled="itemQty <= 1"
                                                class="flex h-full w-8 items-center justify-center rounded-l-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)] disabled:cursor-not-allowed disabled:opacity-30"
                                                aria-label="Kurangi jumlah">−</button>
                                        <span class="w-7 text-center text-[11px] font-bold text-[var(--text)]" x-text="itemQty">{{ $item->quantity }}</span>
                                        <button type="button"
                                                @click="updateCheckoutItem({{ $item->id ?? 'null' }}, itemQty + 1)"
                                                class="flex h-full w-8 items-center justify-center rounded-r-full text-[var(--text-muted)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)]"
                                                aria-label="Tambah jumlah">+</button>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-[9px] text-[var(--text-subtle)]">Rp {{ $unitPrice }} × <span x-text="itemQty">{{ $item->quantity }}</span></p>
                                        <p class="mt-0.5 text-sm font-extrabold tracking-tight text-[var(--brand-dark)]">Rp {{ $subtotal }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Shipping address --}}
            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-6" style="--reveal-delay: 70ms">
                <div class="flex items-start justify-between gap-4 border-b border-[var(--border-light)] pb-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Langkah 2</p>
                        <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Alamat Pengiriman</h2>
                    </div>
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </span>
                </div>

                @if ($savedAddresses->isNotEmpty())
                    <div class="mt-5 rounded-2xl border border-[var(--border-light)] bg-[var(--bg)]/70 p-3.5 sm:p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-[10px] font-bold text-[var(--text)]">Alamat tersimpan</p>
                            <a href="{{ route('profile.edit') }}" class="text-[9px] font-bold text-[var(--brand)] underline-offset-4 transition hover:underline">Kelola alamat</a>
                        </div>
                        <div class="mt-3 grid gap-2 sm:grid-cols-2">
                            @foreach ($savedAddresses as $savedAddress)
                                <button type="button"
                                        @click="applySavedAddress({{ $savedAddress->id }})"
                                        class="rounded-xl border p-3 text-left transition"
                                        :class="selectedAddressId === {{ $savedAddress->id }}
                                            ? 'border-[var(--brand)] bg-[var(--brand-soft)]/40 ring-2 ring-[var(--brand)]/15'
                                            : 'border-[var(--border)] bg-[var(--surface)] hover:border-[var(--brand)]/50'">
                                    <span class="flex items-start justify-between gap-2">
                                        <span class="min-w-0">
                                            <span class="block text-[9px] font-black uppercase tracking-[0.12em] text-[var(--text-subtle)]">{{ $savedAddress->label ?: 'Alamat' }}</span>
                                            <span class="mt-1 block truncate text-[11px] font-bold text-[var(--text)]">{{ $savedAddress->recipient_name }}</span>
                                            <span class="block text-[9px] text-[var(--text-muted)]">{{ $savedAddress->recipient_phone }}</span>
                                        </span>
                                        @if ($savedAddress->is_default)
                                            <span class="shrink-0 rounded-full bg-[var(--brand)] px-2 py-0.5 text-[8px] font-bold uppercase tracking-[0.08em] text-white">Utama</span>
                                        @endif
                                    </span>
                                    <span class="mt-2 block text-[9px] leading-4 text-[var(--text-muted)]">{{ $savedAddress->full_address }}</span>
                                    @if (!empty($savedAddress->landmark_photos))
                                        <span class="mt-2 inline-flex items-center gap-1 rounded-md bg-[var(--brand-soft)] px-1.5 py-0.5 text-[8px] font-bold text-[var(--brand-dark)]">
                                            <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                            {{ count($savedAddress->landmark_photos) }} foto patokan terlampir
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        <p class="mt-2 text-[9px] leading-4 text-[var(--text-subtle)]">Pilih alamat untuk mengisi formulir secara otomatis, lalu lengkapi jika diperlukan.</p>
                    </div>
                @endif

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="recipient_name" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nama Penerima <span class="text-[var(--error)]">*</span></label>
                        <input type="text"
                               id="recipient_name"
                               name="recipient_name"
                               x-model="form.recipient_name"
                               required
                               autocomplete="name"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                    </div>

                    <div>
                        <label for="recipient_phone" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nomor Telepon / WA <span class="text-[var(--error)]">*</span></label>
                        <input type="tel"
                               id="recipient_phone"
                               name="recipient_phone"
                               x-model="form.recipient_phone"
                               required
                               inputmode="tel"
                               autocomplete="tel"
                               placeholder="08xxxxxxxxxx"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs font-medium text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                    </div>

                    {{-- Provinsi --}}
                    <div>
                        <label for="region_province" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Provinsi <span class="text-[var(--error)]">*</span>
                        </label>
                        <div class="relative">
                            <select id="region_province"
                                    x-model="address.province_code"
                                    @change="onProvinceChange()"
                                    required
                                    :disabled="loadingProvinces"
                                    class="h-11 w-full appearance-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-10 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15 disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="">Pilih Provinsi</option>
                                <template x-for="p in provinces" :key="p.code">
                                    <option :value="p.code" x-text="p.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg x-show="!loadingProvinces" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                <svg x-show="loadingProvinces" x-cloak class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Kota / Kabupaten --}}
                    <div>
                        <label for="region_regency" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Kota / Kabupaten <span class="text-[var(--error)]">*</span>
                        </label>
                        <div class="relative">
                            <select id="region_regency"
                                    x-model="address.regency_code"
                                    @change="onRegencyChange()"
                                    required
                                    :disabled="!address.province_code || loadingRegencies"
                                    class="h-11 w-full appearance-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-10 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15 disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="" x-text="!address.province_code ? 'Pilih provinsi dahulu' : (loadingRegencies ? 'Memuat kota/kabupaten…' : 'Pilih Kota / Kabupaten')"></option>
                                <template x-for="r in regencies" :key="r.code">
                                    <option :value="r.code" x-text="r.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg x-show="!loadingRegencies" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                <svg x-show="loadingRegencies" x-cloak class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label for="region_district" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Kecamatan <span class="text-[var(--error)]">*</span>
                        </label>
                        <div class="relative">
                            <select id="region_district"
                                    x-model="address.district_code"
                                    @change="onDistrictChange()"
                                    required
                                    :disabled="!address.regency_code || loadingDistricts"
                                    class="h-11 w-full appearance-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-10 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15 disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="" x-text="!address.regency_code ? 'Pilih kota/kabupaten dahulu' : (loadingDistricts ? 'Memuat kecamatan…' : 'Pilih Kecamatan')"></option>
                                <template x-for="d in districts" :key="d.code">
                                    <option :value="d.code" x-text="d.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg x-show="!loadingDistricts" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                <svg x-show="loadingDistricts" x-cloak class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                        </div>
                    </div>

                    {{-- Desa / Kelurahan --}}
                    <div>
                        <label for="region_village" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Kelurahan / Desa <span class="text-[var(--error)]">*</span>
                        </label>
                        <div class="relative">
                            <select id="region_village"
                                    x-model="address.village_code"
                                    @change="onVillageChange()"
                                    required
                                    :disabled="!address.district_code || loadingVillages"
                                    class="h-11 w-full appearance-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-10 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15 disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="" x-text="!address.district_code ? 'Pilih kecamatan dahulu' : (loadingVillages ? 'Memuat kelurahan/desa…' : 'Pilih Kelurahan / Desa')"></option>
                                <template x-for="v in villages" :key="v.code">
                                    <option :value="v.code" x-text="v.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg x-show="!loadingVillages" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                <svg x-show="loadingVillages" x-cloak class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                        </div>
                    </div>

                    {{-- RT / RW --}}
                    <div>
                        <label class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            RT / RW <span class="font-normal text-[var(--text-subtle)]">(opsional)</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <input type="text"
                                       id="region_rt"
                                       x-model="address.rt"
                                       @input="updateShippingAddress()"
                                       placeholder="RT (cth: 002)"
                                       maxlength="6"
                                       class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-3 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                            </div>
                            <div>
                                <input type="text"
                                       id="region_rw"
                                       x-model="address.rw"
                                       @input="updateShippingAddress()"
                                       placeholder="RW (cth: 005)"
                                       maxlength="6"
                                       class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-3 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                            </div>
                        </div>
                    </div>

                    {{-- Kode Pos --}}
                    <div>
                        <label for="region_postal_code" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Kode Pos <span class="text-[var(--error)]">*</span>
                        </label>
                        <input type="text"
                               id="region_postal_code"
                               x-model="address.postal_code"
                               @input="updateShippingAddress()"
                               required
                               placeholder="Terisi otomatis dari Desa / Kelurahan"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs font-medium text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                    </div>

                    {{-- Detail Jalan, No. Rumah, Patokan --}}
                    <div class="sm:col-span-2">
                        <label for="region_street" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">
                            Detail Jalan, Nomor Rumah & Patokan <span class="text-[var(--error)]">*</span>
                        </label>
                        <input type="text"
                               id="region_street"
                               x-model="address.street"
                               @input="updateShippingAddress()"
                               required
                               autocomplete="street-address"
                               placeholder="Nama jalan, nomor rumah, blok, atau patokan (cth: Jl. Melati No. 12, samping minimarket)"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs font-medium text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                    </div>

                    {{-- Hidden shipping address string submitted to backend --}}
                    <input type="hidden" name="shipping_address" :value="form.shipping_address" required>

                    {{-- Structured address fields, used when saving this address to the profile --}}
                    <input type="hidden" name="province_code" :value="address.province_code">
                    <input type="hidden" name="province_name" :value="address.province_name">
                    <input type="hidden" name="regency_code" :value="address.regency_code">
                    <input type="hidden" name="regency_name" :value="address.regency_name">
                    <input type="hidden" name="district_code" :value="address.district_code">
                    <input type="hidden" name="district_name" :value="address.district_name">
                    <input type="hidden" name="village_code" :value="address.village_code">
                    <input type="hidden" name="village_name" :value="address.village_name">
                    <input type="hidden" name="postal_code" :value="address.postal_code">
                    <input type="hidden" name="rt" :value="address.rt">
                    <input type="hidden" name="rw" :value="address.rw">
                    <input type="hidden" name="street" :value="address.street">

                    {{-- Upload Foto Patokan (Opsional) --}}
                    <div class="sm:col-span-2">
                        <label for="landmark_photo" class="mb-1.5 flex items-center justify-between text-[11px] font-bold text-[var(--text)]">
                            <span>Foto Patokan Lokasi <span class="font-normal text-[var(--text-subtle)]">(opsional, maks. 2 foto)</span></span>
                            <span class="text-[9px] font-normal text-[var(--text-subtle)]">Maks. 2MB/foto (JPG, PNG, WebP)</span>
                        </label>
                        <input type="hidden" name="saved_landmark_photo" :value="JSON.stringify(landmarkPhotos.filter(p => p.type === 'saved').map(p => p.path))">
                        <div class="flex flex-wrap items-center gap-3">
                            <label x-show="landmarkPhotos.length < 2"
                                   class="flex h-11 cursor-pointer items-center gap-2 rounded-xl border border-dashed border-[var(--border)] bg-[var(--bg)] px-4 text-xs font-medium text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                                <span x-text="landmarkPhotos.length === 0 ? 'Pilih Foto Patokan (Maks. 2)' : 'Tambah Foto Patokan (' + landmarkPhotos.length + '/2)'"></span>
                                <input type="file"
                                       id="landmark_photo"
                                       name="landmark_photo[]"
                                       accept="image/jpeg,image/png,image/webp,image/jpg"
                                       multiple
                                       class="hidden"
                                       @change="onLandmarkChange($event)">
                            </label>

                            <template x-for="(photo, index) in landmarkPhotos" :key="photo.url || index">
                                <div class="flex items-center gap-2 rounded-xl border border-[var(--border-light)] bg-[var(--surface-2)]/60 px-3 py-1.5">
                                    <img :src="photo.url" alt="Preview Patokan" class="h-8 w-8 rounded-lg object-cover border border-[var(--border)]">
                                    <span class="max-w-[150px] truncate text-[11px] font-medium text-[var(--text)]" x-text="photo.name || ('Foto ' + (index + 1))"></span>
                                    <button type="button"
                                            @click="removeLandmark(index)"
                                            class="ml-1 text-[10px] font-bold text-[var(--error)] transition hover:underline">
                                        Hapus
                                    </button>
                                </div>
                            </template>
                        </div>
                        <p class="mt-1 text-[9px] text-[var(--brand)] font-medium" x-show="landmarkPhotos.some(p => p.type === 'saved')">
                            Foto patokan otomatis terisi dari alamat tersimpan. Anda dapat menambah, mengganti, atau menghapusnya jika perlu.
                        </p>
                        <p class="mt-1 text-[9px] text-[var(--text-subtle)]" x-show="! landmarkPhotos.some(p => p.type === 'saved')">
                            Unggah hingga 2 foto pagar, rumah, ruko, atau patokan visual terdekat untuk memudahkan kurir mengantar pesanan.
                        </p>
                    </div>

                    <label class="flex cursor-pointer items-start gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--bg)] p-3.5 sm:col-span-2">
                        <input type="checkbox"
                               name="save_address"
                               value="1"
                               x-model="form.save_address"
                               class="mt-0.5 h-4 w-4 rounded border-[var(--border)] text-[var(--brand)] focus:ring-[var(--brand)]/20">
                        <span>
                            <span class="block text-[11px] font-bold text-[var(--text)]">Simpan alamat ini untuk checkout berikutnya</span>
                            <span class="mt-0.5 block text-[9px] leading-4 text-[var(--text-muted)]">Alamat bisa diubah atau dihapus kapan saja di halaman profil.</span>
                        </span>
                    </label>

                    <div class="sm:col-span-2">
                        <label for="notes" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Catatan Khusus <span class="font-medium text-[var(--text-subtle)]">(opsional)</span></label>
                        <input type="text"
                               id="notes"
                               name="notes"
                               x-model="form.notes"
                               class="h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15"
                               placeholder="Contoh: titipkan di satpam, bungkus kado, dll.">
                    </div>
                </div>
            </section>
        </div>

        {{-- Sticky summary --}}
        <aside class="reveal lg:sticky lg:top-24" style="--reveal-delay: 120ms">
            <div class="overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]">
                <div class="flex items-center justify-between border-b border-[var(--border-light)] px-5 py-4">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Langkah 3</p>
                        <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Ringkasan</h2>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--surface-2)] text-[var(--text-muted)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                        </svg>
                    </span>
                </div>

                <div class="space-y-5 p-5">
                    {{-- Coupon --}}
                    <div>
                        <label for="coupon_input" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Voucher Diskon</label>
                        <div class="flex gap-2">
                            <input type="text"
                                   id="coupon_input"
                                   x-model="couponCode"
                                   @keydown.enter.prevent="applyCouponCode()"
                                   placeholder="Kode kupon"
                                   autocomplete="off"
                                   spellcheck="false"
                                   class="h-10 min-w-0 flex-1 rounded-xl border border-[var(--border)] bg-[var(--bg)] px-3.5 text-xs font-mono font-bold uppercase tracking-[0.06em] text-[var(--text)] transition placeholder:font-sans placeholder:font-normal placeholder:normal-case placeholder:tracking-normal placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15">
                            <button type="button"
                                    @click="applyCouponCode()"
                                    :disabled="applyingCoupon || !couponCode.trim()"
                                    class="inline-flex h-10 shrink-0 items-center justify-center rounded-xl bg-[var(--brand)] px-4 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)] disabled:cursor-not-allowed disabled:opacity-40">
                                <span x-show="!applyingCoupon">Pakai</span>
                                <span x-show="applyingCoupon" x-cloak class="h-3.5 w-3.5 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                            </button>
                        </div>

                        <p x-show="couponError" x-cloak class="mt-1.5 text-[10px] font-medium leading-4 text-[var(--error)]" x-text="couponError"></p>

                        <div x-show="appliedCoupon" x-cloak class="mt-2 flex items-center justify-between gap-2 rounded-xl border border-[var(--success)]/15 bg-[var(--success-soft)] px-3 py-2.5 text-[10px] font-medium text-[var(--success)]">
                            <span class="flex min-w-0 items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                </svg>
                                <span class="truncate">Kupon <strong x-text="appliedCoupon?.code"></strong> aktif</span>
                            </span>
                            <button type="button" @click="removeCoupon()" class="shrink-0 text-[9px] font-bold underline underline-offset-2 transition hover:opacity-70">Hapus</button>
                        </div>

                        @if ($featuredCoupon)
                            <p x-show="!appliedCoupon" x-cloak class="mt-2 text-[9px] leading-4 text-[var(--text-subtle)]">
                                Baru di sini? Pakai <a href="{{ $promoUrl }}" class="font-mono font-bold text-[var(--brand)] underline decoration-[var(--brand)]/30 underline-offset-2">{{ $featuredCoupon->code }}</a> dan dapatkan {{ lcfirst($featuredCoupon->discountLabel()) }}.
                            </p>
                        @endif
                    </div>

                    {{-- Totals --}}
                    <div class="space-y-2.5 border-t border-[var(--border-light)] pt-4 text-[11px]">
                        <div class="flex items-center justify-between text-[var(--text-muted)]">
                            <span>Subtotal produk</span>
                            <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $totalAmount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center justify-between text-[var(--text-muted)]">
                            <span>Ongkos kirim</span>
                            <span class="font-bold text-[var(--text)]">Rp {{ number_format((float) $shippingCost, 0, ',', '.') }}</span>
                        </div>

                        <div x-show="discountAmount > 0" x-cloak class="flex items-center justify-between font-bold text-[var(--success)]">
                            <span>Diskon kupon</span>
                            <span>− Rp <span x-text="discountAmount.toLocaleString('id-ID')"></span></span>
                        </div>
                    </div>

                    <div class="border-t border-[var(--border-light)] pt-4">
                        <div class="flex items-end justify-between gap-2">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Total pembayaran</p>
                                <p class="mt-1 text-[9px] text-[var(--text-muted)]">Sudah termasuk ongkir</p>
                            </div>
                            <p class="font-display text-[1.8rem] font-semibold leading-none tracking-[-0.03em] text-[var(--brand-dark)]">
                                Rp <span x-text="grandTotal.toLocaleString('id-ID')"></span>
                            </p>
                        </div>
                    </div>

                    {{-- Hidden coupon for server submission --}}
                    <input type="hidden" name="coupon_code" :value="appliedCoupon ? appliedCoupon.code : ''">

                    <button type="submit"
                            :disabled="submitting"
                            class="group flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            Bayar Sekarang
                            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span x-show="submitting" x-cloak class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Menyiapkan pembayaran…
                        </span>
                    </button>

                    <div class="flex items-start gap-2 rounded-xl bg-[var(--surface-2)]/70 px-3 py-2.5">
                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[var(--text-subtle)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <p class="text-[9px] leading-4 text-[var(--text-subtle)]">
                            Transaksi aman via <span class="font-bold text-[var(--text-muted)]">Midtrans</span>. Pilih VA, QRIS, atau kartu kredit di halaman berikutnya.
                        </p>
                    </div>

                    <p class="text-center text-[9px] leading-4 text-[var(--text-subtle)]">
                        Butuh bantuan? <a href="{{ $helpUrl }}" class="font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Hubungi customer care</a>
                    </p>
                </div>
            </div>
        </aside>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutForm', () => ({
                form: {
                    recipient_name: @js($user->name),
                    recipient_phone: @js($user->phone ?? ''),
                    shipping_address: @js($user->address ?? ''),
                    notes: '',
                    save_address: false,
                },
                address: {
                    province_code: '',
                    province_name: '',
                    regency_code: '',
                    regency_name: '',
                    district_code: '',
                    district_name: '',
                    village_code: '',
                    village_name: '',
                    postal_code: '',
                    rt: '',
                    rw: '',
                    street: @js($user->address ?? ''),
                },
                savedAddresses: @js($savedAddresses),
                selectedAddressId: null,
                landmarkPhotos: [],
                provinces: [],
                regencies: [],
                districts: [],
                villages: [],
                loadingProvinces: false,
                loadingRegencies: false,
                loadingDistricts: false,
                loadingVillages: false,
                couponCode: '',
                applyingCoupon: false,
                appliedCoupon: null,
                couponError: '',
                discountAmount: 0,
                baseTotal: {{ (float) $totalAmount }},
                shippingCost: {{ (float) $shippingCost }},
                grandTotal: {{ (float) $grandTotal }},
                submitting: false,
                isBuyNow: @js($isBuyNow),
                buyNowProductUrl: @js($buyNowProductUrl),
                buyNowApiUrl: @js(url('api/buy-now')),

                init() {
                    const preferred = this.savedAddresses.find(address =>
                        address.is_default
                        && address.province_code
                        && address.regency_code
                        && address.district_code
                        && address.village_code
                    ) || this.savedAddresses.find(address => address.is_default);

                    if (preferred) {
                        this.applySavedAddress(preferred.id);
                    } else {
                        this.fetchProvinces();
                        this.updateShippingAddress();
                    }
                },

                async fetchProvinces() {
                    if (this.provinces.length) {
                        return;
                    }

                    this.loadingProvinces = true;
                    try {
                        const res = await fetch('{{ url("api/regions/provinces") }}');
                        const json = await res.json();
                        this.provinces = json.data || [];
                    } catch (e) {
                        console.error('Gagal memuat provinsi:', e);
                    } finally {
                        this.loadingProvinces = false;
                    }
                },

                async loadRegencies() {
                    this.regencies = [];
                    this.districts = [];
                    this.villages = [];

                    if (! this.address.province_code) return;

                    this.loadingRegencies = true;
                    try {
                        const res = await fetch(`{{ url("api/regions/regencies") }}?province_code=${encodeURIComponent(this.address.province_code)}`);
                        const json = await res.json();
                        this.regencies = json.data || [];
                    } catch (e) {
                        console.error('Gagal memuat kota/kabupaten:', e);
                    } finally {
                        this.loadingRegencies = false;
                    }
                },

                async onProvinceChange() {
                    const p = this.provinces.find(item => item.code === this.address.province_code);
                    this.address.province_name = p ? p.name : '';
                    this.address.regency_code = '';
                    this.address.regency_name = '';
                    this.address.district_code = '';
                    this.address.district_name = '';
                    this.address.village_code = '';
                    this.address.village_name = '';
                    this.address.postal_code = '';
                    this.updateShippingAddress();

                    await this.loadRegencies();
                },

                async loadDistricts() {
                    this.districts = [];
                    this.villages = [];

                    if (! this.address.regency_code) return;

                    this.loadingDistricts = true;
                    try {
                        const res = await fetch(`{{ url("api/regions/districts") }}?regency_code=${encodeURIComponent(this.address.regency_code)}`);
                        const json = await res.json();
                        this.districts = json.data || [];
                    } catch (e) {
                        console.error('Gagal memuat kecamatan:', e);
                    } finally {
                        this.loadingDistricts = false;
                    }
                },

                async onRegencyChange() {
                    const r = this.regencies.find(item => item.code === this.address.regency_code);
                    this.address.regency_name = r ? r.name : '';
                    this.address.district_code = '';
                    this.address.district_name = '';
                    this.address.village_code = '';
                    this.address.village_name = '';
                    this.address.postal_code = '';
                    this.updateShippingAddress();

                    await this.loadDistricts();
                },

                async loadVillages() {
                    this.villages = [];

                    if (! this.address.district_code) return;

                    this.loadingVillages = true;
                    try {
                        const res = await fetch(`{{ url("api/regions/villages") }}?district_code=${encodeURIComponent(this.address.district_code)}`);
                        const json = await res.json();
                        this.villages = json.data || [];
                    } catch (e) {
                        console.error('Gagal memuat kelurahan/desa:', e);
                    } finally {
                        this.loadingVillages = false;
                    }
                },

                async onDistrictChange() {
                    const d = this.districts.find(item => item.code === this.address.district_code);
                    this.address.district_name = d ? d.name : '';
                    this.address.village_code = '';
                    this.address.village_name = '';
                    this.address.postal_code = '';
                    this.updateShippingAddress();

                    await this.loadVillages();
                },

                onVillageChange() {
                    const v = this.villages.find(item => item.code === this.address.village_code);
                    this.address.village_name = v ? v.name : '';
                    this.address.postal_code = v?.postal_code || '';
                    this.updateShippingAddress();
                },

                onLandmarkChange(event) {
                    const files = Array.from(event.target.files || []);
                    if (! files.length) return;

                    const available = 2 - this.landmarkPhotos.length;
                    const toAdd = files.slice(0, available);

                    toAdd.forEach(file => {
                        this.landmarkPhotos.push({
                            type: 'new',
                            file: file,
                            url: URL.createObjectURL(file),
                            name: file.name,
                        });
                    });

                    event.target.value = '';
                    this.syncCheckoutFileInput();
                },

                removeLandmark(index) {
                    const removed = this.landmarkPhotos[index];
                    if (removed && removed.type === 'new' && removed.url) {
                        URL.revokeObjectURL(removed.url);
                    }
                    this.landmarkPhotos.splice(index, 1);
                    this.syncCheckoutFileInput();
                },

                syncCheckoutFileInput() {
                    const input = document.getElementById('landmark_photo');
                    if (! input) return;
                    try {
                        const dt = new DataTransfer();
                        this.landmarkPhotos
                            .filter(p => p.type === 'new' && p.file)
                            .forEach(p => dt.items.add(p.file));
                        input.files = dt.files;
                    } catch (e) {
                        // ignore DataTransfer fallback
                    }
                },

                async applySavedAddress(id) {
                    const saved = this.savedAddresses.find(item => item.id === id);
                    if (! saved) return;

                    this.selectedAddressId = saved.id;
                    this.form.recipient_name = saved.recipient_name || this.form.recipient_name;
                    this.form.recipient_phone = saved.recipient_phone || '';

                    this.address.province_code = saved.province_code || '';
                    this.address.province_name = saved.province_name || '';
                    this.address.regency_code = saved.regency_code || '';
                    this.address.regency_name = saved.regency_name || '';
                    this.address.district_code = saved.district_code || '';
                    this.address.district_name = saved.district_name || '';
                    this.address.village_code = saved.village_code || '';
                    this.address.village_name = saved.village_name || '';
                    this.address.postal_code = saved.postal_code || '';
                    this.address.rt = saved.rt || '';
                    this.address.rw = saved.rw || '';
                    this.address.street = saved.street || '';

                    this.landmarkPhotos = [];
                    const paths = saved.landmark_photos || [];
                    const urls = saved.landmark_photo_urls || [];
                    paths.forEach((path, idx) => {
                        const url = urls[idx] || (saved.landmark_photo_url && idx === 0 ? saved.landmark_photo_url : null);
                        if (url) {
                            this.landmarkPhotos.push({
                                type: 'saved',
                                path: path,
                                url: url,
                                name: 'Foto patokan ' + (idx + 1),
                            });
                        }
                    });
                    if (this.landmarkPhotos.length === 0 && saved.landmark_photo_url && saved.landmark_photo) {
                        this.landmarkPhotos.push({
                            type: 'saved',
                            path: saved.landmark_photo,
                            url: saved.landmark_photo_url,
                            name: 'Foto patokan 1',
                        });
                    }
                    this.syncCheckoutFileInput();

                    await this.fetchProvinces();
                    await this.loadRegencies();
                    await this.loadDistricts();
                    await this.loadVillages();
                    await this.$nextTick();

                    this.address.province_code = saved.province_code || '';
                    this.address.regency_code = saved.regency_code || '';
                    this.address.district_code = saved.district_code || '';
                    this.address.village_code = saved.village_code || '';

                    this.updateShippingAddress();
                },

                updateShippingAddress() {
                    const parts = [];
                    if (this.address.street?.trim()) parts.push(this.address.street.trim());

                    let rtRw = '';
                    if (this.address.rt?.trim() && this.address.rw?.trim()) {
                        rtRw = `RT ${this.address.rt.trim()}/RW ${this.address.rw.trim()}`;
                    } else if (this.address.rt?.trim()) {
                        rtRw = `RT ${this.address.rt.trim()}`;
                    } else if (this.address.rw?.trim()) {
                        rtRw = `RW ${this.address.rw.trim()}`;
                    }
                    if (rtRw) parts.push(rtRw);

                    if (this.address.village_name) parts.push('Desa/Kel. ' + this.address.village_name);
                    if (this.address.district_name) parts.push('Kec. ' + this.address.district_name);
                    if (this.address.regency_name) parts.push(this.address.regency_name);
                    if (this.address.province_name) parts.push(this.address.province_name);
                    if (this.address.postal_code?.trim()) parts.push(this.address.postal_code.trim());

                    this.form.shipping_address = parts.join(', ');
                },

                applyCouponCode() {
                    const code = this.couponCode.trim().toUpperCase();
                    if (!code) return;

                    this.couponCode = code;
                    this.applyingCoupon = true;
                    this.couponError = '';

                    fetch('{{ url("api/checkout/coupon") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ code: code, amount: this.baseTotal })
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Kupon tidak dapat digunakan.');
                        return data;
                    })
                    .then(data => {
                        this.appliedCoupon = data.data;
                        this.discountAmount = Number(data.data.discount_amount || 0);
                        this.grandTotal = Math.max(0, this.baseTotal + this.shippingCost - this.discountAmount);
                        this.$dispatch('toast', {
                            type: 'success',
                            title: 'Kupon berhasil',
                            message: 'Potongan Rp ' + this.discountAmount.toLocaleString('id-ID') + ' telah diterapkan.'
                        });
                    })
                    .catch(err => {
                        this.couponError = err.message;
                        this.appliedCoupon = null;
                        this.discountAmount = 0;
                        this.grandTotal = this.baseTotal + this.shippingCost;
                    })
                    .finally(() => {
                        this.applyingCoupon = false;
                    });
                },

                removeCoupon() {
                    this.appliedCoupon = null;
                    this.couponCode = '';
                    this.discountAmount = 0;
                    this.grandTotal = this.baseTotal + this.shippingCost;
                },

                updateCheckoutItem(id, qty) {
                    if (this.isBuyNow) {
                        this.updateBuyNowQuantity(qty);
                        return;
                    }

                    this.updateCartItem(id, qty);
                },

                updateBuyNowQuantity(qty) {
                    fetch(this.buyNowApiUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ quantity: qty })
                    })
                    .then(async (res) => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Jumlah tidak dapat diperbarui.');
                        window.location.reload();
                    })
                    .catch((err) => {
                        this.$dispatch('toast', {
                            type: 'error',
                            title: 'Beli Sekarang',
                            message: err.message
                        });
                    });
                },

                deleteCheckoutItem(id) {
                    if (this.isBuyNow) {
                        this.deleteBuyNowItem();
                        return;
                    }

                    this.deleteCartItem(id);
                },

                deleteBuyNowItem() {
                    if (!confirm('Batalkan Beli Sekarang untuk produk ini?')) return;

                    fetch(this.buyNowApiUrl, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    }).then(() => {
                        window.location.href = this.buyNowProductUrl;
                    });
                },

                updateCartItem(id, qty) {
                    fetch(`{{ url("api/cart") }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ quantity: qty })
                    }).then(() => window.location.reload());
                },

                deleteCartItem(id) {
                    if (!confirm('Hapus item ini dari pesanan?')) return;
                    fetch(`{{ url("api/cart") }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin'
                    }).then(() => window.location.reload());
                }
            }));
        });
    </script>
@endpush
