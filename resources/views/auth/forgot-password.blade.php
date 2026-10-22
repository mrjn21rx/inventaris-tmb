<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-extrabold text-gray-800">Lupa Kata Sandi?</h2>
        <p class="text-gray-500 text-sm mt-2 leading-relaxed">
            Tidak masalah. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col gap-4">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                KIRIM TAUTAN RESET SANDI
            </button>
            
            <!-- Tombol kembali ke login untuk mempermudah user -->
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 text-center transition">
                &larr; Kembali ke halaman Login
            </a>
        </div>
    </form>
</x-guest-layout>