<x-app-layout>
    @php
        $adminCollection = $users ?? collect();
        $totalAdmins = method_exists($adminCollection, 'total') ? $adminCollection->total() : $adminCollection->count();
        $currentUserId = auth()->id();

        $superAdminTotal = 0;
        $operationalAdminTotal = 0;
        foreach ($adminCollection as $acc) {
            $isSuper = in_array($acc->usertype ?? null, ['superadmin', 'super-admin'], true)
                || in_array($acc->role ?? null, ['superadmin', 'super-admin'], true);
            if ($isSuper) {
                $superAdminTotal++;
            } else {
                $operationalAdminTotal++;
            }
        }
    @endphp

    <style>
        .tmb-adm-wrap {
            width: 100%;
            max-width: 78rem;
            margin: 0 auto;
            padding: 14px max(12px, env(safe-area-inset-right)) calc(26px + env(safe-area-inset-bottom)) max(12px, env(safe-area-inset-left));
        }
        @media (min-width: 640px) {
            .tmb-adm-wrap {
                padding-top: 18px;
                padding-left: max(20px, env(safe-area-inset-left));
                padding-right: max(20px, env(safe-area-inset-right));
            }
        }

        .tmb-adm-top-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 18px;
            align-items: stretch;
        }
        @media (min-width: 768px) {
            .tmb-adm-top-grid {
                grid-template-columns: 24rem minmax(0, 1fr);
                gap: 18px;
            }
        }

        .tmb-adm-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 22px;
            box-shadow: 0 10px 24px -14px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .tmb-adm-top {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .tmb-adm-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            background-color: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #eff6ff;
            border-radius: 8px;
            padding: 3px 9px;
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            line-height: 1.2;
            margin-bottom: 5px;
        }

        .tmb-adm-count-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 12px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tmb-adm-btn-primary {
            width: 100%;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.875rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 8px 16px -8px rgba(37, 99, 235, 0.5);
            transition: transform 0.15s ease, filter 0.15s ease;
            -webkit-tap-highlight-color: transparent;
        }
        .tmb-adm-btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }
        .tmb-adm-btn-primary:active {
            transform: scale(0.99);
        }

        .tmb-adm-hint {
            display: none;
            align-items: flex-start;
            gap: 6px;
            margin-top: 10px;
            padding: 8px 11px;
            border-radius: 10px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: 0.73rem;
            font-weight: 600;
            line-height: 1.4;
            animation: tmbAdmHintFade 0.14s ease-out;
        }
        .tmb-adm-hint.is-visible {
            display: flex;
        }
        @keyframes tmbAdmHintFade {
            from {
                opacity: 0;
                transform: translateY(-3px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tmb-adm-stat-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 640px) {
            .tmb-adm-stat-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .tmb-adm-stat-tile {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 15px;
            padding: 13px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .tmb-adm-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-bottom: 6px;
        }

        /* 16px di mobile agar iOS Safari tidak melakukan auto-zoom saat mengetik */
        .tmb-adm-input {
            width: 100%;
            min-width: 0;
            min-height: 44px;
            padding: 10px 14px;
            background-color: #ffffff;
            color: #0f172a;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        @media (min-width: 768px) {
            .tmb-adm-input {
                font-size: 0.875rem;
            }
        }
        .tmb-adm-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        .tmb-adm-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 26px;
            height: 26px;
            padding: 0 7px;
            border-radius: 8px;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 0.75rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .tmb-adm-edit-btn {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 10px;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1.5px solid #93c5fd;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
        }
        .tmb-adm-edit-btn:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #1d4ed8;
            box-shadow: 0 6px 12px -6px rgba(37, 99, 235, 0.45);
        }

        .tmb-adm-del-btn {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 10px;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #f87171;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
        }
        .tmb-adm-del-btn:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #b91c1c;
            box-shadow: 0 6px 12px -6px rgba(220, 38, 38, 0.45);
        }

        .tmb-adm-locked-tag {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 10px;
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #cbd5e1;
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
        }

        /* Tampilan Kartu (< 768px) & Tabel (>= 768px) */
        .tmb-adm-mobile {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @media (min-width: 768px) {
            .tmb-adm-mobile {
                display: none;
            }
        }

        .tmb-adm-item-card {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 14px;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }
        .tmb-adm-item-card:hover {
            background-color: #eff6ff;
            border-color: #93c5fd;
        }

        .tmb-adm-table-shell {
            display: none;
        }
        @media (min-width: 768px) {
            .tmb-adm-table-shell {
                display: block;
                width: 100%;
                overflow-x: auto;
                border: 1.5px solid #cbd5e1;
                border-radius: 16px;
                background-color: #ffffff;
            }
        }

        .tmb-adm-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
            color: #0f172a;
        }

        .tmb-adm-table thead {
            background-color: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
        }

        .tmb-adm-table thead th {
            padding: 12px 16px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
            white-space: nowrap;
        }

        .tmb-adm-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.14s ease;
        }
        .tmb-adm-table tbody tr:last-child {
            border-bottom: none;
        }
        .tmb-adm-table tbody tr:hover {
            background-color: #eff6ff;
        }

        .tmb-adm-table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
        }

        /* Modal Overlay */
        .tmb-adm-overlay {
            display: none;
            position: fixed;
            inset: 0;
            min-height: 100dvh;
            z-index: 9999;
            background-color: rgba(15, 23, 42, 0.58);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            padding: max(14px, env(safe-area-inset-top)) max(14px, env(safe-area-inset-right)) max(14px, env(safe-area-inset-bottom)) max(14px, env(safe-area-inset-left));
            overflow-y: auto;
        }
        .tmb-adm-overlay.is-open {
            display: flex;
        }

        @keyframes tmbAdmModalPop {
            from {
                opacity: 0;
                transform: translateY(8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .tmb-adm-modal-card {
            animation: tmbAdmModalPop 0.16s ease-out;
        }

        /* Popup SweetAlert2 Modern & Kompak (Gaya Kategori) */
        .swal2-popup.tmb-swal-card {
            width: min(92vw, 420px) !important;
            border-radius: 22px !important;
            border: 1.5px solid #cbd5e1 !important;
            padding: 24px 22px !important;
            box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.28) !important;
        }
        .swal2-title.tmb-swal-title {
            font-size: 1.15rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            padding-top: 4px !important;
        }
        .swal2-html-container.tmb-swal-text {
            font-size: 0.85rem !important;
            font-weight: 500 !important;
            color: #475569 !important;
            margin-top: 6px !important;
        }
        .swal2-actions.tmb-swal-actions {
            gap: 10px !important;
            margin-top: 18px !important;
        }
        .tmb-swal-btn {
            border-radius: 12px !important;
            padding: 10px 18px !important;
            font-size: 0.825rem !important;
            font-weight: 700 !important;
        }
    </style>

    <!-- Header Halaman -->
    <x-slot name="header">
        <div class="flex items-start sm:items-center justify-between gap-2.5 py-0.5">
            <div class="flex items-start sm:items-center gap-2.5 min-w-0 flex-1">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2 sm:p-2.5 rounded-xl shadow-sm flex-shrink-0 mt-0.5 sm:mt-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-base sm:text-xl leading-tight">
                            Manajemen Akun Admin
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #334155; font-weight: 600;" class="flex items-start sm:items-center gap-1.5 text-[11px] sm:text-xs leading-snug mt-1">
                        <span style="background-color: #2563eb;" class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-1 sm:mt-0"></span>
                        <span>
                            Kelola dan perbarui akun super admin serta admin operasional TMB
                            <span style="color: #1d4ed8; font-weight: 700;">RRI Bukittinggi</span>.
                        </span>
                    </p>
                </div>
            </div>

            <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1;" class="hidden sm:block px-3.5 py-1.5 rounded-xl shadow-2xs text-right flex-shrink-0">
                <p style="color: #0f172a; font-weight: 800;" class="text-xs tracking-tight leading-none">RRI Bukittinggi</p>
                <p style="color: #2563eb; font-weight: 900;" class="text-[10px] tracking-wider uppercase mt-0.5 leading-none">DIVISI TMB</p>
            </div>
        </div>
    </x-slot>

    <!-- Kontainer Utama -->
    <div style="padding-left: env(safe-area-inset-left); padding-right: env(safe-area-inset-right);">
        <div class="tmb-adm-wrap">

            <!-- Baris Atas: Kartu Aksi Tambah Admin + Ringkasan Hak Akses -->
            <div class="tmb-adm-top-grid">

                <!-- Kartu Kiri: Akses Tambah Admin Baru -->
                <div class="tmb-adm-card tmb-adm-focus-group">
                    <div class="tmb-adm-top">
                        <div>
                            <div class="tmb-adm-tag">
                                <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                                <span>Registrasi Akses</span>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white leading-snug">
                                Tambah Admin Baru
                            </h3>
                        </div>
                    </div>

                    <div class="p-5">
                        <a href="{{ route('users.create') }}"
                           id="tmbAddAdminBtn"
                           class="tmb-adm-btn-primary touch-manipulation">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Tambah Admin Baru</span>
                        </a>

                        <div class="tmb-adm-hint">
                            <span>•</span>
                            <span>Buka halaman pembuatan akun untuk menambahkan <strong>Admin Operasional</strong> atau <strong>Super Admin</strong> cadangan ke sistem inventaris.</span>
                        </div>
                    </div>
                </div>

                <!-- Kartu Kanan: Ringkasan Akun & Notifikasi -->
                <div class="tmb-adm-card">
                    <div class="tmb-adm-top">
                        <div>
                            <div class="tmb-adm-tag">
                                <span style="background-color: #34d399;" class="w-1.5 h-1.5 rounded-full"></span>
                                <span>Ringkasan Peran</span>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white leading-snug">
                                Status &amp; Hak Akses Akun
                            </h3>
                        </div>

                        <span class="tmb-adm-count-pill">
                            <span style="background-color: #60a5fa;" class="w-2 h-2 rounded-full"></span>
                            <span>{{ $totalAdmins }} Akun</span>
                        </span>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="tmb-adm-stat-row">
                            <div class="tmb-adm-stat-tile">
                                <div>
                                    <div style="color: #3730a3;" class="text-[11px] font-extrabold uppercase tracking-wider">Super Admin</div>
                                    <div style="color: #0f172a;" class="text-xl font-black mt-0.5">{{ $superAdminTotal }}</div>
                                </div>
                                <span style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;" class="text-[11px] font-extrabold px-2.5 py-1 rounded-lg">
                                    Terproteksi
                                </span>
                            </div>

                            <div class="tmb-adm-stat-tile">
                                <div>
                                    <div style="color: #1d4ed8;" class="text-[11px] font-extrabold uppercase tracking-wider">Admin Operasional</div>
                                    <div style="color: #0f172a;" class="text-xl font-black mt-0.5">{{ $operationalAdminTotal }}</div>
                                </div>
                                <span style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;" class="text-[11px] font-extrabold px-2.5 py-1 rounded-lg">
                                    Aktif
                                </span>
                            </div>
                        </div>

                        @if(session('success'))
                            <div id="flashSuccessBanner"
                                 data-success-message="{{ session('success') }}"
                                 style="background-color: #ecfdf5; color: #065f46; border: 1.5px solid #6ee7b7;"
                                 class="p-3.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div style="background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;"
                                 class="p-3.5 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2.5">
                                <span style="background-color: #ef4444;" class="w-2 h-2 rounded-full flex-shrink-0"></span>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div style="background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;"
                                 class="p-3.5 rounded-xl text-xs font-bold">
                                <p class="font-extrabold mb-1">Periksa kembali data yang dimasukkan:</p>
                                <ul class="list-disc list-inside space-y-0.5 font-semibold">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Kartu Utama: Daftar Akun Terdaftar -->
            <div class="tmb-adm-card">
                <div class="tmb-adm-top">
                    <div>
                        <div class="tmb-adm-tag">
                            <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Daftar Pengguna</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold text-white leading-snug">
                            Akun Admin Terdaftar
                        </h3>
                    </div>

                    <span class="tmb-adm-count-pill">
                        <span style="background-color: #34d399;" class="w-2 h-2 rounded-full"></span>
                        <span>{{ $totalAdmins }} Akun</span>
                    </span>
                </div>

                <div class="p-4 sm:p-5">

                    <!-- Tampilan Kartu (< 768px) -->
                    <div class="tmb-adm-mobile">
                        @forelse ($adminCollection as $index => $user)
                            @php
                                $rowNo = method_exists($adminCollection, 'firstItem') ? $adminCollection->firstItem() + $index : $index + 1;
                                $isRowSuperAdmin = in_array($user->usertype ?? null, ['superadmin', 'super-admin'], true)
                                    || in_array($user->role ?? null, ['superadmin', 'super-admin'], true);
                                $isSelf = (string) $currentUserId === (string) $user->id;
                                $canDeleteRow = !$isSelf && (!$isRowSuperAdmin || $superAdminTotal > 1);
                                $roleLabel = $isRowSuperAdmin ? 'Super Administrator' : 'Admin Operasional';
                                $editUrl = Route::has('users.update') ? route('users.update', $user->id) : route('users.destroy', $user->id);
                            @endphp
                            <div class="tmb-adm-item-card">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                        <span class="tmb-adm-num mt-0.5">{{ $rowNo }}</span>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-extrabold text-sm text-slate-900 truncate">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs font-semibold text-slate-600 break-all mt-0.5">
                                                {{ $user->email }}
                                            </p>
                                            <div class="flex items-center gap-1.5 flex-wrap mt-2">
                                                <span style="{{ $isRowSuperAdmin ? 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;' : 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;' }}"
                                                      class="inline-block text-[11px] font-bold px-2.5 py-0.5 rounded-md">
                                                    {{ $roleLabel }}
                                                </span>
                                                <span style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;"
                                                      class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                                    <span style="background-color: #16a34a;" class="w-1.5 h-1.5 rounded-full"></span>
                                                    <span>Aktif</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="border-top: 1px solid #e2e8f0; margin-top: 12px; padding-top: 10px;"
                                     class="{{ $canDeleteRow ? 'grid grid-cols-2 gap-2' : 'flex items-center justify-end gap-2' }}">
                                    <button type="button"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ e($user->name) }}"
                                            data-user-email="{{ e($user->email) }}"
                                            data-user-role="{{ e($roleLabel) }}"
                                            data-update-url="{{ $editUrl }}"
                                            class="js-open-edit-admin tmb-adm-edit-btn {{ $canDeleteRow ? 'w-full' : 'px-4' }} touch-manipulation">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </button>

                                    @if($canDeleteRow)
                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="POST"
                                              class="w-full js-delete-admin-form"
                                              data-admin-name="{{ e($user->name) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tmb-adm-del-btn w-full touch-manipulation">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @elseif($isSelf)
                                        <span class="tmb-adm-locked-tag">Akun Aktif</span>
                                    @else
                                        <span class="tmb-adm-locked-tag">Super Admin Terakhir</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-9 text-center">
                                <p class="text-sm font-extrabold text-slate-700">Belum ada akun admin terdaftar.</p>
                                <p class="text-xs font-medium text-slate-500 mt-1">
                                    Gunakan tombol <strong>Tambah Admin Baru</strong> di atas untuk menambahkan akun baru.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Tampilan Tabel (>= 768px) -->
                    <div class="tmb-adm-table-shell">
                        <table class="tmb-adm-table">
                            <thead>
                                <tr>
                                    <th class="w-14 text-center">No</th>
                                    <th class="text-left">Nama Admin</th>
                                    <th class="text-left">Email Login</th>
                                    <th class="w-52 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($adminCollection as $index => $user)
                                    @php
                                        $rowNo = method_exists($adminCollection, 'firstItem') ? $adminCollection->firstItem() + $index : $index + 1;
                                        $isRowSuperAdmin = in_array($user->usertype ?? null, ['superadmin', 'super-admin'], true)
                                            || in_array($user->role ?? null, ['superadmin', 'super-admin'], true);
                                        $isSelf = (string) $currentUserId === (string) $user->id;
                                        $canDeleteRow = !$isSelf && (!$isRowSuperAdmin || $superAdminTotal > 1);
                                        $roleLabel = $isRowSuperAdmin ? 'Super Administrator' : 'Admin Operasional';
                                        $editUrl = Route::has('users.update') ? route('users.update', $user->id) : route('users.destroy', $user->id);
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <span class="tmb-adm-num">{{ $rowNo }}</span>
                                        </td>
                                        <td>
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="font-extrabold text-slate-900 leading-snug">
                                                    {{ $user->name }}
                                                </span>
                                                <span style="{{ $isRowSuperAdmin ? 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;' : 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;' }}"
                                                      class="inline-block text-[11px] font-bold px-2.5 py-0.5 rounded-md whitespace-nowrap">
                                                    {{ $roleLabel }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="font-semibold text-slate-800">
                                                    {{ $user->email }}
                                                </span>
                                                <span style="background-color: #dcfce7; color: #15803d; border: 1px solid #86efac;"
                                                      class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                                    <span style="background-color: #16a34a;" class="w-1.5 h-1.5 rounded-full"></span>
                                                    <span>Aktif</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="inline-flex items-center justify-center gap-2">
                                                <button type="button"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ e($user->name) }}"
                                                        data-user-email="{{ e($user->email) }}"
                                                        data-user-role="{{ e($roleLabel) }}"
                                                        data-update-url="{{ $editUrl }}"
                                                        class="js-open-edit-admin tmb-adm-edit-btn touch-manipulation">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>

                                                @if($canDeleteRow)
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                          method="POST"
                                                          class="inline js-delete-admin-form"
                                                          data-admin-name="{{ e($user->name) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="tmb-adm-del-btn touch-manipulation">
                                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                @elseif($isSelf)
                                                    <span class="tmb-adm-locked-tag">Akun Aktif</span>
                                                @else
                                                    <span class="tmb-adm-locked-tag">Super Admin Terakhir</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-9 text-center">
                                            <p class="text-sm font-extrabold text-slate-700">Belum ada akun admin terdaftar.</p>
                                            <p class="text-xs font-medium text-slate-500 mt-1">
                                                Gunakan tombol <strong>Tambah Admin Baru</strong> di atas untuk menambahkan akun baru.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($adminCollection, 'hasPages') && $adminCollection->hasPages())
                        <div class="mt-4">
                            {{ $adminCollection->withQueryString()->links() }}
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <!-- POP-UP EDIT AKUN ADMIN -->
    <div id="editAdminModal"
         class="tmb-adm-overlay"
         role="dialog"
         aria-modal="true"
         aria-labelledby="editAdminModalTitle">
        <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; width: 100%; max-width: 460px; max-height: 90dvh; border-radius: 22px; overflow-y: auto;"
             class="tmb-adm-modal-card shadow-2xl my-auto"
             onclick="event.stopPropagation();">

            <div class="tmb-adm-top">
                <div class="min-w-0">
                    <div class="tmb-adm-tag">
                        <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                        <span id="editAdminRoleBadge">Admin</span>
                    </div>
                    <h3 id="editAdminModalTitle" class="text-sm sm:text-base font-extrabold text-white leading-snug">
                        Edit Akun Admin
                    </h3>
                </div>

                <button type="button"
                        id="closeEditAdminModal"
                        aria-label="Tutup pop-up edit"
                        style="background-color: rgba(255, 255, 255, 0.16); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff; width: 36px; height: 36px; border-radius: 10px;"
                        class="inline-flex items-center justify-center cursor-pointer flex-shrink-0 touch-manipulation">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editAdminForm" method="POST" action="" class="p-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="editAdminName" class="tmb-adm-label">
                        Nama Admin <span class="text-rose-600">*</span>
                    </label>
                    <input type="text"
                           id="editAdminName"
                           name="name"
                           required
                           class="tmb-adm-input">
                </div>

                <div>
                    <label for="editAdminEmail" class="tmb-adm-label">
                        Email Login <span class="text-rose-600">*</span>
                    </label>
                    <input type="email"
                           id="editAdminEmail"
                           name="email"
                           required
                           class="tmb-adm-input">
                </div>

                <div>
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <label for="editAdminPassword" class="tmb-adm-label" style="margin-bottom: 0;">
                            Password Baru
                        </label>
                        <span class="text-[11px] font-semibold text-slate-500">
                            Kosongkan jika tetap
                        </span>
                    </div>
                    <div style="position: relative; width: 100%;">
                        <input type="password"
                               id="editAdminPassword"
                               name="password"
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter (opsional)"
                               style="padding-right: 44px;"
                               class="tmb-adm-input">
                        <button type="button"
                                id="toggleEditPasswordBtn"
                                aria-label="Tampilkan atau sembunyikan password"
                                style="color: #64748b; position: absolute; top: 0; right: 0; height: 100%; min-width: 42px; padding: 0 12px;"
                                class="inline-flex items-center justify-center hover:text-blue-600 transition touch-manipulation">
                            <svg id="eyeIconOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eyeIconClosed" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div style="border-top: 1.5px solid #e2e8f0; padding-top: 14px;" class="flex items-center justify-end gap-2.5">
                    <button type="button"
                            id="cancelEditAdminModal"
                            style="background-color: #f1f5f9; color: #334155; border: 1.5px solid #cbd5e1; border-radius: 12px; padding: 9px 16px;"
                            class="min-h-[40px] text-xs font-extrabold hover:bg-slate-200 transition touch-manipulation">
                        Batal
                    </button>
                    <button type="submit"
                            id="submitEditAdminBtn"
                            style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e40af; border-radius: 12px; padding: 9px 18px;"
                            class="min-h-[40px] text-xs font-extrabold hover:brightness-105 shadow-sm transition inline-flex items-center gap-1.5 touch-manipulation">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- POP-UP NOTIFIKASI PERUBAHAN BERHASIL (FALLBACK BILA SWEETALERT2 TIDAK ADA) -->
    <div id="updatedToastModal"
         class="tmb-adm-overlay"
         role="dialog"
         aria-modal="true"
         aria-labelledby="updatedToastTitle">
        <div style="background-color: #ffffff; border: 1.5px solid #cbd5e1; width: 100%; max-width: 380px; border-radius: 22px; padding: 24px;"
             class="tmb-adm-modal-card shadow-2xl text-center my-auto"
             onclick="event.stopPropagation();">
            <div style="background-color: #dcfce7; color: #16a34a;"
                 class="w-14 h-14 mx-auto mb-3 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h4 id="updatedToastTitle" style="color: #0f172a;" class="text-base font-black">
                Berhasil!
            </h4>
            <p id="updatedToastMessage" style="color: #475569;" class="text-xs font-medium mt-1.5 leading-relaxed">
                Data akun telah diperbarui di sistem inventaris TMB RRI Bukittinggi.
            </p>
            <button type="button"
                    id="closeUpdatedToastBtn"
                    style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff;"
                    class="mt-5 w-full min-h-[42px] rounded-xl text-xs font-extrabold hover:brightness-105 transition touch-manipulation">
                Mantap, Mengerti
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hint interaktif saat bagian dipilih (gaya Kelola Kategori)
            document.querySelectorAll('.tmb-adm-focus-group').forEach(group => {
                const hint = group.querySelector('.tmb-adm-hint');
                if (!hint) return;
                group.addEventListener('focusin', () => hint.classList.add('is-visible'));
                group.addEventListener('click', () => hint.classList.add('is-visible'));
            });
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.tmb-adm-focus-group')) {
                    document.querySelectorAll('.tmb-adm-hint').forEach(h => h.classList.remove('is-visible'));
                }
            });

            // 1. Pop-up Konfirmasi Hapus Akun
            document.querySelectorAll('.js-delete-admin-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const adminName = form.getAttribute('data-admin-name') || 'akun admin ini';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Akun Admin?',
                            text: `Akun "${adminName}" akan dihapus permanen dan tidak bisa lagi masuk ke sistem.`,
                            icon: 'warning',
                            showCancelButton: true,
                            reverseButtons: true,
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#475569',
                            customClass: {
                                popup: 'tmb-swal-card',
                                title: 'tmb-swal-title',
                                htmlContainer: 'tmb-swal-text',
                                actions: 'tmb-swal-actions',
                                confirmButton: 'tmb-swal-btn',
                                cancelButton: 'tmb-swal-btn'
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else if (window.confirm(`Yakin ingin menghapus akun "${adminName}"?`)) {
                        form.submit();
                    }
                });
            });

            // 2. Pop-up Edit Akun Admin & Konfirmasi Saat Diubah
            const modal = document.getElementById('editAdminModal');
            const editForm = document.getElementById('editAdminForm');
            const roleBadge = document.getElementById('editAdminRoleBadge');
            const nameInput = document.getElementById('editAdminName');
            const emailInput = document.getElementById('editAdminEmail');
            const passwordInput = document.getElementById('editAdminPassword');
            const closeBtn = document.getElementById('closeEditAdminModal');
            const cancelBtn = document.getElementById('cancelEditAdminModal');
            const togglePassBtn = document.getElementById('toggleEditPasswordBtn');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');

            let initialValues = { name: '', email: '' };

            function resetPasswordVisibility() {
                if (!passwordInput) return;
                passwordInput.type = 'password';
                if (eyeOpen) eyeOpen.style.display = 'block';
                if (eyeClosed) eyeClosed.style.display = 'none';
            }

            function openEditModal(btn) {
                if (!modal || !editForm) return;
                const currentName = btn.getAttribute('data-user-name') || '';
                const currentEmail = btn.getAttribute('data-user-email') || '';
                const currentRole = btn.getAttribute('data-user-role') || 'Admin';

                initialValues = { name: currentName, email: currentEmail };
                editForm.action = btn.getAttribute('data-update-url') || '';
                if (roleBadge) roleBadge.textContent = currentRole;
                nameInput.value = currentName;
                emailInput.value = currentEmail;
                passwordInput.value = '';
                resetPasswordVisibility();
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                if (!modal) return;
                modal.classList.remove('is-open');
                if (!updatedToastModal || !updatedToastModal.classList.contains('is-open')) {
                    document.body.style.overflow = '';
                }
            }

            document.querySelectorAll('.js-open-edit-admin').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openEditModal(btn);
                });
            });

            if (togglePassBtn && passwordInput) {
                togglePassBtn.addEventListener('click', function () {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    if (eyeOpen) eyeOpen.style.display = isHidden ? 'none' : 'block';
                    if (eyeClosed) eyeClosed.style.display = isHidden ? 'block' : 'none';
                });
            }

            if (editForm) {
                editForm.addEventListener('submit', function (e) {
                    const nameChanged = nameInput.value.trim() !== initialValues.name.trim();
                    const emailChanged = emailInput.value.trim() !== initialValues.email.trim();
                    const passwordChanged = passwordInput.value.length > 0;

                    if (!nameChanged && !emailChanged && !passwordChanged) {
                        return;
                    }

                    e.preventDefault();
                    const changes = [];
                    if (nameChanged) changes.push('Nama Admin');
                    if (emailChanged) changes.push('Email Login');
                    if (passwordChanged) changes.push('Password Baru');

                    const summaryText = `Perubahan pada ${changes.join(', ')} untuk "${nameInput.value.trim()}" akan disimpan.`;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Simpan Perubahan Akun?',
                            text: summaryText,
                            icon: 'info',
                            showCancelButton: true,
                            reverseButtons: true,
                            confirmButtonText: 'Ya, Simpan!',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#2563eb',
                            cancelButtonColor: '#475569',
                            customClass: {
                                popup: 'tmb-swal-card',
                                title: 'tmb-swal-title',
                                htmlContainer: 'tmb-swal-text',
                                actions: 'tmb-swal-actions',
                                confirmButton: 'tmb-swal-btn',
                                cancelButton: 'tmb-swal-btn'
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                editForm.submit();
                            }
                        });
                    } else if (window.confirm(summaryText)) {
                        editForm.submit();
                    }
                });
            }

            // 3. Notifikasi Sukses
            const flashBanner = document.getElementById('flashSuccessBanner');
            const updatedToastModal = document.getElementById('updatedToastModal');
            const updatedToastMessage = document.getElementById('updatedToastMessage');
            const closeUpdatedToastBtn = document.getElementById('closeUpdatedToastBtn');

            if (flashBanner) {
                const msg = flashBanner.getAttribute('data-success-message') || 'Data akun berhasil diperbarui.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: msg,
                        confirmButtonText: 'Mantap, Mengerti',
                        confirmButtonColor: '#2563eb',
                        customClass: {
                            popup: 'tmb-swal-card',
                            title: 'tmb-swal-title',
                            htmlContainer: 'tmb-swal-text',
                            actions: 'tmb-swal-actions',
                            confirmButton: 'tmb-swal-btn'
                        }
                    });
                } else if (updatedToastModal && updatedToastMessage) {
                    updatedToastMessage.textContent = msg;
                    updatedToastModal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeUpdatedToast() {
                if (!updatedToastModal) return;
                updatedToastModal.classList.remove('is-open');
                if (!modal || !modal.classList.contains('is-open')) {
                    document.body.style.overflow = '';
                }
            }

            if (closeUpdatedToastBtn && updatedToastModal) {
                closeUpdatedToastBtn.addEventListener('click', closeUpdatedToast);
                updatedToastModal.addEventListener('click', closeUpdatedToast);
            }

            if (closeBtn) closeBtn.addEventListener('click', closeEditModal);
            if (cancelBtn) cancelBtn.addEventListener('click', closeEditModal);
            if (modal) modal.addEventListener('click', closeEditModal);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                    closeUpdatedToast();
                }
            });
        });
    </script>
</x-app-layout>