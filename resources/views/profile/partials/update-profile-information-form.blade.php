<section>
    <header>
        <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Informasi Profil</h2>
        <p class="mt-0.5 text-[11px] sm:text-xs text-slate-500 font-medium">
            Perbarui nama lengkap dan alamat email akun Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-3.5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="tmb-prof-label">Nama Lengkap</label>
            <input id="name"
                   name="name"
                   type="text"
                   class="tmb-prof-input"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="tmb-prof-label">Alamat Email</label>
            <input id="email"
                   name="email"
                   type="email"
                   class="tmb-prof-input"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username" />
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit" class="tmb-prof-btn-primary">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2200)"
                   class="text-xs text-emerald-700 font-bold">
                    Berhasil disimpan.
                </p>
            @endif
        </div>
    </form>
</section>