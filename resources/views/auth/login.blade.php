<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-gray-800">Selamat Datang!</h2>
            <p class="text-gray-500 text-sm mt-1">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
            oninvalid="this.setCustomValidity('Harap isi alamat email Anda.')" 
            oninput="this.setCustomValidity('')"
            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password (Dengan Fitur Intip/Ikon Mata) -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
            <div class="relative mt-1">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                oninvalid="this.setCustomValidity('Harap isi kata sandi Anda.')" 
                oninput="this.setCustomValidity('')"
                class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition pr-10">
                
                <!-- Tombol Ikon Mata -->
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600 focus:outline-none transition">
                    <!-- Ikon Mata Terbuka (Muncul saat password disembunyikan) -->
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Ikon Mata Dicoret (Muncul saat password ditampilkan) -->
                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.922m1.284-1.284A10.007 10.007 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.021 10.021 0 01-4.132 5.411m0 0L21 21m-6-6l-6-6m0 0L3 3" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="block">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline transition" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                MASUK
            </button>
        </div>
    </form>
</x-guest-layout>