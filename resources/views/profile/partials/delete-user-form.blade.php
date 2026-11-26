<section>
    <header>
        <h2 class="text-sm sm:text-base font-extrabold text-rose-700">Hapus Akun</h2>
        <p class="mt-0.5 text-[11px] sm:text-xs text-slate-600 font-medium leading-snug">
            Setelah akun dihapus, seluruh data dan akses terkait akun ini akan hilang secara permanen. Pastikan Anda sudah mencadangkan data penting sebelum melanjutkan.
        </p>
    </header>

    <div class="mt-4">
        <button type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="tmb-prof-btn-danger">
            Hapus Akun
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4 sm:p-6">
            @csrf
            @method('delete')

            <h2 class="text-sm sm:text-base font-extrabold text-slate-900">
                Yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-1 text-xs text-slate-600 leading-snug">
                Setelah akun dihapus, seluruh data terkait akun ini akan terhapus permanen. Masukkan kata sandi Anda untuk konfirmasi.
            </p>

            <div class="mt-4">
                <label for="delete_account_password" class="tmb-prof-label">Kata Sandi</label>
                <input id="delete_account_password"
                       name="password"
                       type="password"
                       class="tmb-prof-input"
                       placeholder="Masukkan kata sandi Anda" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 text-xs" />
            </div>

            <div class="mt-5 flex flex-col-reverse sm:flex-row justify-end gap-2">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="px-4 py-2 rounded-xl border border-slate-300 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>

                <button type="submit" class="tmb-prof-btn-danger">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>