<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#ffffff">
        <title>
            @if(request()->is('items*'))
                Data Barang
            @elseif(request()->is('kategori*', 'categories*'))
                Kategori
            @elseif(request()->is('manajemen-akun*', 'users*', 'akun*'))
                Manajemen Akun
            @elseif(request()->is('log-aktivitas*', 'activity-logs*', 'logs*'))
                Log Aktivitas
            @else
                {{ $title ?? 'Dashboard' }}
            @endif
- {{ config('app.name', 'Inventaris TMB') }}
        </title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/iconapk.png') }}?v=2">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <style>
            [x-cloak] { display: none !important; }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-blue-600 selection:text-white" x-data="{ sidebarOpen: false }">

        <div class="flex h-[100dvh] w-full overflow-hidden bg-slate-50">

            <!-- Sidebar Navigasi (Desktop & Drawer HP) -->
            @include('layouts.navigation')

            <!-- Area Konten Utama -->
            <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

                <!-- Header Atas -->
                <header style="padding-top: max(0.75rem, env(safe-area-inset-top));"
                        class="bg-white/95 backdrop-blur-xs shadow-2xs border-b border-slate-200/70 flex items-center justify-between sm:justify-end px-4 pb-3 sm:py-3.5 sm:px-6 lg:px-8 z-10 flex-shrink-0 gap-3">

                    <!-- Tombol Hamburger untuk Layar HP -->
                    <button type="button"
                            @click="sidebarOpen = true"
                            aria-label="Buka menu navigasi"
                            class="sm:hidden min-w-[44px] min-h-[44px] inline-flex items-center justify-center text-slate-600 hover:text-blue-600 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 bg-slate-100 hover:bg-blue-50 rounded-xl transition touch-manipulation flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Dropdown Profil User -->
                    <div class="flex items-center flex-shrink-0">
                        <x-dropdown align="right" width="64">
                            <x-slot name="trigger">
                                <button type="button"
                                        class="inline-flex items-center gap-2.5 sm:gap-3 text-sm font-bold text-slate-700 hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 transition bg-gradient-to-r from-slate-50 to-blue-50/50 px-3 py-1.5 sm:px-4 sm:py-2 rounded-2xl border-2 border-blue-200/90 hover:border-blue-500 shadow-2xs hover:shadow-md touch-manipulation active:scale-[0.99]">

                                    <!-- Badge Perisai Simetris -->
                                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-2xs flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>

                                    <!-- Nama Pengguna -->
                                    <span class="text-xs sm:text-sm font-black text-slate-900 tracking-tight truncate max-w-[125px] xs:max-w-[155px] sm:max-w-[220px]">
                                        {{ Auth::user()->name }}
                                    </span>

                                    <!-- Panah Dropdown -->
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>

                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @php
                                    $authUser = auth()->user();
                                    $nameText = trim((string) ($authUser->name ?? 'Pengguna'));
                                    $emailText = trim((string) ($authUser->email ?? ''));
                                    $roleSlug = strtolower(trim((string) ($authUser->role ?? 'admin')));
                                    $isSuper = in_array($roleSlug, ['super-admin', 'superadmin', 'super_admin'], true);
                                    $roleText = $isSuper ? 'Super Admin' : 'Admin Operasional';

                                    $parts = preg_split('/\s+/', $nameText) ?: [];
                                    $initials = strtoupper(
                                        substr($parts[0] ?? 'U', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : substr($parts[0] ?? '', 1, 1))
                                    );
                                @endphp

                                <div style="width: 100%; max-width: 260px; padding: 8px; background-color: #ffffff; border-radius: 16px; box-sizing: border-box;">
                                    <!-- Kartu Identitas Akun -->
                                    <div style="padding: 10px 11px; border-radius: 12px; background: linear-gradient(135deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%); color: #ffffff; overflow: hidden; box-sizing: border-box;">
                                        <div style="display: flex; align-items: center; gap: 9px; min-width: 0;">
                                            <div style="width: 32px; height: 32px; border-radius: 9px; background: rgba(255, 255, 255, 0.16); border: 1px solid rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 900; flex-shrink: 0;">
                                                {{ $initials }}
                                            </div>

                                            <div style="min-width: 0; flex: 1;">
                                                <div style="font-size: 12px; font-weight: 800; color: #ffffff; line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $nameText }}
                                                </div>
                                                <div style="font-size: 10.5px; font-weight: 500; color: #dbeafe; line-height: 1.3; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $emailText }}
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-top: 8px; padding-top: 7px; border-top: 1px solid rgba(255, 255, 255, 0.15); display: flex; align-items: center; justify-content: space-between; gap: 6px; flex-wrap: wrap;">
                                            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 10px; font-weight: 700; color: #6ee7b7; white-space: nowrap;">
                                                <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #34d399; flex-shrink: 0;"></span>
                                                <span>Aktif</span>
                                            </span>

                                            <span style="display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 6px; font-size: 9.5px; font-weight: 800; white-space: nowrap; {{ $isSuper ? 'background-color: #eef2ff; color: #3730a3;' : 'background-color: #eff6ff; color: #1d4ed8;' }}">
                                                {{ $roleText }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Menu Navigasi -->
                                    <div style="margin-top: 6px; display: flex; flex-direction: column; gap: 2px;">
                                        <a href="{{ route('profile.edit') }}"
                                           style="display: flex; align-items: center; gap: 9px; padding: 8px 9px; border-radius: 10px; text-decoration: none; color: #1e293b;"
                                           class="hover:bg-blue-50 hover:text-blue-700 transition">
                                            <span style="width: 28px; height: 28px; border-radius: 9px; background-color: #eff6ff; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </span>
                                            <span style="min-width: 0; flex: 1;">
                                                <span style="display: block; font-size: 12px; font-weight: 800; line-height: 1.25;">Profil Saya</span>
                                                <span style="display: block; font-size: 10px; font-weight: 500; color: #64748b; line-height: 1.25; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    Edit profil &amp; sandi
                                                </span>
                                            </span>
                                        </a>

                                        <div style="margin: 3px 6px; border-top: 1px solid #f1f5f9;"></div>

                                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                            @csrf
                                            <button type="submit"
                                                    style="width: 100%; display: flex; align-items: center; gap: 9px; padding: 8px 9px; border-radius: 10px; border: none; background: transparent; text-align: left; color: #dc2626; cursor: pointer;"
                                                    class="hover:bg-rose-50 hover:text-rose-700 transition">
                                                <span style="width: 28px; height: 28px; border-radius: 9px; background-color: #fff1f2; color: #e11d48; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                </span>
                                                <span style="min-width: 0; flex: 1;">
                                                    <span style="display: block; font-size: 12px; font-weight: 800; line-height: 1.25;">Keluar (Log Out)</span>
                                                    <span style="display: block; font-size: 10px; font-weight: 500; color: #f87171; line-height: 1.25; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        Akhiri sesi akun
                                                    </span>
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Area Halaman Dinamis -->
                <main style="padding-bottom: max(1rem, env(safe-area-inset-bottom));"
                      class="flex-1 overflow-x-hidden overflow-y-auto overscroll-contain bg-slate-50">
                    @isset($header)
                        <div class="bg-white border-b border-slate-200/70 px-4 py-3.5 sm:px-6 lg:px-8 sm:py-5">
                            {{ $header }}
                        </div>
                    @endisset

                    <div class="p-4 sm:p-6 lg:p-8 max-w-full">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- SweetAlert2 Library -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Pop-up sukses (otomatis hilang dalam 2,5 detik)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            @endif

            // Pop-up error di dalam sistem
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: @json(session('error')),
                    confirmButtonColor: '#2563EB',
                });
            @endif
        </script>
    </body>
</html>