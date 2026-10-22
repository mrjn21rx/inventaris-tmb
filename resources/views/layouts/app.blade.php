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
    <!-- Tambahkan x-data untuk kontrol sidebar di HP -->
    <body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarOpen: false }">
        
        <div class="flex h-screen overflow-hidden">
            
            <!-- Memanggil File Sidebar Navigasi -->
            @include('layouts.navigation')

            <!-- Area Konten Utama (Sebelah Kanan) -->
            <div class="flex-1 flex flex-col overflow-hidden">
                
                <!-- Header Atas (Profil User & Tombol Menu HP) -->
                <header class="bg-white shadow-sm border-b border-gray-100 flex items-center justify-between sm:justify-end px-4 py-3 sm:px-6 lg:px-8 z-10">
                    
                    <!-- Tombol Hamburger untuk Layar HP -->
                    <button @click="sidebarOpen = true" class="sm:hidden text-gray-500 hover:text-gray-700 focus:outline-none bg-gray-100 p-2 rounded-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Dropdown Profil User -->
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-blue-600 focus:outline-none transition bg-gray-50 px-4 py-2 rounded-xl border border-gray-200">
                                    <div class="bg-blue-100 text-blue-700 p-1 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>{{ Auth::user()->name }}</div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">Profil Saya</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                                        Keluar (Log Out)
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Area Halaman Dinamis -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    @isset($header)
                        <div class="bg-white border-b border-gray-100 px-8 py-5">
                            {{ $header }}
                        </div>
                    @endisset
                    
                    <div class="p-4 sm:p-8">
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
                    text: '{{ session('success') }}',
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
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#2563EB',
                });
            @endif
        </script>
    </body>
</html>