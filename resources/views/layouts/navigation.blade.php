@php
    $user = Auth::user();
    $isSuperAdmin = $user && (
        in_array($user->usertype ?? null, ['superadmin', 'super-admin'], true) ||
        in_array($user->role ?? null, ['superadmin', 'super-admin'], true)
    );
@endphp

<style>
    @media (min-width: 640px) {
        nav[aria-label="Navigasi Utama Desktop"] {
            display: flex !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 18.5rem !important;
            height: 100dvh !important;
        }
        nav[aria-label="Navigasi Utama Desktop"] + div,
        nav[aria-label="Navigasi Utama Desktop"] ~ div:not([role="dialog"]),
        nav[aria-label="Navigasi Utama Desktop"] ~ main {
            margin-left: 18.5rem !important;
            min-width: 0 !important;
        }
    }
</style>

<!-- SIDEBAR UNTUK DESKTOP -->
<nav aria-label="Navigasi Utama Desktop"
     style="position: fixed; top: 0; left: 0; height: 100dvh; width: 18.5rem; background-color: #ffffff;"
     class="hidden sm:flex flex-col bg-gradient-to-b from-white via-white to-slate-50/90 border-r border-slate-200/90 shadow-sm z-20 justify-between overflow-y-auto select-none">

    <div>
        <!-- Logo & Judul Aplikasi -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 flex-shrink-0 bg-white/95 backdrop-blur-xs overflow-hidden">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline group focus:outline-none">
                <div class="flex items-center justify-center flex-shrink-0 overflow-hidden" style="height: 38px;">
                    <img src="{{ asset('images/logo_rri.png') }}" alt="Logo RRI Bukittinggi" style="height: 38px; width: auto; max-width: none;" class="object-contain transition-transform duration-200 group-hover:scale-105">
                </div>
                <div class="flex items-center gap-1.5 leading-none whitespace-nowrap">
                    <span class="font-extrabold text-[15px] text-slate-900 tracking-tight">INVENTARIS</span>
                    <span class="font-extrabold text-[15px] text-blue-600 tracking-tight">TMB</span>
                </div>
            </a>
        </div>

        <!-- Menu Links Utama -->
        <div class="py-4 px-4 space-y-1.5">

            <!-- Menu Dashboard -->
            @php $isDashboardActive = request()->routeIs('dashboard'); @endphp
            <a href="{{ route('dashboard') }}"
               @if($isDashboardActive) aria-current="page" @endif
               style="{{ $isDashboardActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'color: #334155;' }}"
               class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl font-bold text-sm transition-all {{ $isDashboardActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100/90 hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span style="color: inherit;">Dashboard</span>
            </a>

            <!-- Menu Data Barang -->
            @php $isItemsActive = request()->routeIs('items.*'); @endphp
            <a href="{{ route('items.index') }}"
               @if($isItemsActive) aria-current="page" @endif
               style="{{ $isItemsActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'color: #334155;' }}"
               class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl font-bold text-sm transition-all {{ $isItemsActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100/90 hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span style="color: inherit;">Data Barang</span>
            </a>

            <!-- MENU KHUSUS SUPER ADMIN -->
            @if($isSuperAdmin)
                <div class="pt-4 pb-1.5 px-4 flex items-center gap-2">
                    <p class="text-[11px] font-black uppercase tracking-wider text-slate-400 whitespace-nowrap">Super Admin Area</p>
                    <span class="h-px flex-1 bg-slate-200/80"></span>
                </div>

                @php $isCategoriesActive = request()->routeIs('categories.*'); @endphp
                <a href="{{ route('categories.index') }}"
                   @if($isCategoriesActive) aria-current="page" @endif
                   style="{{ $isCategoriesActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'color: #334155;' }}"
                   class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl font-bold text-sm transition-all {{ $isCategoriesActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100/90 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span style="color: inherit;">Kategori</span>
                </a>

                @if(Route::has('admin.users') || Route::has('users.index'))
                    @php $isUsersActive = request()->routeIs('admin.users*') || request()->routeIs('users.*'); @endphp
                    <a href="{{ Route::has('admin.users') ? route('admin.users') : route('users.index') }}"
                       @if($isUsersActive) aria-current="page" @endif
                       style="{{ $isUsersActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'color: #334155;' }}"
                       class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl font-bold text-sm transition-all {{ $isUsersActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100/90 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span style="color: inherit;">Manajemen Akun</span>
                    </a>
                @endif

                @if(Route::has('admin.activity-logs') || Route::has('logs.index'))
                    @php $isLogsActive = request()->routeIs('admin.activity-logs*') || request()->routeIs('logs.*'); @endphp
                    <a href="{{ Route::has('admin.activity-logs') ? route('admin.activity-logs') : route('logs.index') }}"
                       @if($isLogsActive) aria-current="page" @endif
                       style="{{ $isLogsActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'color: #334155;' }}"
                       class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl font-bold text-sm transition-all {{ $isLogsActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100/90 hover:text-blue-600' }}">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span style="color: inherit;">Log Aktivitas</span>
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- WIDGET BAWAH MODERN -->
    @if($isSuperAdmin)
        <div style="background: linear-gradient(145deg, #f8fafc 0%, #eff6ff 100%); border-color: #dbeafe; color: #0f172a; padding: 14px 16px; margin: 12px 16px 18px 16px; border-radius: 16px; border-width: 1px; text-align: center;"
             class="shadow-xs flex-shrink-0 relative overflow-hidden">
            <div style="height: 3px; background: linear-gradient(90deg, #1d4ed8 0%, #38bdf8 100%); position: absolute; top: 0; left: 0; right: 0;"></div>

            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100/90 text-blue-700 text-[10px] font-extrabold uppercase tracking-wider mb-1.5">
                <span style="width: 5px; height: 5px; border-radius: 9999px; background-color: #0284c7;"></span>
                <span>Akses Penuh</span>
            </div>

            <div style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;"
                 class="w-9 h-9 mx-auto mb-1.5 rounded-xl flex items-center justify-center shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <p style="color: #0f172a;" class="text-xs font-black tracking-tight leading-tight">Super Administrator</p>
            <p style="color: #64748b;" class="text-[11px] font-medium mt-1 leading-snug">Kontrol penuh sistem inventaris &amp; monitoring keamanan TMB RRI Bukittinggi.</p>
        </div>
    @else
        <div style="background: linear-gradient(145deg, #eff6ff 0%, #f0f9ff 100%); border-color: #bfdbfe; color: #0f172a; padding: 14px 16px; margin: 12px 16px 18px 16px; border-radius: 16px; border-width: 1px; text-align: center;"
             class="shadow-xs flex-shrink-0 relative overflow-hidden">
            <div style="height: 3px; background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%); position: absolute; top: 0; left: 0; right: 0;"></div>

            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-sky-100/90 text-sky-800 text-[10px] font-extrabold uppercase tracking-wider mb-1.5">
                <span style="width: 5px; height: 5px; border-radius: 9999px; background-color: #2563eb;"></span>
                <span>Operasional</span>
            </div>

            <div style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;"
                 class="w-9 h-9 mx-auto mb-1.5 rounded-xl flex items-center justify-center shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p style="color: #0f172a;" class="text-xs font-black tracking-tight leading-tight">Admin Operasional</p>
            <p style="color: #475569;" class="text-[11px] font-medium mt-1 leading-snug">Kelola data inventaris harian TMB RRI Bukittinggi dengan optimal.</p>
        </div>
    @endif
</nav>

<!-- DRAWER NAVIGASI UNTUK LAYAR HP (ANDROID & IOS) -->
<div x-show="sidebarOpen"
     x-cloak
     @keydown.escape.window="sidebarOpen = false"
     class="fixed inset-0 z-50 sm:hidden"
     role="dialog"
     aria-modal="true"
     aria-label="Menu Navigasi">
    <!-- Backdrop Gelap -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/55 backdrop-blur-xs"
         @click="sidebarOpen = false"></div>

    <!-- Panel Drawer HP (SOLID PUTIH TIDAK TEMBUS PANDANG) -->
    <nav x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         style="padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); min-height: 100dvh; background-color: #ffffff; opacity: 1;"
         class="fixed inset-y-0 left-0 w-[min(19.5rem,85vw)] max-w-xs bg-white shadow-2xl border-r border-slate-200/90 flex flex-col z-50 justify-between overflow-y-auto overscroll-contain select-none">

        <div style="background-color: #ffffff;">
            <!-- Header Drawer HP -->
            <div style="background-color: #ffffff;" class="h-16 flex items-center justify-between px-4 border-b border-slate-100 flex-shrink-0 overflow-hidden">
                <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-2.5 no-underline min-w-0">
                    <div class="flex items-center justify-center flex-shrink-0 overflow-hidden" style="height: 34px;">
                        <img src="{{ asset('images/logo_rri.png') }}" alt="Logo RRI Bukittinggi" style="height: 34px; width: auto; max-width: none;" class="object-contain">
                    </div>
                    <div class="flex items-center gap-1 leading-none whitespace-nowrap">
                        <span class="font-extrabold text-sm text-slate-900 tracking-tight">INVENTARIS</span>
                        <span class="font-extrabold text-sm text-blue-600 tracking-tight">TMB</span>
                    </div>
                </a>
                <button type="button"
                        @click="sidebarOpen = false"
                        aria-label="Tutup menu"
                        class="min-w-[44px] min-h-[44px] inline-flex items-center justify-center text-slate-500 hover:text-rose-600 active:scale-95 bg-slate-100/90 hover:bg-rose-50 rounded-xl transition touch-manipulation flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu Links HP -->
            <div style="background-color: #ffffff;" class="py-3.5 px-3 space-y-1.5">
                @php $isMobileDashboardActive = request()->routeIs('dashboard'); @endphp
                <a href="{{ route('dashboard') }}"
                   @click="sidebarOpen = false"
                   @if($isMobileDashboardActive) aria-current="page" @endif
                   style="{{ $isMobileDashboardActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'background-color: #ffffff; color: #334155;' }}"
                   class="flex items-center gap-3.5 min-h-[46px] px-4 py-2.5 rounded-xl font-bold text-sm transition-all touch-manipulation active:scale-[0.99] {{ $isMobileDashboardActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span style="color: inherit;">Dashboard</span>
                </a>

                @php $isMobileItemsActive = request()->routeIs('items.*'); @endphp
                <a href="{{ route('items.index') }}"
                   @click="sidebarOpen = false"
                   @if($isMobileItemsActive) aria-current="page" @endif
                   style="{{ $isMobileItemsActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'background-color: #ffffff; color: #334155;' }}"
                   class="flex items-center gap-3.5 min-h-[46px] px-4 py-2.5 rounded-xl font-bold text-sm transition-all touch-manipulation active:scale-[0.99] {{ $isMobileItemsActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span style="color: inherit;">Data Barang</span>
                </a>

                @if($isSuperAdmin)
                    <div class="pt-3.5 pb-1.5 px-4 flex items-center gap-2">
                        <p class="text-[11px] font-black uppercase tracking-wider text-slate-400 whitespace-nowrap">Super Admin Area</p>
                        <span class="h-px flex-1 bg-slate-200/80"></span>
                    </div>

                    @php $isMobileCategoriesActive = request()->routeIs('categories.*'); @endphp
                    <a href="{{ route('categories.index') }}"
                       @click="sidebarOpen = false"
                       @if($isMobileCategoriesActive) aria-current="page" @endif
                       style="{{ $isMobileCategoriesActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'background-color: #ffffff; color: #334155;' }}"
                       class="flex items-center gap-3.5 min-h-[46px] px-4 py-2.5 rounded-xl font-bold text-sm transition-all touch-manipulation active:scale-[0.99] {{ $isMobileCategoriesActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span style="color: inherit;">Kategori</span>
                    </a>

                    @if(Route::has('admin.users') || Route::has('users.index'))
                        @php $isMobileUsersActive = request()->routeIs('admin.users*') || request()->routeIs('users.*'); @endphp
                        <a href="{{ Route::has('admin.users') ? route('admin.users') : route('users.index') }}"
                           @click="sidebarOpen = false"
                           @if($isMobileUsersActive) aria-current="page" @endif
                           style="{{ $isMobileUsersActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'background-color: #ffffff; color: #334155;' }}"
                           class="flex items-center gap-3.5 min-h-[46px] px-4 py-2.5 rounded-xl font-bold text-sm transition-all touch-manipulation active:scale-[0.99] {{ $isMobileUsersActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100' }}">
                            <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span style="color: inherit;">Manajemen Akun</span>
                        </a>
                    @endif

                    @if(Route::has('admin.activity-logs') || Route::has('logs.index'))
                        @php $isMobileLogsActive = request()->routeIs('admin.activity-logs*') || request()->routeIs('logs.*'); @endphp
                        <a href="{{ Route::has('admin.activity-logs') ? route('admin.activity-logs') : route('logs.index') }}"
                           @click="sidebarOpen = false"
                           @if($isMobileLogsActive) aria-current="page" @endif
                           style="{{ $isMobileLogsActive ? 'background: linear-gradient(90deg, #1d4ed8 0%, #2563eb 100%); color: #ffffff;' : 'background-color: #ffffff; color: #334155;' }}"
                           class="flex items-center gap-3.5 min-h-[46px] px-4 py-2.5 rounded-xl font-bold text-sm transition-all touch-manipulation active:scale-[0.99] {{ $isMobileLogsActive ? 'shadow-md shadow-blue-600/20' : 'hover:bg-slate-100' }}">
                            <svg class="w-5 h-5 flex-shrink-0" style="color: inherit;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span style="color: inherit;">Log Aktivitas</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <!-- WIDGET BAWAH HP -->
        @if($isSuperAdmin)
            <div style="background: linear-gradient(145deg, #f8fafc 0%, #eff6ff 100%); border-color: #dbeafe; color: #0f172a;"
                 class="p-3.5 m-3 rounded-2xl border text-center shadow-xs flex-shrink-0 relative overflow-hidden">
                <div style="height: 3px; background: linear-gradient(90deg, #1d4ed8 0%, #38bdf8 100%); position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100/90 text-blue-700 text-[10px] font-extrabold uppercase tracking-wider mb-1.5">
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background-color: #0284c7;"></span>
                    <span>Akses Penuh</span>
                </div>
                <div style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;"
                     class="w-9 h-9 mx-auto mb-1.5 rounded-xl flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <p style="color: #0f172a;" class="text-xs font-black tracking-tight leading-snug">Super Administrator</p>
                <p style="color: #64748b;" class="text-[11px] font-medium mt-0.5 leading-snug">Kontrol penuh sistem inventaris &amp; monitoring keamanan TMB RRI Bukittinggi.</p>
            </div>
        @else
            <div style="background: linear-gradient(145deg, #eff6ff 0%, #f0f9ff 100%); border-color: #bfdbfe; color: #0f172a;"
                 class="p-3.5 m-3 rounded-2xl border text-center shadow-xs flex-shrink-0 relative overflow-hidden">
                <div style="height: 3px; background: linear-gradient(90deg, #2563eb 0%, #38bdf8 100%); position: absolute; top: 0; left: 0; right: 0;"></div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-sky-100/90 text-sky-800 text-[10px] font-extrabold uppercase tracking-wider mb-1.5">
                    <span style="width: 5px; height: 5px; border-radius: 9999px; background-color: #2563eb;"></span>
                    <span>Operasional</span>
                </div>
                <div style="background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 100%); color: #ffffff;"
                     class="w-9 h-9 mx-auto mb-1.5 rounded-xl flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p style="color: #0f172a;" class="text-xs font-black tracking-tight leading-snug">Admin Operasional</p>
                <p style="color: #475569;" class="text-[11px] font-medium mt-0.5 leading-snug">Kelola data inventaris harian TMB RRI Bukittinggi dengan optimal.</p>
            </div>
        @endif
    </nav>
</div>