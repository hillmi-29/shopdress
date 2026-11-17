@extends('frontend.layouts.app')

@section('title', 'Profil Saya — ShopDress')
@section('meta_description', 'Kelola profil akun Anda di ShopDress. Ubah nama, email, nomor telepon, dan alamat.')

@section('content')
    @php
        $user = auth()->user();
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15';
        $selectClass = 'h-11 w-full appearance-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 pr-10 text-xs font-medium text-[var(--text)] transition focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15 disabled:cursor-not-allowed disabled:opacity-60';
    @endphp

    <header class="relative overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] px-5 py-7 sm:px-8 sm:py-9">
        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-[var(--brand-soft)]/80 blur-2xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-1/4 h-24 w-24 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

        <nav class="relative flex flex-wrap items-center gap-2 text-[10px] font-semibold text-[var(--text-subtle)]" aria-label="Breadcrumb">
            <a href="{{ \App\Support\FrontendUrls::home() }}" class="transition hover:text-[var(--brand)]">Beranda</a>
            <span aria-hidden="true">/</span>
            <span class="text-[var(--text)]" aria-current="page">Profil</span>
        </nav>

        <div class="relative mt-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-xl">
                <p class="section-kicker">Akun ShopDress</p>
                <h1 class="mt-2 font-display text-[2.6rem] font-semibold leading-[0.95] tracking-[-0.045em] text-[var(--text)] sm:text-6xl">
                    Profil Saya
                </h1>
                <p class="mt-3 max-w-lg text-xs leading-6 text-[var(--text-muted)] sm:text-sm">
                    Kelola data akun dan alamat pengirimanmu agar checkout berikutnya lebih cepat.
                </p>
            </div>

            <div class="flex items-center gap-2.5 rounded-full border border-[var(--border)] bg-[var(--bg)] px-3 py-1.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[var(--brand)] text-[10px] font-black uppercase text-white">{{ mb_substr($user->name, 0, 1) }}</span>
                <span class="pr-1 text-[10px] font-bold text-[var(--text)]">{{ $user->name }}</span>
            </div>
        </div>
    </header>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[1.3fr_0.7fr]">
        <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5 sm:p-6"
                 x-data="profileForm()">
            <div class="flex items-start justify-between gap-4 border-b border-[var(--border-light)] pb-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Informasi pribadi</p>
                    <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Data Akun</h2>
                </div>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand)]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                </span>
            </div>

            <div x-show="message" x-cloak x-transition.opacity
                 class="mt-4 rounded-xl border p-3.5 text-[11px] font-medium leading-5"
                 :class="status === 'success' ? 'border-[var(--success)]/20 bg-[var(--success-soft)] text-[var(--success)]' : 'border-[var(--error)]/20 bg-[var(--error-soft)] text-[var(--error)]'"
                 role="status">
                <span x-text="message"></span>
            </div>

            <form @submit.prevent="submit" class="mt-5 space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nama Lengkap <span class="text-[var(--error)]">*</span></label>
                        <input type="text" id="name" x-model="form.name" required autocomplete="name" class="{{ $inputClass }}" aria-describedby="name-error">
                        <p id="name-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.name" x-text="errors.name"></p>
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Email <span class="text-[var(--error)]">*</span></label>
                        <input type="email" id="email" x-model="form.email" required autocomplete="email" class="{{ $inputClass }}" aria-describedby="email-error">
                        <p id="email-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.email" x-text="errors.email"></p>
                    </div>
                </div>

                <div>
                    <label for="phone" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nomor Telepon</label>
                    <input type="tel" id="phone" x-model="form.phone" autocomplete="tel" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}" aria-describedby="phone-error">
                    <p id="phone-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.phone" x-text="errors.phone"></p>
                </div>

                <button type="submit"
                        :disabled="saving"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)] disabled:cursor-not-allowed disabled:opacity-60">
                    <svg x-show="saving" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                </button>
            </form>
        </section>

        <div class="space-y-5">
            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: 80ms">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Keamanan</p>
                        <h2 class="mt-0.5 font-display text-lg font-semibold tracking-[-0.02em] text-[var(--text)]">Kata Sandi</h2>
                    </div>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--surface-2)] text-[var(--text-muted)]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                    </span>
                </div>

                <p class="mt-3 text-[11px] leading-6 text-[var(--text-muted)]">
                    Untuk mengganti kata sandi, hubungi customer care kami melalui email. Tim akan memverifikasi akunmu dan mengirimkan tautan pengaturan ulang.
                </p>

                <a href="mailto:halo@shopdress.com?subject=Ubah%20Kata%20Sandi%20ShopDress"
                   class="mt-4 inline-flex h-10 items-center justify-center gap-1.5 rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 text-[10px] font-bold text-[var(--text)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                    Hubungi Customer Care
                </a>
            </section>

            <section class="reveal rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)] p-5" style="--reveal-delay: 120ms">
                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Ringkasan akun</p>
                <div class="mt-3 space-y-2.5 text-[11px]">
                    <div class="flex items-center justify-between">
                        <span class="text-[var(--text-muted)]">Status akun</span>
                        <span class="font-bold text-[var(--success)]">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[var(--text-muted)]">Email terdaftar</span>
                        <span class="font-bold text-[var(--text)]">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ \App\Support\FrontendUrls::orders() }}" class="inline-flex h-9 flex-1 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[10px] font-bold text-[var(--brand-dark)] transition hover:bg-[var(--brand)] hover:text-white">Pesanan</a>
                    <a href="{{ \App\Support\FrontendUrls::wishlist() }}" class="inline-flex h-9 flex-1 items-center justify-center rounded-full border border-[var(--border)] text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">Wishlist</a>
                </div>
            </section>
        </div>
    </div>

    <section class="reveal mt-6 overflow-hidden rounded-[1.5rem] border border-[var(--border)] bg-[var(--surface)]"
             x-data="addressBook()">
        <div class="flex flex-col gap-4 border-b border-[var(--border-light)] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Alamat tersimpan</p>
                <h2 class="mt-0.5 font-display text-xl font-semibold tracking-[-0.02em] text-[var(--text)]">Alamat Pengiriman</h2>
                <p class="mt-1 text-[10px] leading-5 text-[var(--text-muted)]">
                    Simpan beberapa alamat. Alamat utama otomatis muncul saat checkout.
                </p>
            </div>
            <button type="button"
                    @click="openCreate()"
                    class="inline-flex h-10 shrink-0 items-center justify-center gap-1.5 rounded-full bg-[var(--brand)] px-4 text-[10px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition hover:bg-[var(--brand-dark)]">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Tambah Alamat
            </button>
        </div>

        <div class="p-5 sm:p-6">
            <div x-show="message" x-cloak x-transition.opacity
                 class="mb-4 rounded-xl border p-3.5 text-[11px] font-medium leading-5"
                 :class="status === 'success' ? 'border-[var(--success)]/20 bg-[var(--success-soft)] text-[var(--success)]' : 'border-[var(--error)]/20 bg-[var(--error-soft)] text-[var(--error)]'"
                 role="status">
                <span x-text="message"></span>
            </div>

            <div x-show="addresses.length === 0 && ! showForm" x-cloak
                 class="rounded-2xl border border-dashed border-[var(--border)] bg-[var(--bg)] px-5 py-8 text-center">
                <p class="text-[12px] font-bold text-[var(--text)]">Belum ada alamat tersimpan</p>
                <p class="mx-auto mt-1 max-w-sm text-[10px] leading-5 text-[var(--text-muted)]">
                    Tambahkan alamat pertamamu agar proses checkout berikutnya lebih cepat.
                </p>
                <button type="button" @click="openCreate()" class="mt-4 inline-flex h-9 items-center justify-center rounded-full bg-[var(--brand-soft)] px-4 text-[10px] font-bold text-[var(--brand-dark)] transition hover:bg-[var(--brand)] hover:text-white">
                    Tambah Alamat Pertama
                </button>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3" x-show="addresses.length > 0">
                <template x-for="address in addresses" :key="address.id">
                    <article class="flex flex-col rounded-2xl border p-4 transition"
                             :class="address.is_default ? 'border-[var(--brand)] bg-[var(--brand-soft)]/30' : 'border-[var(--border)] bg-[var(--bg)]'">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]" x-text="address.label || 'Alamat'"></p>
                                <p class="mt-1 truncate text-[12px] font-bold text-[var(--text)]" x-text="address.recipient_name"></p>
                                <p class="text-[10px] text-[var(--text-muted)]" x-text="address.recipient_phone || '-'"></p>
                            </div>
                            <span x-show="address.is_default" class="shrink-0 rounded-full bg-[var(--brand)] px-2 py-0.5 text-[8px] font-bold uppercase tracking-[0.08em] text-white">Utama</span>
                        </div>
                        <p class="mt-3 flex-1 text-[10px] leading-5 text-[var(--text-muted)]" x-text="address.full_address"></p>
                        <template x-if="address.landmark_photo_url">
                            <div class="mt-2.5 flex items-center gap-2 rounded-xl border border-[var(--border-light)] bg-[var(--surface-2)]/40 p-1.5">
                                <img :src="address.landmark_photo_url" alt="Patokan" class="h-8 w-8 rounded-lg object-cover border border-[var(--border)]">
                                <span class="text-[9px] font-medium text-[var(--text-subtle)]">Foto patokan terlampir</span>
                            </div>
                        </template>
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            <button type="button" @click="openEdit(address)" class="inline-flex h-8 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-3 text-[9px] font-bold text-[var(--text)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                                Ubah
                            </button>
                            <button type="button" x-show="! address.is_default" @click="setDefault(address)" :disabled="busyId === address.id" class="inline-flex h-8 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-3 text-[9px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)] disabled:opacity-50">
                                Jadikan Utama
                            </button>
                            <button type="button" @click="remove(address)" :disabled="busyId === address.id" class="inline-flex h-8 items-center justify-center rounded-full px-3 text-[9px] font-bold text-[var(--error)] transition hover:bg-[var(--error-soft)] disabled:opacity-50">
                                Hapus
                            </button>
                        </div>
                    </article>
                </template>
            </div>

            <form x-show="showForm" x-cloak x-transition.opacity
                  @submit.prevent="save"
                  class="mt-5 rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-4 sm:p-5">
                <div class="flex items-start justify-between gap-3 border-b border-[var(--border-light)] pb-3">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.14em] text-[var(--text-subtle)]">Form alamat</p>
                        <h3 class="mt-0.5 font-display text-lg font-semibold tracking-[-0.02em] text-[var(--text)]" x-text="editingId ? 'Ubah Alamat' : 'Tambah Alamat Baru'"></h3>
                    </div>
                    <button type="button" @click="closeForm()" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-[var(--text-subtle)] transition hover:bg-[var(--surface-2)] hover:text-[var(--text)]" aria-label="Tutup form alamat">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="address_label" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Label Alamat</label>
                        <input type="text" id="address_label" x-model="form.label" maxlength="50" placeholder="Rumah, Kantor, Kos" class="{{ $inputClass }}">
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.label" x-text="errors.label"></p>
                    </div>

                    <div>
                        <label for="address_recipient_name" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nama Penerima <span class="text-[var(--error)]">*</span></label>
                        <input type="text" id="address_recipient_name" x-model="form.recipient_name" required autocomplete="name" class="{{ $inputClass }}">
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.recipient_name" x-text="errors.recipient_name"></p>
                    </div>

                    <div>
                        <label for="address_recipient_phone" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Nomor Telepon / WA <span class="text-[var(--error)]">*</span></label>
                        <input type="tel" id="address_recipient_phone" x-model="form.recipient_phone" required inputmode="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}">
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.recipient_phone" x-text="errors.recipient_phone"></p>
                    </div>

                    <div>
                        <label for="address_province" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Provinsi <span class="text-[var(--error)]">*</span></label>
                        <div class="relative">
                            <select id="address_province" x-model="form.province_code" @change="onProvinceChange()" required :disabled="loadingProvinces" class="{{ $selectClass }}">
                                <option value="">Pilih Provinsi</option>
                                <template x-for="province in provinces" :key="province.code">
                                    <option :value="province.code" x-text="province.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.province_code" x-text="errors.province_code"></p>
                    </div>

                    <div>
                        <label for="address_regency" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Kota / Kabupaten <span class="text-[var(--error)]">*</span></label>
                        <div class="relative">
                            <select id="address_regency" x-model="form.regency_code" @change="onRegencyChange()" required :disabled="! form.province_code || loadingRegencies" class="{{ $selectClass }}">
                                <option value="" x-text="! form.province_code ? 'Pilih provinsi dahulu' : (loadingRegencies ? 'Memuat kota/kabupaten…' : 'Pilih Kota / Kabupaten')"></option>
                                <template x-for="regency in regencies" :key="regency.code">
                                    <option :value="regency.code" x-text="regency.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.regency_code" x-text="errors.regency_code"></p>
                    </div>

                    <div>
                        <label for="address_district" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Kecamatan <span class="text-[var(--error)]">*</span></label>
                        <div class="relative">
                            <select id="address_district" x-model="form.district_code" @change="onDistrictChange()" required :disabled="! form.regency_code || loadingDistricts" class="{{ $selectClass }}">
                                <option value="" x-text="! form.regency_code ? 'Pilih kota/kabupaten dahulu' : (loadingDistricts ? 'Memuat kecamatan…' : 'Pilih Kecamatan')"></option>
                                <template x-for="district in districts" :key="district.code">
                                    <option :value="district.code" x-text="district.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.district_code" x-text="errors.district_code"></p>
                    </div>

                    <div>
                        <label for="address_village" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Kelurahan / Desa <span class="text-[var(--error)]">*</span></label>
                        <div class="relative">
                            <select id="address_village" x-model="form.village_code" @change="onVillageChange()" required :disabled="! form.district_code || loadingVillages" class="{{ $selectClass }}">
                                <option value="" x-text="! form.district_code ? 'Pilih kecamatan dahulu' : (loadingVillages ? 'Memuat kelurahan/desa…' : 'Pilih Kelurahan / Desa')"></option>
                                <template x-for="village in villages" :key="village.code">
                                    <option :value="village.code" x-text="village.name"></option>
                                </template>
                            </select>
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-[var(--text-subtle)]">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.village_code" x-text="errors.village_code"></p>
                    </div>

                    <div>
                        <label for="address_postal_code" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Kode Pos <span class="text-[var(--error)]">*</span></label>
                        <input type="text" id="address_postal_code" x-model="form.postal_code" required maxlength="10" placeholder="Terisi otomatis dari kelurahan" class="{{ $inputClass }}">
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.postal_code" x-text="errors.postal_code"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="address_rt" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">RT <span class="font-normal text-[var(--text-subtle)]">(opsional)</span></label>
                            <input type="text" id="address_rt" x-model="form.rt" maxlength="6" placeholder="002" class="{{ $inputClass }}">
                            <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.rt" x-text="errors.rt"></p>
                        </div>
                        <div>
                            <label for="address_rw" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">RW <span class="font-normal text-[var(--text-subtle)]">(opsional)</span></label>
                            <input type="text" id="address_rw" x-model="form.rw" maxlength="6" placeholder="005" class="{{ $inputClass }}">
                            <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.rw" x-text="errors.rw"></p>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address_street" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Detail Jalan, Nomor Rumah &amp; Patokan <span class="text-[var(--error)]">*</span></label>
                        <input type="text" id="address_street" x-model="form.street" required autocomplete="street-address" placeholder="Nama jalan, nomor rumah, blok, atau patokan" class="{{ $inputClass }}">
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.street" x-text="errors.street"></p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address_landmark_photo" class="mb-1.5 flex items-center justify-between text-[11px] font-bold text-[var(--text)]">
                            <span>Foto Patokan Lokasi <span class="font-normal text-[var(--text-subtle)]">(opsional)</span></span>
                            <span class="text-[9px] font-normal text-[var(--text-subtle)]">Maks. 2MB (JPG, PNG, WebP)</span>
                        </label>
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="flex h-11 cursor-pointer items-center gap-2 rounded-xl border border-dashed border-[var(--border)] bg-[var(--surface)] px-4 text-xs font-medium text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                                <span x-text="landmarkFileName ? 'Ganti Foto' : (landmarkPreviewUrl ? 'Ganti Foto Patokan' : 'Pilih Foto Patokan')">Pilih Foto Patokan</span>
                                <input type="file"
                                       id="address_landmark_photo"
                                       accept="image/jpeg,image/png,image/webp,image/jpg"
                                       class="hidden"
                                       @change="onLandmarkPhotoChange($event)">
                            </label>
                            <template x-if="landmarkPreviewUrl">
                                <div class="flex items-center gap-2 rounded-xl border border-[var(--border-light)] bg-[var(--surface-2)]/60 px-3 py-1.5">
                                    <img :src="landmarkPreviewUrl" alt="Preview Patokan" class="h-8 w-8 rounded-lg object-cover border border-[var(--border)]">
                                    <span class="max-w-[150px] truncate text-[11px] font-medium text-[var(--text)]" x-text="landmarkFileName || 'Foto patokan'"></span>
                                    <button type="button"
                                            @click="removeLandmarkPhoto()"
                                            class="ml-1 text-[10px] font-bold text-[var(--error)] transition hover:underline">
                                        Hapus
                                    </button>
                                </div>
                            </template>
                        </div>
                        <p class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.landmark_photo" x-text="errors.landmark_photo"></p>
                        <p class="mt-1 text-[9px] text-[var(--text-subtle)]">Unggah foto pagar, rumah, ruko, atau patokan visual terdekat untuk memudahkan kurir mengantar pesanan.</p>
                    </div>

                    <label class="flex cursor-pointer items-start gap-2.5 rounded-xl border border-[var(--border)] bg-[var(--surface)] p-3.5 sm:col-span-2">
                        <input type="checkbox" x-model="form.is_default" class="mt-0.5 h-4 w-4 rounded border-[var(--border)] text-[var(--brand)] focus:ring-[var(--brand)]/20">
                        <span>
                            <span class="block text-[11px] font-bold text-[var(--text)]">Jadikan alamat utama</span>
                            <span class="mt-0.5 block text-[9px] leading-4 text-[var(--text-muted)]">Alamat utama otomatis dipilih saat kamu membuka checkout.</span>
                        </span>
                    </label>

                    <div class="flex flex-wrap items-center justify-end gap-2 sm:col-span-2">
                        <button type="button" @click="closeForm()" class="inline-flex h-10 items-center justify-center rounded-full border border-[var(--border)] bg-[var(--surface)] px-4 text-[10px] font-bold text-[var(--text-muted)] transition hover:border-[var(--brand)] hover:text-[var(--brand)]">
                            Batal
                        </button>
                        <button type="submit" :disabled="saving" class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[10px] font-bold text-white transition hover:bg-[var(--brand-dark)] disabled:cursor-not-allowed disabled:opacity-60">
                            <svg x-show="saving" x-cloak class="h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="saving ? 'Menyimpan…' : 'Simpan Alamat'">Simpan Alamat</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileForm', () => ({
                form: {
                    name: @js($user->name),
                    email: @js($user->email),
                    phone: @js($user->phone ?? ''),
                },
                errors: {},
                saving: false,
                status: 'success',
                message: '',

                async submit() {
                    this.errors = {};
                    this.message = '';
                    this.saving = true;

                    try {
                        const response = await fetch('{{ url('/api/user/profile') }}', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(this.form),
                        });

                        const data = await response.json();

                        if (! response.ok) {
                            if (data.errors) {
                                this.errors = Object.fromEntries(
                                    Object.entries(data.errors).map(([key, value]) => [key, Array.isArray(value) ? value[0] : value])
                                );
                            }
                            throw new Error(data.message || 'Gagal memperbarui profil.');
                        }

                        this.status = 'success';
                        this.message = data.message || 'Profil berhasil diperbarui.';
                    } catch (error) {
                        this.status = 'error';
                        this.message = error.message || 'Gagal memperbarui profil.';
                    } finally {
                        this.saving = false;
                    }
                },
            }));

            Alpine.data('addressBook', () => ({
                addresses: @js($addresses),
                defaults: {
                    recipient_name: @js($user->name),
                    recipient_phone: @js($user->phone ?? ''),
                },
                provinces: [],
                regencies: [],
                districts: [],
                villages: [],
                loadingProvinces: false,
                loadingRegencies: false,
                loadingDistricts: false,
                loadingVillages: false,
                showForm: false,
                editingId: null,
                saving: false,
                busyId: null,
                status: 'success',
                message: '',
                errors: {},
                landmarkFile: null,
                landmarkFileName: '',
                landmarkPreviewUrl: null,
                removeLandmarkPhotoFlag: false,
                form: {
                    label: '',
                    recipient_name: @js($user->name),
                    recipient_phone: @js($user->phone ?? ''),
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
                    street: '',
                    is_default: false,
                },

                resetForm() {
                    this.form = {
                        label: '',
                        recipient_name: this.defaults.recipient_name,
                        recipient_phone: this.defaults.recipient_phone,
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
                        street: '',
                        is_default: this.addresses.length === 0,
                    };
                    this.regencies = [];
                    this.districts = [];
                    this.villages = [];
                },

                openCreate() {
                    this.editingId = null;
                    this.errors = {};
                    this.message = '';
                    this.landmarkFile = null;
                    this.landmarkFileName = '';
                    this.landmarkPreviewUrl = null;
                    this.removeLandmarkPhotoFlag = false;
                    const input = document.getElementById('address_landmark_photo');
                    if (input) input.value = '';
                    this.resetForm();
                    this.showForm = true;
                    this.fetchProvinces();
                },

                closeForm() {
                    this.showForm = false;
                    this.editingId = null;
                    this.errors = {};
                    this.landmarkFile = null;
                    this.landmarkFileName = '';
                    this.landmarkPreviewUrl = null;
                    this.removeLandmarkPhotoFlag = false;
                },

                async openEdit(address) {
                    this.editingId = address.id;
                    this.errors = {};
                    this.message = '';
                    this.landmarkFile = null;
                    this.landmarkFileName = '';
                    this.landmarkPreviewUrl = address.landmark_photo_url || null;
                    this.removeLandmarkPhotoFlag = false;
                    const input = document.getElementById('address_landmark_photo');
                    if (input) input.value = '';
                    this.showForm = true;
                    this.form = {
                        label: address.label || '',
                        recipient_name: address.recipient_name || '',
                        recipient_phone: address.recipient_phone || '',
                        province_code: address.province_code || '',
                        province_name: address.province_name || '',
                        regency_code: address.regency_code || '',
                        regency_name: address.regency_name || '',
                        district_code: address.district_code || '',
                        district_name: address.district_name || '',
                        village_code: address.village_code || '',
                        village_name: address.village_name || '',
                        postal_code: address.postal_code || '',
                        rt: address.rt || '',
                        rw: address.rw || '',
                        street: address.street || '',
                        is_default: !! address.is_default,
                    };

                    await this.fetchProvinces();
                    await this.loadRegencies();
                    await this.loadDistricts();
                    await this.loadVillages();
                    await this.$nextTick();

                    this.form.province_code = address.province_code || '';
                    this.form.regency_code = address.regency_code || '';
                    this.form.district_code = address.district_code || '';
                    this.form.village_code = address.village_code || '';
                },

                async fetchProvinces() {
                    if (this.provinces.length || this.loadingProvinces) {
                        return;
                    }

                    this.loadingProvinces = true;
                    try {
                        const response = await fetch('{{ url("api/regions/provinces") }}');
                        const json = await response.json();
                        this.provinces = json.data || [];
                    } catch (error) {
                        console.error('Gagal memuat provinsi:', error);
                    } finally {
                        this.loadingProvinces = false;
                    }
                },

                async loadRegencies() {
                    this.regencies = [];
                    this.districts = [];
                    this.villages = [];

                    if (! this.form.province_code) {
                        return;
                    }

                    this.loadingRegencies = true;
                    try {
                        const response = await fetch(`{{ url("api/regions/regencies") }}?province_code=${encodeURIComponent(this.form.province_code)}`);
                        const json = await response.json();
                        this.regencies = json.data || [];
                    } catch (error) {
                        console.error('Gagal memuat kota/kabupaten:', error);
                    } finally {
                        this.loadingRegencies = false;
                    }
                },

                async onProvinceChange() {
                    const province = this.provinces.find(item => item.code === this.form.province_code);
                    this.form.province_name = province ? province.name : '';
                    this.form.regency_code = '';
                    this.form.regency_name = '';
                    this.form.district_code = '';
                    this.form.district_name = '';
                    this.form.village_code = '';
                    this.form.village_name = '';
                    this.form.postal_code = '';

                    await this.loadRegencies();
                },

                async loadDistricts() {
                    this.districts = [];
                    this.villages = [];

                    if (! this.form.regency_code) {
                        return;
                    }

                    this.loadingDistricts = true;
                    try {
                        const response = await fetch(`{{ url("api/regions/districts") }}?regency_code=${encodeURIComponent(this.form.regency_code)}`);
                        const json = await response.json();
                        this.districts = json.data || [];
                    } catch (error) {
                        console.error('Gagal memuat kecamatan:', error);
                    } finally {
                        this.loadingDistricts = false;
                    }
                },

                async onRegencyChange() {
                    const regency = this.regencies.find(item => item.code === this.form.regency_code);
                    this.form.regency_name = regency ? regency.name : '';
                    this.form.district_code = '';
                    this.form.district_name = '';
                    this.form.village_code = '';
                    this.form.village_name = '';
                    this.form.postal_code = '';

                    await this.loadDistricts();
                },

                async loadVillages() {
                    this.villages = [];

                    if (! this.form.district_code) {
                        return;
                    }

                    this.loadingVillages = true;
                    try {
                        const response = await fetch(`{{ url("api/regions/villages") }}?district_code=${encodeURIComponent(this.form.district_code)}`);
                        const json = await response.json();
                        this.villages = json.data || [];
                    } catch (error) {
                        console.error('Gagal memuat kelurahan/desa:', error);
                    } finally {
                        this.loadingVillages = false;
                    }
                },

                async onDistrictChange() {
                    const district = this.districts.find(item => item.code === this.form.district_code);
                    this.form.district_name = district ? district.name : '';
                    this.form.village_code = '';
                    this.form.village_name = '';
                    this.form.postal_code = '';

                    await this.loadVillages();
                },

                onVillageChange() {
                    const village = this.villages.find(item => item.code === this.form.village_code);
                    this.form.village_name = village ? village.name : '';
                    this.form.postal_code = village?.postal_code || '';
                },

                onLandmarkPhotoChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.landmarkFile = file;
                        this.landmarkFileName = file.name;
                        this.landmarkPreviewUrl = URL.createObjectURL(file);
                        this.removeLandmarkPhotoFlag = false;
                    }
                },

                removeLandmarkPhoto() {
                    this.landmarkFile = null;
                    this.landmarkFileName = '';
                    this.landmarkPreviewUrl = null;
                    this.removeLandmarkPhotoFlag = true;
                    const input = document.getElementById('address_landmark_photo');
                    if (input) input.value = '';
                },

                async save() {
                    this.errors = {};
                    this.message = '';
                    this.saving = true;

                    try {
                        const formData = new FormData();
                        for (const [key, value] of Object.entries(this.form)) {
                            if (value !== null && value !== undefined) {
                                formData.append(key, typeof value === 'boolean' ? (value ? '1' : '0') : value);
                            }
                        }
                        if (this.landmarkFile) {
                            formData.append('landmark_photo', this.landmarkFile);
                        }
                        if (this.removeLandmarkPhotoFlag) {
                            formData.append('remove_landmark_photo', '1');
                        }
                        if (this.editingId) {
                            formData.append('_method', 'PUT');
                        }

                        const endpoint = this.editingId
                            ? `{{ url('/profil/alamat') }}/${this.editingId}`
                            : '{{ url('/profil/alamat') }}';

                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            credentials: 'same-origin',
                            body: formData,
                        });

                        const data = await response.json().catch(() => ({}));

                        if (! response.ok) {
                            if (data.errors) {
                                this.errors = Object.fromEntries(
                                    Object.entries(data.errors).map(([key, value]) => [key, Array.isArray(value) ? value[0] : value])
                                );
                            }
                            throw new Error(data.message || 'Gagal menyimpan alamat.');
                        }

                        window.location.reload();
                    } catch (error) {
                        this.status = 'error';
                        this.message = error.message || 'Gagal menyimpan alamat.';
                    } finally {
                        this.saving = false;
                    }
                },

                async setDefault(address) {
                    this.busyId = address.id;
                    this.message = '';

                    try {
                        const response = await fetch(`{{ url('/profil/alamat') }}/${address.id}/default`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            credentials: 'same-origin',
                        });

                        if (! response.ok) {
                            const data = await response.json().catch(() => ({}));
                            throw new Error(data.message || 'Gagal mengubah alamat utama.');
                        }

                        window.location.reload();
                    } catch (error) {
                        this.status = 'error';
                        this.message = error.message || 'Gagal mengubah alamat utama.';
                    } finally {
                        this.busyId = null;
                    }
                },

                async remove(address) {
                    if (! confirm(`Hapus alamat "${address.label || address.recipient_name}"?`)) {
                        return;
                    }

                    this.busyId = address.id;
                    this.message = '';

                    try {
                        const response = await fetch(`{{ url('/profil/alamat') }}/${address.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            credentials: 'same-origin',
                        });

                        if (! response.ok) {
                            const data = await response.json().catch(() => ({}));
                            throw new Error(data.message || 'Gagal menghapus alamat.');
                        }

                        window.location.reload();
                    } catch (error) {
                        this.status = 'error';
                        this.message = error.message || 'Gagal menghapus alamat.';
                    } finally {
                        this.busyId = null;
                    }
                },
            }));
        });
    </script>
@endpush
