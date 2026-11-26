<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#1d4ed8">
        <title>
            {{ request()->routeIs('password.request') ? 'Lupa Kata Sandi' : 'Login' }} - {{ config('app.name', 'Inventaris TMB') }}
        </title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/iconapk.png') }}?v=2">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="color: #f8fafc;" class="font-sans antialiased selection:bg-blue-600 selection:text-white">
        <!-- CONTAINER FULL LAYAR DENGAN BACKGROUND FOTO SAMPAI KE KANAN -->
        <div style="position: relative; min-height: 100dvh; width: 100%; background-image: url('{{ asset('images/bg-login.png') }}'); background-size: cover; background-position: center;"
             class="flex flex-col md:flex-row overflow-x-hidden">

            <!-- OVERLAY GRADASI MIDNIGHT NAVY AGAR MENYATU DENGAN FOTO & KONTRASTEKSTUAL -->
            <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(6, 10, 20, 0.78) 0%, rgba(15, 23, 42, 0.84) 55%, rgba(6, 10, 20, 0.90) 100%); z-index: 0;"></div>

            <!-- BAGIAN KIRI (50% LAYAR DESKTOP): BADGE SEDIKIT KE ATAS + UI/UX PEMISAH MODERN -->
            <div style="position: relative; z-index: 10; padding-top: max(1.75rem, env(safe-area-inset-top)); padding-bottom: max(1.75rem, env(safe-area-inset-bottom));"
                 class="hidden md:flex md:w-1/2 min-h-[100dvh] flex-col justify-center items-center px-4 sm:px-8 lg:px-14 select-none">

                <div style="width: 100%; max-width: 430px;"
                     class="flex flex-col items-center justify-center text-center">

                    <!-- Badge RRI Bukittinggi • TMB Sedikit Ke Atas -->
                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 15px; border-radius: 9999px; background-color: rgba(15, 23, 42, 0.76); border: 1px solid rgba(125, 211, 252, 0.35); color: #e0f2fe; white-space: nowrap; margin-bottom: 10px;"
                         class="text-[11px] font-extrabold uppercase tracking-wider shadow-sm backdrop-blur-xs">
                        <span style="width: 7px; height: 7px; border-radius: 9999px; background-color: #38bdf8; box-shadow: 0 0 8px rgba(56, 189, 248, 0.85); flex-shrink: 0;"></span>
                        <span>RRI Bukittinggi • TMB</span>
                    </div>

                    <!-- UI/UX Pemisah Modern: Garis Vertikal Bergradasi & Titik Aksen -->
                    <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 12px;" aria-hidden="true">
                        <div style="width: 1.5px; height: 18px; border-radius: 9999px; background: linear-gradient(180deg, rgba(56, 189, 248, 0.75) 0%, rgba(56, 189, 248, 0.15) 100%);"></div>
                        <span style="width: 5px; height: 5px; border-radius: 9999px; background-color: #38bdf8; box-shadow: 0 0 8px rgba(56, 189, 248, 0.8); margin-top: 3px;"></span>
                    </div>

                    <!-- Logo / Ikon Aplikasi -->
                    <div style="width: 58px; height: 58px; border-radius: 18px; background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); border: 1px solid rgba(125, 211, 252, 0.4); color: #ffffff; flex-shrink: 0; margin-bottom: 16px; box-shadow: 0 14px 30px -8px rgba(29, 78, 216, 0.65);"
                         class="flex items-center justify-center">
                        <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>

                    <!-- Judul & Deskripsi -->
                    <h1 style="color: #ffffff; text-shadow: 0 2px 16px rgba(0, 0, 0, 0.75); margin-bottom: 10px;"
                        class="font-extrabold text-4xl lg:text-[42px] tracking-tight leading-tight">
                        INVENTARIS <span style="color: #38bdf8;">TMB</span>
                    </h1>

                    <p style="color: #e2e8f0; text-shadow: 0 1px 10px rgba(0, 0, 0, 0.85); margin-bottom: 18px; max-width: 390px;"
                       class="text-sm font-medium leading-relaxed">
                        Sistem Manajemen Inventaris Alat Teknik dan Barang RRI Bukittinggi
                    </p>

                    <div style="width: 84px; height: 2px; border-radius: 9999px; background: linear-gradient(90deg, transparent 0%, #38bdf8 50%, transparent 100%); margin-bottom: 18px;"></div>

                    <!-- Micro Feature Pills -->
                    <div style="gap: 8px;" class="flex flex-wrap items-center justify-center">
                        <span style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.82) 0%, rgba(15, 23, 42, 0.68) 100%); border: 1px solid rgba(125, 211, 252, 0.22); color: #f1f5f9; padding: 5.5px 11px; border-radius: 9999px;"
                              class="inline-flex items-center gap-1.5 text-[11px] font-semibold backdrop-blur-xs shadow-2xs">
                            <svg style="width: 12px; height: 12px; color: #38bdf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pendataan Aset</span>
                        </span>

                        <span style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.82) 0%, rgba(15, 23, 42, 0.68) 100%); border: 1px solid rgba(125, 211, 252, 0.22); color: #f1f5f9; padding: 5.5px 11px; border-radius: 9999px;"
                              class="inline-flex items-center gap-1.5 text-[11px] font-semibold backdrop-blur-xs shadow-2xs">
                            <svg style="width: 12px; height: 12px; color: #38bdf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>Alat Teknik &amp; Barang</span>
                        </span>

                        <span style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.82) 0%, rgba(15, 23, 42, 0.68) 100%); border: 1px solid rgba(125, 211, 252, 0.22); color: #f1f5f9; padding: 5.5px 11px; border-radius: 9999px;"
                              class="inline-flex items-center gap-1.5 text-[11px] font-semibold backdrop-blur-xs shadow-2xs">
                            <svg style="width: 12px; height: 12px; color: #38bdf8;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Laporan Terkelola</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN (50% LAYAR DESKTOP): KARTU LOGIN GABUNGAN WARNA NAVY + COBALT + SKY -->
            <div style="position: relative; z-index: 10; padding-top: max(1.75rem, env(safe-area-inset-top)); padding-bottom: max(1.75rem, env(safe-area-inset-bottom));"
                 class="w-full md:w-1/2 min-h-[100dvh] flex flex-col justify-center items-center px-4 sm:px-8 lg:px-14">

                <div style="width: 100%; max-width: 430px;" class="flex flex-col items-center">

                    <!-- Identitas Versi HP (Android & iOS) -->
                    <div class="md:hidden mb-6 text-center flex flex-col items-center">
                        <div style="width: 48px; height: 48px; border-radius: 16px; background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;"
                             class="flex items-center justify-center shadow-lg mb-2.5">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h1 style="color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.7);" class="font-extrabold text-xl tracking-tight">
                            INVENTARIS <span style="color: #38bdf8;">TMB</span>
                        </h1>
                        <p style="color: #e2e8f0; text-shadow: 0 1px 6px rgba(0,0,0,0.8);" class="text-xs font-medium mt-0.5">
                            RRI Bukittinggi
                        </p>
                    </div>

                    <!-- Card Login Gabungan Midnight Navy + Slate + Sky Accent -->
                    <div style="background: linear-gradient(165deg, rgba(15, 23, 42, 0.88) 0%, rgba(12, 20, 38, 0.84) 55%, rgba(17, 28, 52, 0.88) 100%); border: 1px solid rgba(125, 211, 252, 0.22); border-radius: 28px; overflow: hidden; box-shadow: 0 25px 55px -12px rgba(2, 6, 23, 0.8);"
                         class="w-full backdrop-blur-xl">

                        <!-- Top Accent Bar: Cobalt Blue -> Sky Cyan -->
                        <div style="height: 4px; background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 50%, #38bdf8 100%);"></div>

                        <div style="padding: 26px 28px;">
                            <!-- Header Bar di Dalam Card -->
                            <div style="border-bottom: 1px solid rgba(148, 163, 184, 0.16); padding-bottom: 14px; margin-bottom: 22px;"
                                 class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span style="width: 8px; height: 8px; border-radius: 9999px; background-color: #38bdf8;" class="inline-block flex-shrink-0"></span>
                                    <span style="color: #e2e8f0;" class="text-xs font-bold tracking-tight">
                                        Portal Petugas Inventaris
                                    </span>
                                </div>
                                <span style="background-color: rgba(2, 132, 199, 0.18); border: 1px solid rgba(125, 211, 252, 0.3); color: #7dd3fc; padding: 3.5px 11px; border-radius: 9999px;"
                                      class="text-[11px] font-extrabold">
                                    RRI Bukittinggi
                                </span>
                            </div>

                            {{ $slot }}

                            <!-- Footer Ringkas di Bagian Bawah Card -->
                            <div style="border-top: 1px solid rgba(148, 163, 184, 0.16); padding-top: 14px; margin-top: 22px; color: #94a3b8;"
                                 class="flex items-center justify-between gap-2 text-[11px] font-medium select-none">
                                <span>Divisi Teknik &amp; Media Baru</span>
                                <span>&copy; {{ date('Y') }} RRI Bukittinggi</span>
                            </div>
                        </div>
                    </div>

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
                    text: @json($errors->first()),
                    confirmButtonColor: '#2563EB',
                    background: '#0f172a',
                    color: '#f8fafc',
                    backdrop: 'rgba(2, 6, 23, 0.65)'
                });
            @endif

            // Pop-up sukses (contoh: berhasil kirim link reset sandi)
            @if(session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('status')),
                    confirmButtonColor: '#2563EB',
                    background: '#0f172a',
                    color: '#f8fafc',
                });
            @endif
        </script>
    </body>
</html>