<!-- SIDEBAR UNTUK DESKTOP -->
<nav class="hidden sm:flex flex-col w-72 bg-white border-r border-gray-100 h-full shadow-sm z-20">
    
    <!-- Logo -->
    <div class="h-[73px] flex items-center px-6 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition">
            <div class="bg-blue-600 p-2 rounded-xl shadow-md shadow-blue-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <span class="font-extrabold text-xl text-gray-800 tracking-tight">INVENTARIS <span class="text-blue-600">TMB</span></span>
        </a>
    </div>

    <!-- Menu Links -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="font-semibold text-sm">Dashboard</span>
        </a>

        <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <span class="font-semibold text-sm">Data Barang</span>
        </a>

        @if(Auth::user()->role === 'super-admin')
            <div class="pt-6 pb-2 px-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Super Admin Area</div>
            
            <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="font-semibold text-sm">Kategori</span>
            </a>

            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-semibold text-sm">Manajemen Akun</span>
            </a>

            <a href="{{ route('logs.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 {{ request()->routeIs('logs.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span class="font-semibold text-sm">Log Aktivitas</span>
            </a>
        @endif
    </div>
</nav>

<!-- SIDEBAR UNTUK LAYAR HP (MUNCUL SAAT TOMBOL DIKLIK) -->
<div x-show="sidebarOpen" class="fixed inset-0 z-50 sm:hidden" style="display: none;">
    <!-- Layar Gelap Latar Belakang -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" @click="sidebarOpen = false"></div>

    <!-- Panel Sidebar HP -->
    <nav x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl flex flex-col">
        
        <div class="h-[73px] flex items-center justify-between px-6 border-b border-gray-100">
            <span class="font-extrabold text-xl text-gray-800">INV <span class="text-blue-600">TMB</span></span>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-red-500 bg-gray-50 p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <!-- Link Menu HP (Isinya sama dengan Desktop) -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                <span class="font-semibold text-sm">Data Barang</span>
            </a>

            @if(Auth::user()->role === 'super-admin')
                <div class="pt-6 pb-2 px-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Super Admin Area</div>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <span class="font-semibold text-sm">Kategori</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <span class="font-semibold text-sm">Manajemen Akun</span>
                </a>
                <a href="{{ route('logs.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl {{ request()->routeIs('logs.*') ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                    <span class="font-semibold text-sm">Log Aktivitas</span>
                </a>
            @endif
        </div>
    </nav>
</div>