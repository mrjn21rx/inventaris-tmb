<x-app-layout>
    @php
        $totalVal = (int) ($totalItems ?? 0);
        $newVal = (int) ($newItems ?? 0);
        $goodVal = (int) ($goodItems ?? 0);
        $brokenVal = (int) ($brokenItems ?? 0);
        $brokenHeavyVal = (int) ($brokenHeavyItems ?? 0);
        $brokenCombinedVal = $brokenVal + $brokenHeavyVal;
        $denom = $totalVal > 0 ? $totalVal : 1;

        $pctNew = round(($newVal / $denom) * 100);
        $pctGood = round(($goodVal / $denom) * 100);
        $pctBroken = round(($brokenVal / $denom) * 100);
        $pctHeavy = round(($brokenHeavyVal / $denom) * 100);

        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];
        $nowObj = now();
        $namaHari = $hariIndo[$nowObj->format('l')] ?? $nowObj->format('l');
        $namaBulan = $bulanIndo[$nowObj->format('F')] ?? $nowObj->format('F');
        $tanggalIndonesia = $namaHari . ', ' . $nowObj->format('d') . ' ' . $namaBulan . ' ' . $nowObj->format('Y');
    @endphp

    <style>
        .tmb-dash-wrap {
            width: 100%;
            max-width: 86rem;
            margin: 0 auto;
            padding: 18px 0 20px 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        @media (min-width: 1024px) {
            .tmb-dash-wrap {
                padding: 18px 0 16px 0;
                gap: 20px;
            }
        }

        /* 1. Banner Sambutan */
        .tmb-banner {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e3a8a;
            border-radius: 18px;
            padding: 18px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 10px 22px -12px rgba(15, 23, 42, 0.35);
        }
        @media (min-width: 768px) {
            .tmb-banner {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 18px 26px;
            }
        }

        /* Kotak Border "Hari Ini" Lebih Lega */
        .tmb-date-box {
            background-color: rgba(15, 23, 42, 0.48);
            border: 1.5px solid rgba(255, 255, 255, 0.24);
            border-radius: 14px;
            padding: 12px 18px;
            text-align: left;
            flex-shrink: 0;
        }
        @media (min-width: 768px) {
            .tmb-date-box {
                text-align: right;
                padding: 12px 20px;
            }
        }

        /* 2. 4 Kartu 4 Warna */
        .tmb-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            width: 100%;
            margin-top: 2px;
        }
        @media (min-width: 768px) and (max-width: 1023px) {
            .tmb-cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }
        }
        @media (min-width: 1024px) {
            .tmb-cards-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 17px;
                margin-top: 2px;
            }
        }

        .tmb-kpi-card {
            border-radius: 18px;
            padding: 20px 22px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            text-decoration: none;
            box-shadow: 0 8px 20px -12px rgba(15, 23, 42, 0.26);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        @media (min-width: 1024px) {
            .tmb-kpi-card {
                padding: 21px 22px;
            }
        }
        .tmb-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 26px -12px rgba(15, 23, 42, 0.34);
        }
        .tmb-kpi-num {
            font-size: 1.9rem;
            line-height: 1;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.025em;
        }
        @media (min-width: 1024px) {
            .tmb-kpi-num {
                font-size: 2.1rem;
            }
        }
        .tmb-kpi-unit {
            font-size: 0.875rem;
            font-weight: 700;
            opacity: 0.92;
        }

        /* 3. Panel Statistik Kondisi Barang */
        .tmb-stats-panel {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 20px;
            padding: 19px 22px;
            margin-top: 2px;
            box-shadow: 0 6px 16px -10px rgba(15, 23, 42, 0.08);
        }
        @media (min-width: 1024px) {
            .tmb-stats-panel {
                padding: 20px 24px;
            }
        }

        .tmb-stats-body {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
            align-items: center;
        }
        @media (min-width: 768px) {
            .tmb-stats-body {
                grid-template-columns: 170px minmax(0, 1fr);
                gap: 26px;
            }
        }
        @media (min-width: 1024px) {
            .tmb-stats-body {
                grid-template-columns: 176px minmax(0, 1fr);
                gap: 30px;
            }
        }

        .tmb-donut-box {
            width: 158px;
            height: 158px;
            margin: 0 auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (min-width: 1024px) {
            .tmb-donut-box {
                width: 166px;
                height: 166px;
            }
        }
        .tmb-donut-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            text-align: center;
        }

        .tmb-condition-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 13px;
            width: 100%;
        }
        @media (min-width: 640px) {
            .tmb-condition-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }
        }

        .tmb-cond-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 9px;
            padding: 15px 18px;
            border-radius: 14px;
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .tmb-cond-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 16px -12px rgba(15, 23, 42, 0.16);
        }
        .tmb-cond-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .tmb-cond-count {
            font-size: 0.9375rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            white-space: nowrap;
        }
        .tmb-cond-count span {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }
        .tmb-cond-pct {
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 9999px;
            white-space: nowrap;
        }
        .tmb-progress-track {
            width: 100%;
            height: 5px;
            border-radius: 9999px;
            background-color: rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }
        .tmb-progress-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.35s ease;
        }
    </style>

    <!-- Header Dashboard -->
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 py-0.5">
            <div class="flex items-center gap-3 min-w-0">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2.5 rounded-xl shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 min-w-0 flex-wrap">
                    <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;"
                        class="text-lg sm:text-xl leading-tight">
                        Dashboard Utama
                    </h2>
                    <span style="background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;"
                        class="hidden md:inline-flex items-center text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full flex-shrink-0">
                        TMB • RRI
                    </span>
                </div>

                <p style="color: #334155; font-weight: 600; letter-spacing: 0.005em;"
                class="flex items-start gap-1.5 text-[11px] sm:text-xs leading-snug mt-0.5">
                    <span style="background-color: #2563eb;"
                        class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-1"></span>
                    <span class="min-w-0">
                        Sistem Informasi Manajemen Inventaris
                        <span style="color: #1d4ed8; font-weight: 700;" class="whitespace-nowrap">
                            TMB RRI Bukittinggi
                        </span>
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
        <div class="tmb-dash-wrap">

            <!-- Banner Sambutan -->
            <div class="tmb-banner">
                <div class="min-w-0">
                    <span style="background-color: rgba(255, 255, 255, 0.16); color: #ffffff;" class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1.5">
                        <span style="background-color: #93c5fd;" class="w-1.5 h-1.5 rounded-full"></span>
                        Sistem Inventaris
                    </span>
                    <h3 style="color: #ffffff;" class="text-base sm:text-lg font-black tracking-tight leading-snug">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h3>
                    <p style="color: #dbeafe;" class="text-xs font-medium mt-0.5 leading-snug">
                        Berikut adalah ringkasan inventaris barang TMB RRI Bukittinggi Anda hari ini.
                    </p>
                </div>

                <div class="tmb-date-box self-start md:self-auto">
                    <p style="color: #93c5fd;" class="text-[10px] font-extrabold uppercase tracking-wider leading-none">Hari Ini</p>
                    <p style="color: #ffffff;" class="text-xs sm:text-sm font-black mt-1.5 whitespace-nowrap leading-none">{{ $tanggalIndonesia }}</p>
                </div>
            </div>

            <!-- 4 Kartu Ringkasan Utama -->
            <div class="tmb-cards-grid">

                <!-- 1. Total Barang -->
                <a href="{{ route('items.index') }}"
                   style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e40af;"
                   class="tmb-kpi-card active:scale-[0.98] touch-manipulation">
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="relative z-10 min-w-0">
                        <h3 style="color: #dbeafe;" class="text-[11px] font-extrabold uppercase tracking-wider mb-1">Total Barang</h3>
                        <div class="flex items-baseline gap-1.5">
                            <span class="tmb-kpi-num">{{ $totalVal }}</span>
                            <span style="color: #bfdbfe;" class="tmb-kpi-unit">Unit</span>
                        </div>
                    </div>
                    <div class="relative z-10 w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </a>

                <!-- 2. Kondisi Baru -->
                <a href="{{ route('items.index', ['condition' => 'Baru']) }}"
                   style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: #ffffff; border: 1.5px solid #065f46;"
                   class="tmb-kpi-card active:scale-[0.98] touch-manipulation">
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="relative z-10 min-w-0">
                        <h3 style="color: #d1fae5;" class="text-[11px] font-extrabold uppercase tracking-wider mb-1">Kondisi Baru</h3>
                        <div class="flex items-baseline gap-1.5">
                            <span class="tmb-kpi-num">{{ $newVal }}</span>
                            <span style="color: #a7f3d0;" class="tmb-kpi-unit">Unit</span>
                        </div>
                    </div>
                    <div class="relative z-10 w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>

                <!-- 3. Kondisi Baik -->
                <a href="{{ route('items.index', ['condition' => 'Baik']) }}"
                   style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: 1.5px solid #b45309;"
                   class="tmb-kpi-card active:scale-[0.98] touch-manipulation">
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="relative z-10 min-w-0">
                        <h3 style="color: #fef3c7;" class="text-[11px] font-extrabold uppercase tracking-wider mb-1">Kondisi Baik</h3>
                        <div class="flex items-baseline gap-1.5">
                            <span class="tmb-kpi-num">{{ $goodVal }}</span>
                            <span style="color: #fde68a;" class="tmb-kpi-unit">Unit</span>
                        </div>
                    </div>
                    <div class="relative z-10 w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </a>

                <!-- 4. Rusak / Rusak Berat -->
                <a href="{{ route('items.index', ['condition' => 'rusak-berats']) }}"
                   style="background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%); color: #ffffff; border: 1.5px solid #9f1239;"
                   class="tmb-kpi-card active:scale-[0.98] touch-manipulation">
                    <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="relative z-10 min-w-0">
                        <h3 style="color: #ffe4e6;" class="text-[11px] font-extrabold uppercase tracking-wider mb-1">Rusak / Berat</h3>
                        <div class="flex items-baseline gap-1.5">
                            <span class="tmb-kpi-num">{{ $brokenCombinedVal }}</span>
                            <span style="color: #fecdd3;" class="tmb-kpi-unit">Unit</span>
                        </div>
                    </div>
                    <div class="relative z-10 w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-white/20 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </a>

            </div>

            <!-- Panel Statistik Kondisi Barang -->
            <div class="tmb-stats-panel">

                <!-- Judul & Tombol Lihat Detail -->
                <div style="border-color: #e2e8f0;" class="flex items-center justify-between gap-3 pb-3.5 mb-4 border-b">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div style="background-color: #2563eb;" class="w-1.5 h-4 rounded-full flex-shrink-0"></div>
                        <h3 style="color: #0f172a;" class="text-sm font-extrabold tracking-tight truncate">
                            Statistik Kondisi Barang
                        </h3>
                    </div>

                    <a href="{{ route('items.index') }}"
                       style="background-color: #2563eb; color: #ffffff; border: 1.5px solid #1d4ed8;"
                       class="inline-flex items-center gap-1.5 hover:bg-blue-700 px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all shadow-2xs touch-manipulation flex-shrink-0 whitespace-nowrap">
                        <span>Lihat Detail</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Diagram Donat + 4 Kartu Rincian Kondisi -->
                <div class="tmb-stats-body">

                    <!-- Diagram Donat dengan Info Tengah -->
                    <div class="tmb-donut-box">
                        <canvas id="inventoryDoughnutChart"></canvas>
                        <div class="tmb-donut-center">
                            <span style="font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b;">Total</span>
                            <span style="font-size: 1.3rem; font-weight: 900; color: #0f172a; line-height: 1.1; margin-top: 1px;">{{ $totalVal }}</span>
                            <span style="font-size: 10px; font-weight: 700; color: #475569;">Unit</span>
                        </div>
                    </div>

                    <!-- 4 Kartu Rincian Kondisi -->
                    <div class="tmb-condition-grid">

                        <!-- 1. Baru -->
                        <a href="{{ route('items.index', ['condition' => 'Baru']) }}"
                           style="background: linear-gradient(120deg, rgba(16, 185, 129, 0.09) 0%, #ffffff 72%); border: 1.5px solid #6ee7b7;"
                           class="tmb-cond-card active:scale-[0.99] touch-manipulation">
                            <div class="tmb-cond-top">
                                <div style="display: flex; align-items: center; gap: 8px; min-width: 0;">
                                    <span style="width: 9px; height: 9px; border-radius: 9999px; background-color: #10b981; flex-shrink: 0;"></span>
                                    <span style="font-size: 0.875rem; font-weight: 700; color: #065f46; white-space: nowrap;">Baru</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 7px; flex-shrink: 0;">
                                    <span class="tmb-cond-count">{{ $newVal }} <span>Unit</span></span>
                                    <span class="tmb-cond-pct" style="color: #047857; background-color: #d1fae5; border: 1px solid #a7f3d0;">{{ $pctNew }}%</span>
                                </div>
                            </div>
                            <div class="tmb-progress-track">
                                <div class="tmb-progress-fill" style="width: {{ min(100, max(0, $pctNew)) }}%; background-color: #10b981;"></div>
                            </div>
                        </a>

                        <!-- 2. Baik -->
                        <a href="{{ route('items.index', ['condition' => 'Baik']) }}"
                           style="background: linear-gradient(120deg, rgba(245, 158, 11, 0.09) 0%, #ffffff 72%); border: 1.5px solid #fcd34d;"
                           class="tmb-cond-card active:scale-[0.99] touch-manipulation">
                            <div class="tmb-cond-top">
                                <div style="display: flex; align-items: center; gap: 8px; min-width: 0;">
                                    <span style="width: 9px; height: 9px; border-radius: 9999px; background-color: #f59e0b; flex-shrink: 0;"></span>
                                    <span style="font-size: 0.875rem; font-weight: 700; color: #92400e; white-space: nowrap;">Baik</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 7px; flex-shrink: 0;">
                                    <span class="tmb-cond-count">{{ $goodVal }} <span>Unit</span></span>
                                    <span class="tmb-cond-pct" style="color: #b45309; background-color: #fef3c7; border: 1px solid #fde68a;">{{ $pctGood }}%</span>
                                </div>
                            </div>
                            <div class="tmb-progress-track">
                                <div class="tmb-progress-fill" style="width: {{ min(100, max(0, $pctGood)) }}%; background-color: #f59e0b;"></div>
                            </div>
                        </a>

                        <!-- 3. Rusak -->
                        <a href="{{ route('items.index', ['condition' => 'Rusak']) }}"
                           style="background: linear-gradient(120deg, rgba(244, 63, 94, 0.09) 0%, #ffffff 72%); border: 1.5px solid #fda4af;"
                           class="tmb-cond-card active:scale-[0.99] touch-manipulation">
                            <div class="tmb-cond-top">
                                <div style="display: flex; align-items: center; gap: 8px; min-width: 0;">
                                    <span style="width: 9px; height: 9px; border-radius: 9999px; background-color: #f43f5e; flex-shrink: 0;"></span>
                                    <span style="font-size: 0.875rem; font-weight: 700; color: #9f1239; white-space: nowrap;">Rusak</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 7px; flex-shrink: 0;">
                                    <span class="tmb-cond-count">{{ $brokenVal }} <span>Unit</span></span>
                                    <span class="tmb-cond-pct" style="color: #be123c; background-color: #ffe4e6; border: 1px solid #fecdd3;">{{ $pctBroken }}%</span>
                                </div>
                            </div>
                            <div class="tmb-progress-track">
                                <div class="tmb-progress-fill" style="width: {{ min(100, max(0, $pctBroken)) }}%; background-color: #f43f5e;"></div>
                            </div>
                        </a>

                        <!-- 4. Rusak Berat -->
                        <a href="{{ route('items.index', ['condition' => 'Rusak Berat']) }}"
                           style="background: linear-gradient(120deg, rgba(185, 28, 28, 0.09) 0%, #ffffff 72%); border: 1.5px solid #f87171;"
                           class="tmb-cond-card active:scale-[0.99] touch-manipulation">
                            <div class="tmb-cond-top">
                                <div style="display: flex; align-items: center; gap: 8px; min-width: 0;">
                                    <span style="width: 9px; height: 9px; border-radius: 9999px; background-color: #b91c1c; flex-shrink: 0;"></span>
                                    <span style="font-size: 0.875rem; font-weight: 700; color: #7f1d1d; white-space: nowrap;">Rusak Berat</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 7px; flex-shrink: 0;">
                                    <span class="tmb-cond-count">{{ $brokenHeavyVal }} <span>Unit</span></span>
                                    <span class="tmb-cond-pct" style="color: #991b1b; background-color: #fee2e2; border: 1px solid #fca5a5;">{{ $pctHeavy }}%</span>
                                </div>
                            </div>
                            <div class="tmb-progress-track">
                                <div class="tmb-progress-fill" style="width: {{ min(100, max(0, $pctHeavy)) }}%; background-color: #b91c1c;"></div>
                            </div>
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('inventoryDoughnutChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Baru', 'Baik', 'Rusak', 'Rusak Berat'],
                    datasets: [{
                        data: [
                            {{ $newVal }},
                            {{ $goodVal }},
                            {{ $brokenVal }},
                            {{ $brokenHeavyVal }}
                        ],
                        backgroundColor: [
                            '#10b981',
                            '#f59e0b',
                            '#f43f5e',
                            '#b91c1c'
                        ],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    cutout: '73%'
                }
            });
        });
    </script>
</x-app-layout>