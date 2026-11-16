@extends('frontend.layouts.app')

@section('title', 'Profil Saya — ShopDress')
@section('meta_description', 'Kelola profil akun Anda di ShopDress. Ubah nama, email, nomor telepon, dan alamat.')

@section('content')
    @php
        $user = auth()->user();
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15';
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

                <div>
                    <label for="address" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Alamat Pengiriman</label>
                    <textarea id="address" x-model="form.address" rows="3" autocomplete="street-address" placeholder="Alamat lengkap untuk pengiriman" class="w-full resize-none rounded-xl border border-[var(--border)] bg-[var(--bg)] px-4 py-3 text-xs leading-5 text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15" aria-describedby="address-error"></textarea>
                    <p id="address-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]" x-show="errors.address" x-text="errors.address"></p>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileForm', () => ({
                form: {
                    name: @js($user->name),
                    email: @js($user->email),
                    phone: @js($user->phone ?? ''),
                    address: @js($user->address ?? ''),
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
        });
    </script>
@endpush
