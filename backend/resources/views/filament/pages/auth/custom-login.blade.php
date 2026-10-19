{{-- Halaman login — memakai kelas .sd-* yang sama dengan dashboard (lihat section 6 admin-shopdress.css) --}}
<div class="sd-auth-page">
    <header class="sd-auth-header">
        <a href="{{ url('/') }}" class="sd-auth-home" aria-label="Kembali ke beranda">
            @include('filament.brand-logo')
        </a>
    </header>

    <main class="sd-auth-main">
        <div class="sd-auth-col">
            <div class="sd-auth-card sd-auth-enter">
                <div class="sd-auth-head">
                    <span class="sd-auth-icon">
                        <span class="material-symbols-outlined" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">checkroom</span>
                    </span>
                    <h1 class="sd-auth-title">Masuk ke Panel Admin</h1>
                    <p class="sd-auth-subtitle">Kelola katalog, inventaris, dan pesanan busana Anda</p>
                </div>

                <form wire:submit="authenticate" class="sd-auth-form" id="admin-login-form" novalidate>
                    @if ($loginAttempts > 0 && $errors->any())
                        <div
                            wire:key="login-error-summary-{{ $loginAttempts }}"
                            class="sd-auth-summary"
                            role="alert"
                            tabindex="-1"
                            aria-labelledby="login-error-title"
                            x-init="$el.focus()"
                        >
                            <span class="material-symbols-outlined" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">error</span>
                            <div>
                                <p class="sd-auth-summary-title" id="login-error-title">Masuk tidak berhasil</p>
                                <ul class="sd-auth-summary-list">
                                    @foreach ($errors->getMessages() as $field => $messages)
                                        @php $target = str_contains($field, 'password') ? 'admin-password' : 'admin-email'; @endphp
                                        @foreach ($messages as $message)
                                            <li><a href="#{{ $target }}">{{ $message }}</a></li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="sd-auth-field">
                        <label class="sd-auth-label" for="admin-email">
                            Alamat Email <span class="sd-auth-req" aria-hidden="true">*</span>
                        </label>
                        <div class="sd-auth-box">
                            <span class="material-symbols-outlined sd-auth-box-icon" aria-hidden="true">mail</span>
                            <input
                                wire:model.blur="data.email"
                                class="sd-auth-input"
                                id="admin-email"
                                name="email"
                                type="email"
                                placeholder="admin@shopdress.id"
                                value="admin@shopdress.id"
                                required
                                autofocus
                                autocomplete="username"
                                autocapitalize="none"
                                spellcheck="false"
                                @error('data.email') aria-invalid="true" aria-describedby="admin-email-error" @enderror
                            />
                        </div>
                        @error('data.email')
                            <p class="sd-auth-error" id="admin-email-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sd-auth-field" x-data="{ showPassword: false }">
                        <label class="sd-auth-label" for="admin-password">
                            Kata Sandi <span class="sd-auth-req" aria-hidden="true">*</span>
                        </label>
                        <div class="sd-auth-box">
                            <span class="material-symbols-outlined sd-auth-box-icon" aria-hidden="true">lock</span>
                            <input
                                wire:model.blur="data.password"
                                :type="showPassword ? 'text' : 'password'"
                                class="sd-auth-input sd-auth-input--toggle"
                                id="admin-password"
                                name="password"
                                type="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                                @error('data.password') aria-invalid="true" aria-describedby="admin-password-error" @enderror
                            />
                            <button
                                type="button"
                                class="sd-auth-toggle"
                                id="toggle-password"
                                @click="showPassword = !showPassword"
                                :aria-pressed="showPassword ? 'true' : 'false'"
                                aria-label="Tampilkan atau sembunyikan kata sandi"
                            >
                                <span class="material-symbols-outlined" x-text="showPassword ? 'visibility_off' : 'visibility'" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                        @error('data.password')
                            <p class="sd-auth-error" id="admin-password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sd-auth-row">
                        <label class="sd-auth-check">
                            <input wire:model="data.remember" id="remember-me" name="remember" type="checkbox" checked />
                            <span>Ingat saya</span>
                        </label>
                        <a class="sd-auth-link" href="#">Lupa kata sandi?</a>
                    </div>

                    <button
                        class="sd-btn sd-btn-primary sd-auth-submit"
                        id="login-submit-btn"
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="authenticate"
                    >
                        <span aria-live="polite">
                            <span wire:loading.remove wire:target="authenticate">Masuk ke Dasbor</span>
                            <span wire:loading wire:target="authenticate">Memverifikasi kredensial...</span>
                        </span>
                        <span class="material-symbols-outlined" aria-hidden="true" wire:loading.remove wire:target="authenticate">login</span>
                        <span class="material-symbols-outlined sd-spin" aria-hidden="true" wire:loading wire:target="authenticate">sync</span>
                    </button>

                    <p class="sd-auth-note">
                        <span class="material-symbols-outlined" aria-hidden="true" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                        <span>Akses khusus administrator</span>
                    </p>
                </form>

                <div class="sd-auth-cardfoot">
                    <a class="sd-auth-storelink" href="{{ url('/') }}">
                        <span class="material-symbols-outlined" aria-hidden="true">home</span>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="sd-auth-footer">
        <p>© {{ date('Y') }} ShopDress Indonesia. Hak cipta dilindungi.</p>
        <nav class="sd-auth-footer-links" aria-label="Tautan footer">
            <a href="#">Privasi</a>
            <span aria-hidden="true">&bull;</span>
            <a href="#">Ketentuan Layanan</a>
            <span aria-hidden="true">&bull;</span>
            <a href="#">Pusat Bantuan</a>
        </nav>
    </footer>
</div>
