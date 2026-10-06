<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Background Gradasi Modern -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-900 via-blue-700 to-indigo-800">
            
            <!-- Logo di Halaman Login -->
            <div class="mb-8 text-center">
                <a href="/" class="flex flex-col items-center gap-3">
                    <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm border border-white/30 shadow-xl">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <span class="font-extrabold text-4xl text-white tracking-tight drop-shadow-md">INVENTARIS <span class="text-blue-300">TMB</span></span>
                </a>
            </div>

            <!-- Kotak Form Login -->
            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-3xl border border-gray-100">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-white/70 text-sm">
                &copy; {{ date('Y') }} Sistem Manajemen Inventaris TMB
            </div>
        </div>
    </body>
</html>