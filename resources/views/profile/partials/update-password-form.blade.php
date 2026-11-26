<section>
    <header>
        <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Perbarui Kata Sandi</h2>
        <p class="mt-0.5 text-[11px] sm:text-xs text-slate-500 font-medium">
            Gunakan kata sandi minimal 8 karakter agar akun tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-3.5 space-y-3">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="tmb-prof-label">Kata Sandi Saat Ini</label>
            <div class="tmb-prof-input-wrap">
                <input id="update_password_current_password"
                       name="current_password"
                       type="password"
                       class="tmb-prof-input has-toggle"
                       autocomplete="current-password" />
                <button type="button" data-toggle-prof-pass="update_password_current_password" class="tmb-prof-eye-btn" title="Tampilkan / Sembunyikan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs" />
        </div>

        <div>
            <label for="update_password_password" class="tmb-prof-label">Kata Sandi Baru</label>
            <div class="tmb-prof-input-wrap">
                <input id="update_password_password"
                       name="password"
                       type="password"
                       class="tmb-prof-input has-toggle"
                       autocomplete="new-password" />
                <button type="button" data-toggle-prof-pass="update_password_password" class="tmb-prof-eye-btn" title="Tampilkan / Sembunyikan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="tmb-prof-label">Konfirmasi Sandi Baru</label>
            <div class="tmb-prof-input-wrap">
                <input id="update_password_password_confirmation"
                       name="password_confirmation"
                       type="password"
                       class="tmb-prof-input has-toggle"
                       autocomplete="new-password" />
                <button type="button" data-toggle-prof-pass="update_password_password_confirmation" class="tmb-prof-eye-btn" title="Tampilkan / Sembunyikan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs" />
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit" class="tmb-prof-btn-dark">
                Simpan Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2200)"
                   class="text-xs text-emerald-700 font-bold">
                    Sandi berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>