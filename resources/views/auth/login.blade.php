<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 18px;">
        @csrf

        <!-- Header Form Modern -->
        <div class="flex items-center justify-between gap-3" style="margin-bottom: 4px;">
            <div>
                <span style="background-color: rgba(2, 132, 199, 0.18); border: 1px solid rgba(125, 211, 252, 0.3); color: #7dd3fc; padding: 3px 10px; border-radius: 9999px;"
                      class="inline-block text-[10px] font-extrabold uppercase tracking-wider mb-2">
                    Autentikasi Sistem
                </span>
                <h2 style="color: #ffffff;" class="text-2xl font-extrabold tracking-tight leading-tight">
                    Selamat Datang!
                </h2>
                <p style="color: #94a3b8; margin-top: 4px;" class="text-xs sm:text-sm font-medium">
                    Silakan masuk ke akun Anda
                </p>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, rgba(29, 78, 216, 0.28) 0%, rgba(2, 132, 199, 0.2) 100%); border: 1px solid rgba(125, 211, 252, 0.32); color: #38bdf8; flex-shrink: 0;"
                 class="flex items-center justify-center">
                <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" style="color: #e2e8f0; margin-bottom: 7px;" class="block text-xs sm:text-sm font-semibold">
                Alamat Email
            </label>
            <div style="position: relative; width: 100%;">
                <span style="position: absolute; top: 0; left: 0; height: 100%; padding-left: 13px; color: #7dd3fc;"
                      class="inline-flex items-center justify-center pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="nama@rri.go.id"
                       oninvalid="this.setCustomValidity('Harap isi alamat email Anda.')"
                       oninput="this.setCustomValidity('')"
                       style="background-color: rgba(15, 23, 42, 0.68); border-color: rgba(148, 163, 184, 0.28); color: #f8fafc; padding: 11px 14px 11px 40px; border-radius: 13px;"
                       class="block w-full border text-sm font-medium placeholder:text-slate-400 focus:bg-slate-950/90 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/25 focus:outline-none transition">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password (Dengan Fitur Intip/Ikon Mata) -->
        <div x-data="{ show: false }">
            <label for="password" style="color: #e2e8f0; margin-bottom: 7px;" class="block text-xs sm:text-sm font-semibold">
                Kata Sandi
            </label>
            <div style="position: relative; width: 100%;">
                <span style="position: absolute; top: 0; left: 0; height: 100%; padding-left: 13px; color: #7dd3fc;"
                      class="inline-flex items-center justify-center pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                <input id="password"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Masukkan kata sandi"
                       oninvalid="this.setCustomValidity('Harap isi kata sandi Anda.')"
                       oninput="this.setCustomValidity('')"
                       style="background-color: rgba(15, 23, 42, 0.68); border-color: rgba(148, 163, 184, 0.28); color: #f8fafc; padding: 11px 42px 11px 40px; border-radius: 13px;"
                       class="block w-full border text-sm font-medium placeholder:text-slate-400 focus:bg-slate-950/90 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/25 focus:outline-none transition">

                <!-- Tombol Ikon Mata -->
                <button type="button"
                        @click="show = !show"
                        aria-label="Tampilkan atau sembunyikan kata sandi"
                        style="position: absolute; top: 0; right: 0; height: 100%; padding: 0 13px; color: #94a3b8;"
                        class="flex items-center justify-center hover:text-sky-300 focus:outline-none transition">
                    <!-- Ikon Mata Terbuka -->
                    <svg x-show="!show" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Ikon Mata Dicoret -->
                    <svg x-show="show" style="display: none;" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.922m1.284-1.284A10.007 10.007 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.021 10.021 0 01-4.132 5.411m0 0L21 21m-6-6l-6-6m0 0L3 3" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between gap-2" style="padding-top: 2px;">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me"
                       type="checkbox"
                       style="border-color: #64748b; accent-color: #2563eb;"
                       class="w-4 h-4 rounded border shadow-2xs focus:ring-sky-400"
                       name="remember">
                <span style="color: #cbd5e1;" class="ms-2 text-xs sm:text-sm font-medium">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a style="color: #7dd3fc;"
                   class="text-xs sm:text-sm font-bold hover:text-sky-200 hover:underline transition"
                   href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Catatan Akses Singkat Sebelum Tombol Masuk -->
        <div style="background-color: rgba(2, 132, 199, 0.1); border: 1px solid rgba(125, 211, 252, 0.18); border-radius: 12px; padding: 8px 12px; color: #cbd5e1;"
             class="flex items-center gap-2 text-[11px] font-medium">
            <svg style="width: 14px; height: 14px; color: #38bdf8; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Gunakan akun petugas terdaftar untuk mengakses sistem.</span>
        </div>

        <!-- Tombol Masuk -->
        <div style="padding-top: 2px;">
            <button type="submit"
                    style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #0284c7 100%); color: #ffffff; border-radius: 13px; padding: 12px 16px;"
                    class="w-full flex items-center justify-center border border-transparent shadow-lg text-sm font-extrabold tracking-wider hover:opacity-95 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400 transition">
                MASUK
            </button>
        </div>
    </form>
</x-guest-layout>