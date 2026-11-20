@extends('frontend.layouts.app')

@section('title', 'Masuk — '.\App\Models\SiteSetting::storeName())
@section('meta_description', 'Masuk ke akun '.\App\Models\SiteSetting::storeName().' Anda untuk mengakses wishlist, riwayat pesanan, dan kecepatan checkout.')

@section('content')
    @php
        $storeName = \App\Models\SiteSetting::storeName();
        $redirectTarget = request()->query('redirect');
        $registerUrl = \App\Support\FrontendUrls::register()
            . (is_string($redirectTarget) && $redirectTarget !== ''
                ? '?'.http_build_query(['redirect' => $redirectTarget])
                : '');
        $helpUrl = $helpUrl ?? \App\Support\FrontendUrls::help();
        $loginBenefits = [
            'Keranjang dan wishlist tersimpan otomatis',
            'Status pesanan bisa dipantau kapan saja',
            'Checkout lebih cepat di kunjungan berikutnya',
        ];
    @endphp

    <div class="mx-auto grid max-w-5xl gap-5 lg:grid-cols-[0.92fr_1.08fr] lg:items-stretch">

        {{-- Panel brand (desktop) --}}
        <aside class="reveal relative hidden overflow-hidden rounded-[1.75rem] bg-[var(--brand-deep)] p-8 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-[var(--brand)]/35 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-20 h-56 w-56 rounded-full bg-[var(--accent)]/15 blur-3xl"></div>

            <div class="relative">
                <p class="text-[9px] font-black uppercase tracking-[0.22em] text-[#c9b3f5]">Akun {{ $storeName }}</p>
                <h2 class="mt-3 max-w-xs font-display text-[2.4rem] font-semibold leading-[1.03] tracking-[-0.04em]">
                    Selamat datang <span class="italic text-[#c9b3f5]">kembali</span>
                </h2>
                <p class="mt-3 max-w-xs text-xs leading-6 text-white/60">
                    Satu akun untuk wishlist, riwayat pesanan, dan pengalaman belanja yang lebih cepat.
                </p>

                <ul class="mt-8 space-y-3.5">
                    @foreach ($loginBenefits as $benefit)
                        <li class="flex items-start gap-3 text-xs leading-5 text-white/75">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[#9be7bd] ring-1 ring-white/15">
                                <svg class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            {{ $benefit }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative mt-10 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
                <p class="text-[9px] font-bold uppercase tracking-[0.18em] text-[#c9b3f5]">Belum punya akun?</p>
                <p class="mt-1.5 text-[11px] leading-5 text-white/60">
                    Daftar gratis dan dapatkan akses ke koleksi terbaru lebih dulu.
                </p>
                <a href="{{ $registerUrl }}"
                   class="mt-3 inline-flex items-center gap-1.5 text-[11px] font-bold text-white underline-offset-4 transition hover:underline">
                    Buat akun sekarang
                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </aside>

        {{-- Kartu formulir --}}
        <div class="reveal overflow-hidden rounded-[1.75rem] border border-[var(--border)] bg-[var(--surface)] shadow-[0_26px_70px_-56px_rgba(50,19,95,0.5)]">
            <div class="relative overflow-hidden border-b border-[var(--border-light)] bg-[var(--brand-soft)]/50 px-6 py-7 sm:px-8">
                <div class="pointer-events-none absolute -right-10 -top-14 h-40 w-40 rounded-full bg-[var(--brand)]/10 blur-2xl"></div>
                <div class="pointer-events-none absolute -bottom-16 -left-10 h-36 w-36 rounded-full bg-[var(--accent-soft)]/70 blur-2xl"></div>

                <p class="relative text-[9px] font-black uppercase tracking-[0.2em] text-[var(--brand)]">Akun {{ $storeName }}</p>
                <h1 class="relative mt-2 font-display text-[2rem] font-semibold leading-[1.05] tracking-[-0.04em] text-[var(--text)]">
                    Masuk ke <span class="italic text-[var(--brand)]">akunmu</span>
                </h1>
                <p class="relative mt-2 max-w-sm text-xs leading-6 text-[var(--text-muted)]">
                    Gunakan email dan kata sandi yang kamu daftarkan untuk melanjutkan belanja.
                </p>
            </div>

            <div class="p-6 sm:p-8">
                @if (session('error'))
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-[var(--error)]/20 bg-[var(--error-soft)] p-4" role="alert">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[var(--surface)] text-[var(--error)]">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-[var(--error)]">Tidak bisa masuk</p>
                            <p class="mt-0.5 text-[11px] leading-5 text-[var(--error)]/85">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5" x-data="{ showPassword: false }">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-[11px] font-bold text-[var(--text)]">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               placeholder="nama@email.com"
                               class="h-11 w-full rounded-xl border @error('email') border-[var(--error)]/40 @else border-[var(--border)] @enderror bg-[var(--bg)] px-4 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15"
                               aria-describedby="email-error">
                        @error('email')
                            <p id="email-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <label for="password" class="text-[11px] font-bold text-[var(--text)]">Kata Sandi</label>
                            <a href="{{ $helpUrl }}" class="text-[10px] font-semibold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Lupa kata sandi?</a>
                        </div>
                        <div class="relative">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="h-11 w-full rounded-xl border @error('password') border-[var(--error)]/40 @else border-[var(--border)] @enderror bg-[var(--bg)] px-4 pr-11 text-xs text-[var(--text)] transition placeholder:text-[var(--text-subtle)] focus:border-[var(--brand)] focus:bg-[var(--surface)] focus:outline-none focus:ring-2 focus:ring-[var(--brand)]/15"
                                   :type="showPassword ? 'text' : 'password'"
                                   aria-describedby="password-error">
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[var(--text-subtle)] transition hover:text-[var(--brand)]"
                                    :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                <svg x-show="!showPassword" class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                <svg x-show="showPassword" x-cloak class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473a1 1 0 010-1.414l1.473-1.473a1 1 0 00-1.414-1.414L3.707 2.293zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p id="password-error" class="mt-1.5 text-[11px] font-medium text-[var(--error)]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox"
                               id="remember"
                               name="remember"
                               value="1"
                               class="h-4 w-4 rounded border-[var(--border)] text-[var(--brand)] focus:ring-[var(--brand)]/20">
                        <label for="remember" class="text-[11px] font-medium text-[var(--text-muted)]">Ingat saya</label>
                    </div>

                    <button type="submit"
                            class="flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[var(--brand)] px-5 text-[11px] font-bold text-white shadow-[0_12px_26px_-16px_rgba(124,58,237,0.9)] transition-all duration-200 ease-out hover:bg-[var(--brand-dark)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--brand)] focus-visible:ring-offset-2 active:scale-[0.99]">
                        Masuk ke Akun
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1 0 1.414l-6 6a1 1 0 0 1-1.414-1.414L14.586 11H3a1 1 0 1 1 0-2h11.586l-4.293-4.293a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" /></svg>
                    </button>
                </form>

                <div class="mt-6 border-t border-[var(--border-light)] pt-5">
                    <p class="text-center text-[11px] text-[var(--text-muted)]">
                        Belum punya akun?
                        <a href="{{ $registerUrl }}" class="font-bold text-[var(--brand)] transition hover:text-[var(--brand-dark)]">Daftar gratis</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <p class="mx-auto mt-5 max-w-md text-center text-[10px] leading-5 text-[var(--text-subtle)]">
        Dengan masuk, kamu menyetujui
        <a href="{{ \App\Support\FrontendUrls::terms() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Syarat & Ketentuan</a>
        serta
        <a href="{{ \App\Support\FrontendUrls::privacy() }}" class="font-semibold text-[var(--brand)] underline-offset-2 hover:underline">Kebijakan Privasi</a>
        {{ $storeName }}.
    </p>
@endsection
