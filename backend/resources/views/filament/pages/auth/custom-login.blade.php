<div class="min-h-screen bg-gradient-to-b from-primary-50 to-surface font-body-default text-on-surface antialiased flex flex-col justify-between selection:bg-primary-100 selection:text-primary-950">
    <!-- Header Brand -->
    <header class="w-full pt-8 px-gutter flex items-center justify-center">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 transition-transform hover:scale-105 duration-200">
            <div class="w-9 h-9 rounded-lg bg-primary-600 flex items-center justify-center shadow-[0_1px_2px_0_rgba(0,0,0,0.05)] text-surface-white">
                <span class="material-symbols-outlined text-surface-white text-[20px]">app_registration</span>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-page-title text-base text-on-surface font-bold tracking-tight leading-none">ShopDress</span>
                <span class="font-caption text-[11px] text-text-muted tracking-wide uppercase mt-0.5">Admin Panel</span>
            </div>
        </a>
    </header>

    <!-- Main Container -->
    <main class="w-full flex-1 flex items-center justify-center px-gutter py-8">
        <div class="w-full max-w-[440px]">
            <div class="flex flex-col w-full items-center justify-center">
                <!-- Decorative Ambient Glows contained inside parent -->
                <div class="relative w-full max-w-[420px]">
                    <div class="absolute -top-16 -left-16 w-48 h-48 bg-primary-100 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
                    <div class="absolute -bottom-12 -right-12 w-52 h-52 bg-primary-200 rounded-full blur-3xl opacity-40 pointer-events-none"></div>
                    
                    <!-- Main Filament v3-Styled Authentication Card -->
                    <div class="relative bg-surface-white rounded-2xl shadow-xl shadow-primary-950/5 p-8 sm:p-10 transition-all duration-300">
                        
                        <!-- Top Brand Presentation -->
                        <div class="flex flex-col items-center text-center">
                            <!-- Stylized Hanger/Dress Icon Badge -->
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-primary-700 via-primary-600 to-primary-500 flex items-center justify-center shadow-md shadow-primary-600/20 mb-4 transition-transform hover:scale-105 duration-200 text-surface-white">
                                <span class="material-symbols-outlined text-surface-white text-[28px]" style="font-variation-settings: 'FILL' 1;">checkroom</span>
                            </div>
                            
                            <div class="flex items-center justify-center gap-2">
                                <span class="font-page-title text-page-title text-on-surface font-bold tracking-tight">ShopDress</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-badge-label font-semibold bg-primary-50 text-primary-700 tracking-wide uppercase">
                                    Admin Panel
                                </span>
                            </div>
                            
                            <!-- Main Headings -->
                            <h1 class="font-page-title text-page-title text-on-surface font-bold tracking-tight mt-5">
                                Masuk ke Panel Admin
                            </h1>
                            <p class="font-body-default text-body-sm text-text-muted mt-1.5">
                                Kelola katalog, inventaris, dan pesanan busana Anda
                            </p>
                        </div>

                        <!-- Authentication Form with Livewire -->
                        <form wire:submit="authenticate" class="mt-8 space-y-5" id="admin-login-form">
                            <!-- Email Input Field -->
                            <div class="space-y-1.5 text-left">
                                <label class="block font-body-bold text-body-sm text-on-surface" for="admin-email">
                                    Alamat Email <span class="text-error font-body-bold">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm bg-surface-white group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted group-focus-within:text-primary-600 transition-colors">
                                        <span class="material-symbols-outlined text-[19px]">mail</span>
                                    </div>
                                    <input 
                                        wire:model="data.email" 
                                        class="block w-full pl-10 pr-3.5 py-2.5 text-on-surface placeholder:text-text-muted/60 font-body-default text-body-default rounded-xl bg-surface-white focus:outline-none focus:ring-2 focus:ring-primary-600 focus:bg-surface-white transition duration-150" 
                                        id="admin-email" 
                                        name="email" 
                                        onblur="this.style.boxShadow='inset 0 0 0 1px #CBD5E1';" 
                                        onfocus="this.style.boxShadow='inset 0 0 0 2px #6D28D9';" 
                                        placeholder="admin@shopdress.id" 
                                        required 
                                        style="box-shadow: inset 0 0 0 1px #CBD5E1;" 
                                        type="email" 
                                        value="admin@shopdress.id"
                                    />
                                </div>
                                @error('data.email')
                                    <p class="text-error text-[12px] font-caption mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Input Field -->
                            <div class="space-y-1.5 text-left" x-data="{ showPassword: false }">
                                <label class="block font-body-bold text-body-sm text-on-surface" for="admin-password">
                                    Kata Sandi <span class="text-error font-body-bold">*</span>
                                </label>
                                <div class="relative rounded-xl shadow-sm bg-surface-white group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-text-muted group-focus-within:text-primary-600 transition-colors">
                                        <span class="material-symbols-outlined text-[19px]">lock</span>
                                    </div>
                                    <input 
                                        wire:model="data.password" 
                                        :type="showPassword ? 'text' : 'password'" 
                                        class="block w-full pl-10 pr-10 py-2.5 text-on-surface placeholder:text-text-muted/60 font-body-default text-body-default rounded-xl bg-surface-white focus:outline-none focus:ring-2 focus:ring-primary-600 transition duration-150" 
                                        id="admin-password" 
                                        name="password" 
                                        onblur="this.style.boxShadow='inset 0 0 0 1px #CBD5E1';" 
                                        onfocus="this.style.boxShadow='inset 0 0 0 2px #6D28D9';" 
                                        placeholder="••••••••" 
                                        required 
                                        style="box-shadow: inset 0 0 0 1px #CBD5E1;" 
                                        type="password"
                                    />
                                    <button 
                                        aria-label="Tampilkan atau sembunyikan kata sandi" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-text-muted hover:text-on-surface focus:outline-none cursor-pointer transition-colors" 
                                        id="toggle-password" 
                                        @click="showPassword = !showPassword" 
                                        type="button"
                                    >
                                        <span class="material-symbols-outlined text-[20px]" id="password-eye-icon" x-text="showPassword ? 'visibility_off' : 'visibility'">visibility</span>
                                    </button>
                                </div>
                                @error('data.password')
                                    <p class="text-error text-[12px] font-caption mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Utility Row: Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between pt-0.5">
                                <label class="flex items-center gap-2 cursor-pointer select-none group">
                                    <input 
                                        wire:model="data.remember" 
                                        class="w-4 h-4 rounded text-primary-600 bg-surface-white focus:ring-primary-500 focus:ring-offset-0 cursor-pointer transition accent-primary-600" 
                                        id="remember-me" 
                                        name="remember" 
                                        type="checkbox"
                                        checked
                                    />
                                    <span class="font-body-medium text-body-sm text-text-muted group-hover:text-on-surface transition-colors">
                                        Ingat saya
                                    </span>
                                </label>
                                <a class="font-body-bold text-body-sm text-primary-600 hover:text-primary-700 transition-colors focus:outline-none focus:underline" href="#">
                                    Lupa kata sandi?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button 
                                    class="w-full py-3 px-4 rounded-xl bg-primary-600 hover:bg-primary-700 active:bg-primary-950 text-surface-white font-body-bold text-body-default shadow-md shadow-primary-600/25 transition-all duration-150 flex items-center justify-center gap-2 group cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-200 focus:ring-offset-2" 
                                    id="login-submit-btn" 
                                    type="submit"
                                >
                                    <span wire:loading.remove wire:target="authenticate" id="btn-text">Masuk ke Dasbor</span>
                                    <span wire:loading wire:target="authenticate">Memverifikasi kredensial...</span>
                                    <span class="material-symbols-outlined text-[19px] group-hover:translate-x-0.5 transition-transform" wire:loading.remove wire:target="authenticate" id="btn-icon">login</span>
                                    <span class="material-symbols-outlined text-[19px] animate-spin" wire:loading wire:target="authenticate">sync</span>
                                </button>
                            </div>

                            <!-- Filament Security Trust Notice -->
                            <div class="pt-3">
                                <div class="flex items-center justify-center gap-2 py-2 px-3 rounded-lg bg-surface-container-low text-text-muted text-[11px] font-caption">
                                    <span class="material-symbols-outlined text-[15px] text-tertiary-container" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                                    <span class="tracking-tight">Dilindungi enkripsi SSL 256-bit &amp; Filament Guard</span>
                                </div>
                            </div>
                        </form>

                        <!-- Store Return & Storefront Link -->
                        <div class="mt-8 pt-6 border-t border-border-subtle flex flex-col items-center text-center gap-3">
                            <a class="inline-flex items-center gap-1.5 font-body-bold text-body-sm text-on-surface hover:text-primary-600 transition-colors group" href="{{ url('/') }}">
                                <span class="material-symbols-outlined text-[17px] group-hover:-translate-x-0.5 transition-transform">storefront</span>
                                <span>Kembali ke Toko Online ShopDress</span>
                                <span class="text-text-muted group-hover:text-primary-600 transition-colors">→</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Server Status Indicator below card -->
                    <div class="mt-4 flex items-center justify-center gap-2 font-caption text-caption text-text-muted">
                        <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                        <span>Sistem Operasional v3.4.12 • Jakarta Region (JKT01)</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full pb-8 pt-4 px-gutter flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
        <p class="font-caption text-caption text-text-muted">© 2025 ShopDress Indonesia. Hak cipta dilindungi.</p>
        <div class="flex items-center gap-4">
            <a class="font-caption text-caption text-text-muted hover:text-on-surface transition-colors" href="#">Privasi</a>
            <span class="text-border-strong text-caption">•</span>
            <a class="font-caption text-caption text-text-muted hover:text-on-surface transition-colors" href="#">Ketentuan Layanan</a>
            <span class="text-border-strong text-caption">•</span>
            <a class="font-caption text-caption text-text-muted hover:text-on-surface transition-colors" href="#">Pusat Bantuan</a>
        </div>
    </footer>
</div>
