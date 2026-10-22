<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Inventaris TMB') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('logo_rri.png') }}?v=2">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen bg-gray-50">
            
        <!-- BAGIAN KIRI: Logo & Branding (Menggunakan Gambar Background Kustom) -->
            <div class="hidden md:flex md:w-1/2 flex-col justify-center items-center p-12 text-center relative overflow-hidden bg-cover bg-center" 
                 style="background-image: url('{{ asset('images/bg-login.png') }}');">
                 
                <!-- Overlay Gradasi Biru Gelap (Penting agar teks tetap terbaca dengan kontras) -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900/95 via-blue-800/85 to-indigo-900/90 z-0"></div>

                <div class="relative z-10 flex flex-col items-center">
                    <div class="bg-white/20 p-6 rounded-3xl backdrop-blur-md border border-white/30 shadow-2xl mb-8">
                        <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h1 class="font-extrabold text-5xl text-white tracking-tight drop-shadow-lg mb-4">
                        INVENTARIS <span class="text-blue-300">TMB</span>
                    </h1>
                    <p class="text-blue-100 text-lg max-w-sm leading-relaxed drop-shadow-md">
                        Sistem Manajemen Inventaris Alat Teknik dan Barang RRI Bukittinggi
                    </p>
                </div>
            </div>

            <!-- BAGIAN KANAN: Area Form (Login & Lupa Sandi) -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 relative">
                
                <!-- Logo versi HP (Muncul jika dibuka lewat HP karena bagian kiri di-hidden) -->
                <div class="md:hidden mb-10 text-center flex flex-col items-center">
                    <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-200 mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h1 class="font-extrabold text-3xl text-gray-900 tracking-tight">
                        INV <span class="text-blue-600">TMB</span>
                    </h1>
                </div>

                <!-- Kotak Form Utama -->
                <div class="w-full max-w-md bg-white px-8 py-10 shadow-2xl shadow-gray-200 sm:rounded-3xl border border-gray-100">
                    <!-- Ini akan otomatis memanggil form login atau lupa sandi -->
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <div class="absolute bottom-6 text-gray-400 text-sm font-medium">
                    &copy; {{ date('Y') }} RRI Bukittinggi
                </div>
            </div>

        </div>
        <!-- SweetAlert2 Library -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            // Pop-up jika ada error (contoh: salah password)
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#2563EB',
                    backdrop: `rgba(0,0,123,0.1)`
                });
            @endif

            // Pop-up sukses (contoh: berhasil kirim link reset sandi)
            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#2563EB',
                });
            @endif
        </script>
    </body>
</html>