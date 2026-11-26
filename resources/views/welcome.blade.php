<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#1e40af">
        <title>{{ config('app.name', 'Inventaris TMB') }} - RRI Bukittinggi</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('images/iconapk.png') }}?v=2">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-blue-600 selection:text-white overflow-x-hidden">
        <div style="padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom);"
             class="min-h-[100dvh] flex flex-col justify-between bg-gradient-to-b from-blue-50/70 via-slate-50 to-slate-100/80">

            <!-- Header Atas -->
            <header class="w-full border-b border-slate-200/70 bg-white/90 backdrop-blur-xs sticky top-0 z-20">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-3">
                    <!-- Logo & Nama Aplikasi -->
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 no-underline min-w-0 group">
                        <div class="flex items-center justify-center flex-shrink-0 overflow-hidden" style="height: 32px;">
                            <img src="{{ asset('images/logo_rri.png') }}"
                                 alt="Logo RRI Bukittinggi"
                                 style="height: 32px; width: auto; max-width: none;"
                                 class="object-contain transition-transform duration-200 group-hover:scale-105">
                        </div>
                        <div class="flex items-center gap-1.5 leading-none whitespace-nowrap">
                            <span class="font-extrabold text-xs sm:text-sm text-slate-900 tracking-tight">INVENTARIS</span>
                            <span class="font-extrabold text-xs sm:text-sm text-blue-600 tracking-tight">TMB</span>
                        </div>
                    </a>

                    <!-- Tombol Aksi Auth -->
                    @if (Route::has('login'))
                        <nav aria-label="Akses Akun" class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="min-h-[42px] inline-flex items-center justify-center px-4 sm:px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-600/20 transition touch-manipulation">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="min-h-[42px] inline-flex items-center justify-center px-4 sm:px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-600/20 transition touch-manipulation">
                                    Masuk
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="min-h-[42px] inline-flex items-center justify-center px-3.5 sm:px-4 py-2 rounded-xl bg-white hover:bg-slate-100 active:scale-[0.98] text-slate-700 border border-slate-200 text-xs sm:text-sm font-bold transition touch-manipulation">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <!-- Konten Utama -->
            <main class="flex-1 flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
                <div class="w-full max-w-5xl bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/60 overflow-hidden grid grid-cols-1 lg:grid-cols-12">

                    <!-- Kolom Kiri: Informasi Sistem -->
                    <div class="lg:col-span-7 p-6 sm:p-8 lg:p-12 flex flex-col justify-center">
                        <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-[11px] font-extrabold uppercase tracking-wider mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            RRI Bukittinggi
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-3">
                            Sistem Inventaris <span class="text-blue-600">TMB</span>
                        </h1>

                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-6">
                            Platform pengelolaan data alat teknik, kondisi barang, dan administrasi inventaris secara terstruktur dan mudah diakses dari perangkat desktop maupun ponsel.
                        </p>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="min-h-[46px] inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-sm font-bold shadow-md shadow-blue-600/20 transition touch-manipulation">
                                        <span>Buka Dashboard</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="min-h-[46px] inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white text-sm font-bold shadow-md shadow-blue-600/20 transition touch-manipulation">
                                        <span>Masuk ke Sistem</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Ringkasan Fitur -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 p-6 sm:p-8 lg:p-10 text-white flex flex-col justify-center space-y-4">
                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/15">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-200 mb-1">Pendataan Terpusat</p>
                            <p class="text-sm text-white/95 leading-snug">Pencatatan barang dan status kondisi lebih rapi serta mudah ditelusuri.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/15">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-200 mb-1">Akses Multi-Perangkat</p>
                            <p class="text-sm text-white/95 leading-snug">Dioptimalkan untuk layar desktop, Android, maupun iOS tanpa merusak tata letak.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/15">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-blue-200 mb-1">Hak Akses Terkontrol</p>
                            <p class="text-sm text-white/95 leading-snug">Pemisahan tampilan dan kendali antara Admin Operasional dan Super Admin.</p>
                        </div>
                    </div>

                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 px-4 text-center text-xs sm:text-sm text-slate-400 font-medium">
                &copy; {{ date('Y') }} RRI Bukittinggi
            </footer>
        </div>
    </body>
</html>