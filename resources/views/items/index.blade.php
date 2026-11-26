<x-app-layout>
    @php
        $totalRows = method_exists($items, 'total') ? $items->total() : $items->count();
        $hasFilter = request()->filled('category_id') || request()->filled('condition');
    @endphp

    <style>
        .tmb-items-wrap {
            width: 100%;
            max-width: 86rem;
            margin: 0 auto;
            padding: 14px 0 24px 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .tmb-surface-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 20px;
            box-shadow: 0 8px 20px -12px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .tmb-toolbar {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 16px;
        }
        @media (min-width: 768px) {
            .tmb-toolbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 18px;
            }
        }

        .tmb-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.875rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 6px 14px -6px rgba(37, 99, 235, 0.45);
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .tmb-btn-primary:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 10px 18px -8px rgba(37, 99, 235, 0.5);
        }

        .tmb-count-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 12px;
            background-color: #eff6ff;
            color: #0f172a;
            border: 1.5px solid #bfdbfe;
            font-size: 0.8125rem;
            font-weight: 800;
            white-space: nowrap;
            flex-shrink: 0;
            line-height: 1.2;
        }

        .tmb-count-dot {
            width: 8px;
            height: 8px;
            min-width: 8px;
            min-height: 8px;
            border-radius: 9999px;
            background-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.16);
            flex-shrink: 0;
        }

        .tmb-count-num {
            color: #1d4ed8;
            font-weight: 900;
        }

        .tmb-btn-excel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 14px;
            border-radius: 12px;
            background-color: #ecfdf5;
            color: #047857;
            border: 1.5px solid #34d399;
            font-size: 0.8125rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-btn-excel:hover {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
        }

        .tmb-btn-pdf {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 14px;
            border-radius: 12px;
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1.5px solid #f87171;
            font-size: 0.8125rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-btn-pdf:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        .tmb-filter-box {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        .tmb-select {
            width: 100%;
            min-width: 0;
            padding: 9px 14px;
            background-color: #ffffff;
            color: #0f172a;
            border: 1.5px solid #cbd5e1;
            border-radius: 11px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .tmb-select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        .tmb-btn-search {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 11px;
            background-color: #0f172a;
            color: #ffffff;
            border: 1.5px solid #0f172a;
            font-size: 0.8125rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-btn-search:hover {
            background-color: #1e293b;
            border-color: #1e293b;
        }

        .tmb-btn-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 15px;
            border-radius: 11px;
            background-color: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-btn-reset:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* Mode Mobile Card List (< 768px) */
        .tmb-mobile-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @media (min-width: 768px) {
            .tmb-mobile-cards {
                display: none;
            }
        }

        .tmb-item-card {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 18px;
            padding: 15px;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
        }
        .tmb-item-card:hover,
        .tmb-item-card:active {
            background-color: #eff6ff;
            border-color: #93c5fd;
            box-shadow: 0 8px 18px -12px rgba(37, 99, 235, 0.25);
        }

        .tmb-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .tmb-card-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border-radius: 9px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .tmb-card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            word-break: break-word;
        }

        .tmb-card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .tmb-card-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-top: 12px;
        }

        .tmb-card-meta-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
        }

        .tmb-card-meta-label {
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 2px;
        }

        .tmb-card-meta-val {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
            line-height: 1.3;
        }
        .tmb-card-meta-val.is-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.75rem;
            color: #1d4ed8;
        }

        .tmb-card-note {
            margin-top: 8px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
        }

        .tmb-card-actions {
            margin-top: 12px;
            padding-top: 11px;
            border-top: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        /* Mode Tabel Desktop (>= 768px) */
        .tmb-table-shell {
            display: none;
        }
        @media (min-width: 768px) {
            .tmb-table-shell {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border: 1.5px solid #cbd5e1;
                border-radius: 16px;
                background-color: #ffffff;
            }
        }

        .tmb-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8125rem;
            color: #0f172a;
        }

        .tmb-table thead {
            background: linear-gradient(135deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
        }

        .tmb-table thead th {
            padding: 13px 14px;
            font-size: 0.6875rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #ffffff;
            border-right: 1px solid rgba(255, 255, 255, 0.14);
            white-space: nowrap;
        }
        .tmb-table thead th:last-child {
            border-right: none;
        }

        .tmb-table tbody tr.tmb-row-clickable {
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            transition: background-color 0.14s ease;
        }
        .tmb-table tbody tr.tmb-row-clickable:last-child {
            border-bottom: none;
        }
        .tmb-table tbody tr.tmb-row-clickable:hover {
            background-color: #eff6ff;
        }

        .tmb-table tbody td {
            padding: 13px 14px;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .tmb-table tbody td:last-child {
            border-right: none;
        }

        /* Tombol Edit & Hapus */
        .tmb-action-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 10px;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1.5px solid #93c5fd;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-action-edit:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .tmb-action-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 10px;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #f87171;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-action-delete:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #b91c1c;
            box-shadow: 0 6px 12px -6px rgba(220, 38, 38, 0.45);
        }

        /* Modal Detail Barang */
        .tmb-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 60;
            background-color: rgba(15, 23, 42, 0.52);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .tmb-modal-backdrop.is-open {
            display: flex;
        }

        .tmb-modal-card {
            width: 100%;
            max-width: 34rem;
            max-height: 90vh;
            background-color: #ffffff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.34);
            border: 1.5px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            animation: tmbModalPop 0.16s ease-out;
        }
        @keyframes tmbModalPop {
            from {
                opacity: 0;
                transform: translateY(8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .tmb-modal-header {
            background: linear-gradient(120deg, #1d4ed8 0%, #2563eb 60%, #3b82f6 100%);
            color: #ffffff;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
        }

        .tmb-modal-badge {
            background-color: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 12px;
            padding: 8px 11px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #ffffff;
            flex-shrink: 0;
        }

        .tmb-modal-close {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background-color: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.15s ease;
            flex-shrink: 0;
        }
        .tmb-modal-close:hover {
            background-color: rgba(255, 255, 255, 0.28);
        }

        .tmb-modal-body {
            padding: 18px 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: #f8fafc;
        }

        .tmb-detail-grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 540px) {
            .tmb-detail-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .tmb-info-tile {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 15px;
        }
        .tmb-info-tile.is-highlight {
            background-color: #eff6ff;
            border-color: #93c5fd;
        }

        .tmb-info-label {
            font-size: 0.6875rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 5px;
            line-height: 1.2;
        }
        .tmb-info-tile.is-highlight .tmb-info-label {
            color: #1d4ed8;
        }

        .tmb-info-val {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
            line-height: 1.35;
        }
        .tmb-info-tile.is-highlight .tmb-info-val {
            color: #1d4ed8;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .tmb-modal-footer {
            background-color: #ffffff;
            border-top: 1.5px solid #e2e8f0;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .tmb-btn-close-modal {
            padding: 9px 18px;
            border-radius: 11px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px -6px rgba(37, 99, 235, 0.45);
        }
        .tmb-btn-close-modal:hover {
            filter: brightness(1.05);
        }

        /* Popup SweetAlert2 Modern & Kompak */
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-base sm:text-xl leading-tight">
                            Manajemen Barang
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #334155; font-weight: 600;" class="flex items-start sm:items-center gap-1.5 text-[11px] sm:text-xs leading-snug mt-1">
                        <span style="background-color: #2563eb;" class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-1 sm:mt-0"></span>
                        <span>
                            Pusat kontrol, pendataan, dan monitoring aset harian
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
        <div class="tmb-items-wrap">
            <div class="tmb-surface-card">
                <div class="p-4 sm:p-7">

                    <!-- Toolbar Aksi Atas -->
                    <div class="tmb-toolbar">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <a href="{{ route('items.create', ['condition' => request('condition')]) }}" class="tmb-btn-primary w-full sm:w-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Barang Baru</span>
                            </a>

                            <span class="tmb-count-pill">
                                <span class="tmb-count-dot"></span>
                                <span><span class="tmb-count-num">{{ $totalRows }}</span> Data Tercatat</span>
                            </span>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap justify-start md:justify-end">
                            <a href="{{ route('items.excel') }}" class="tmb-btn-excel flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span>Export Excel</span>
                            </a>
                            <a href="{{ route('items.pdf') }}" class="tmb-btn-pdf flex-1 sm:flex-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- Filter Bar -->
                    <form method="GET" action="{{ route('items.index') }}" class="tmb-filter-box">
                        <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                            <div class="w-full md:w-1/3">
                                <select name="category_id" class="tmb-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-1/3">
                                <select name="condition" class="tmb-select">
                                    <option value="">Semua Kondisi</option>
                                    <option value="Baru" {{ request('condition') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak" {{ request('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="Rusak Berat" {{ request('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                            </div>

                            <div class="flex gap-2 w-full md:w-auto md:flex-1 justify-end">
                                <button type="submit" class="tmb-btn-search flex-1 md:flex-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span>Cari</span>
                                </button>
                                <a href="{{ route('items.index') }}" class="tmb-btn-reset">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Notifikasi Sukses -->
                    @if(session('success'))
                        <div style="background-color: #ecfdf5; color: #065f46; border: 1.5px solid #6ee7b7;" class="p-4 mb-5 text-sm font-bold rounded-xl flex items-center gap-2.5 shadow-2xs">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- TAMPILAN CARD KHUSUS HP (< 768px) -->
                    <div class="tmb-mobile-cards">
                        @forelse ($items as $index => $item)
                            @php
                                $rowNumber = method_exists($items, 'firstItem') ? $items->firstItem() + $index : $index + 1;
                                $itemCode = $item->code ?? $item->item_code ?? '-';
                                $catName = $item->category->name ?? '-';
                                $catLower = strtolower(trim($catName));

                                $catStyle = 'display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; white-space: nowrap;';
                                if (str_contains($catLower, 'elektronik')) {
                                    $catStyle .= ' background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;';
                                } elseif (str_contains($catLower, 'furnitur')) {
                                    $catStyle .= ' background-color: #f0f9ff; color: #0369a1; border: 1.5px solid #7dd3fc;';
                                } elseif (str_contains($catLower, 'alat tulis kantor') || str_contains($catLower, 'atk')) {
                                    $catStyle .= ' background-color: #faf5ff; color: #7e22ce; border: 1.5px solid #d8b4fe;';
                                } else {
                                    $catStyle .= ' background-color: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1;';
                                }

                                $cond = strtolower(trim($item->condition));
                                $condStyle = 'display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 800; white-space: nowrap;';
                                if (str_contains($cond, 'baru')) {
                                    $condStyle .= ' background-color: #ecfdf5; color: #047857; border: 1.5px solid #6ee7b7;';
                                } elseif (str_contains($cond, 'baik')) {
                                    $condStyle .= ' background-color: #fffbeb; color: #b45309; border: 1.5px solid #fcd34d;';
                                } elseif (str_contains($cond, 'rusak berat')) {
                                    $condStyle .= ' background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;';
                                } elseif (str_contains($cond, 'rusak')) {
                                    $condStyle .= ' background-color: #fff1f2; color: #be123c; border: 1.5px solid #fda4af;';
                                } else {
                                    $condStyle .= ' background-color: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1;';
                                }
                            @endphp

                            <div class="tmb-item-card tmb-detail-trigger"
                                 data-no="#{{ $rowNumber }}"
                                 data-name="{{ e($item->name) }}"
                                 data-code="{{ e($itemCode) }}"
                                 data-category="{{ e($catName) }}"
                                 data-quantity="{{ $item->quantity }}"
                                 data-condition="{{ e($item->condition) }}"
                                 data-location="{{ e($item->location ?? '-') }}"
                                 data-description="{{ e($item->description ?: '-') }}"
                                 data-edit-url="{{ route('items.edit', $item->id) }}">

                                <div class="tmb-card-head">
                                    <div class="flex items-start gap-2.5 min-w-0">
                                        <span class="tmb-card-num">{{ $rowNumber }}</span>
                                        <div class="min-w-0">
                                            <div class="tmb-card-title">{{ $item->name }}</div>
                                            <div class="text-[11px] font-semibold text-slate-500 mt-0.5">
                                                Ketuk kartu untuk detail lengkap
                                            </div>
                                        </div>
                                    </div>
                                    <span style="background-color: #ffffff; color: #0f172a; border: 1.5px solid #cbd5e1;" class="px-2.5 py-1 rounded-lg text-xs font-extrabold whitespace-nowrap flex-shrink-0">
                                        {{ $item->quantity }} Unit
                                    </span>
                                </div>

                                <div class="tmb-card-badges">
                                    <span style="{{ $catStyle }}">{{ $catName }}</span>
                                    <span style="{{ $condStyle }}">{{ $item->condition }}</span>
                                </div>

                                <div class="tmb-card-meta-grid">
                                    <div class="tmb-card-meta-box">
                                        <div class="tmb-card-meta-label">Kode Barang</div>
                                        <div class="tmb-card-meta-val is-code">{{ $itemCode }}</div>
                                    </div>
                                    <div class="tmb-card-meta-box">
                                        <div class="tmb-card-meta-label">Lokasi</div>
                                        <div class="tmb-card-meta-val">{{ $item->location }}</div>
                                    </div>
                                </div>

                                <div class="tmb-card-note">
                                    <div class="tmb-card-meta-label">Keterangan</div>
                                    <div class="tmb-card-meta-val" style="font-weight: 600; color: #334155;">
                                        {{ $item->description ?: '-' }}
                                    </div>
                                </div>

                                <div class="tmb-card-actions" onclick="event.stopPropagation();">
                                    <a href="{{ route('items.edit', $item->id) }}" class="tmb-action-edit w-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="w-full form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tmb-action-delete w-full">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-8 text-center">
                                <p class="text-sm font-extrabold text-slate-700">
                                    {{ $hasFilter ? 'Data barang sesuai filter tidak ditemukan.' : 'Belum ada data barang tersedia.' }}
                                </p>
                                <p class="text-xs font-medium text-slate-500 mt-1">
                                    {{ $hasFilter ? 'Coba ubah pilihan filter atau klik Reset untuk menampilkan semua barang.' : 'Klik tombol Tambah Barang Baru untuk mulai mencatat inventaris.' }}
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- TAMPILAN TABEL DESKTOP (>= 768px) -->
                    <div class="tmb-table-shell">
                        <table class="tmb-table">
                            <thead>
                                <tr>
                                    <th class="w-12 text-center">No</th>
                                    <th class="text-left">Nama Barang</th>
                                    <th class="text-left">Kode Barang</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-center">Jml</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-left">Lokasi</th>
                                    <th class="text-left">Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $index => $item)
                                    @php
                                        $rowNumber = method_exists($items, 'firstItem') ? $items->firstItem() + $index : $index + 1;
                                        $itemCode = $item->code ?? $item->item_code ?? '-';
                                        $catName = $item->category->name ?? '-';
                                        $catLower = strtolower(trim($catName));

                                        $catStyle = 'display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; white-space: nowrap;';
                                        if (str_contains($catLower, 'elektronik')) {
                                            $catStyle .= ' background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;';
                                        } elseif (str_contains($catLower, 'furnitur')) {
                                            $catStyle .= ' background-color: #f0f9ff; color: #0369a1; border: 1.5px solid #7dd3fc;';
                                        } elseif (str_contains($catLower, 'alat tulis kantor') || str_contains($catLower, 'atk')) {
                                            $catStyle .= ' background-color: #faf5ff; color: #7e22ce; border: 1.5px solid #d8b4fe;';
                                        } else {
                                            $catStyle .= ' background-color: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1;';
                                        }

                                        $cond = strtolower(trim($item->condition));
                                        $condStyle = 'display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 800; white-space: nowrap;';
                                        if (str_contains($cond, 'baru')) {
                                            $condStyle .= ' background-color: #ecfdf5; color: #047857; border: 1.5px solid #6ee7b7;';
                                        } elseif (str_contains($cond, 'baik')) {
                                            $condStyle .= ' background-color: #fffbeb; color: #b45309; border: 1.5px solid #fcd34d;';
                                        } elseif (str_contains($cond, 'rusak berat')) {
                                            $condStyle .= ' background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;';
                                        } elseif (str_contains($cond, 'rusak')) {
                                            $condStyle .= ' background-color: #fff1f2; color: #be123c; border: 1.5px solid #fda4af;';
                                        } else {
                                            $condStyle .= ' background-color: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1;';
                                        }
                                    @endphp
                                    <tr class="tmb-row-clickable tmb-detail-trigger"
                                        data-no="#{{ $rowNumber }}"
                                        data-name="{{ e($item->name) }}"
                                        data-code="{{ e($itemCode) }}"
                                        data-category="{{ e($catName) }}"
                                        data-quantity="{{ $item->quantity }}"
                                        data-condition="{{ e($item->condition) }}"
                                        data-location="{{ e($item->location ?? '-') }}"
                                        data-description="{{ e($item->description ?: '-') }}"
                                        data-edit-url="{{ route('items.edit', $item->id) }}"
                                        title="Klik untuk melihat detail barang">

                                        <!-- No -->
                                        <td class="text-center font-extrabold text-slate-900">
                                            {{ $rowNumber }}
                                        </td>

                                        <!-- Nama Barang -->
                                        <td class="font-extrabold text-slate-900 leading-snug text-left whitespace-normal break-words min-w-[150px]">
                                            {{ $item->name }}
                                        </td>

                                        <!-- Kode Barang -->
                                        <td class="text-left whitespace-normal break-words">
                                            <span style="background-color: #f8fafc; color: #0f172a; border: 1.5px solid #cbd5e1;" class="inline-block px-2.5 py-1 rounded-lg font-mono text-xs font-bold break-all">
                                                {{ $itemCode }}
                                            </span>
                                        </td>

                                        <!-- Kategori -->
                                        <td class="whitespace-normal break-words text-center">
                                            <span style="{{ $catStyle }}">{{ $catName }}</span>
                                        </td>

                                        <!-- Jumlah -->
                                        <td class="text-center">
                                            <span style="background-color: #f1f5f9; color: #0f172a; border: 1.5px solid #cbd5e1;" class="inline-block px-2.5 py-0.5 rounded-lg font-extrabold text-xs">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>

                                        <!-- Kondisi -->
                                        <td class="text-center whitespace-normal break-words">
                                            <span style="{{ $condStyle }}">{{ $item->condition }}</span>
                                        </td>

                                        <!-- Lokasi -->
                                        <td class="text-left text-slate-800 font-semibold whitespace-normal break-words min-w-[140px]">
                                            {{ $item->location }}
                                        </td>

                                        <!-- Keterangan -->
                                        <td class="text-left text-slate-700 font-medium whitespace-normal break-words min-w-[180px]">
                                            {{ $item->description ?: '-' }}
                                        </td>

                                        <!-- Aksi -->
                                        <td class="text-center whitespace-nowrap" onclick="event.stopPropagation();">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('items.edit', $item->id) }}" class="tmb-action-edit" title="Edit Barang">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>

                                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline form-hapus">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="tmb-action-delete" title="Hapus Barang">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-10 text-center">
                                            <p class="text-sm font-extrabold text-slate-700">
                                                {{ $hasFilter ? 'Data barang sesuai filter tidak ditemukan.' : 'Belum ada data barang tersedia.' }}
                                            </p>
                                            <p class="text-xs font-medium text-slate-500 mt-1">
                                                {{ $hasFilter ? 'Coba ubah pilihan filter atau klik Reset untuk menampilkan semua barang.' : 'Klik tombol Tambah Barang Baru untuk mulai mencatat inventaris.' }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($items, 'hasPages') && $items->hasPages())
                        <div class="mt-4">
                            {{ $items->withQueryString()->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup Detail Barang -->
    <div id="tmbItemDetailModal" class="tmb-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="tmbModalTitle">
        <div class="tmb-modal-card" onclick="event.stopPropagation();">
            <div class="tmb-modal-header">
                <div class="flex items-center gap-3 min-w-0">
                    <span id="tmbModalBadgeNo" class="tmb-modal-badge">#1</span>
                    <div class="min-w-0">
                        <h3 id="tmbModalTitle" class="text-base sm:text-lg font-extrabold tracking-tight text-white leading-tight truncate">
                            Detail Data Barang
                        </h3>
                        <p class="text-xs font-normal text-blue-100 mt-0.5 truncate">
                            Inventaris Divisi TMB • RRI Bukittinggi
                        </p>
                    </div>
                </div>
                <button type="button" id="tmbCloseModalBtn" class="tmb-modal-close" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="tmb-modal-body">
                <div class="tmb-info-tile">
                    <div class="tmb-info-label">Nama Barang</div>
                    <div id="tmbModalName" class="tmb-info-val">-</div>
                </div>

                <div class="tmb-detail-grid-2">
                    <div class="tmb-info-tile is-highlight">
                        <div class="tmb-info-label">Kode Barang</div>
                        <div id="tmbModalCode" class="tmb-info-val">-</div>
                    </div>
                    <div class="tmb-info-tile">
                        <div class="tmb-info-label">Kategori</div>
                        <div id="tmbModalCategory" class="tmb-info-val">-</div>
                    </div>
                </div>

                <div class="tmb-detail-grid-2">
                    <div class="tmb-info-tile">
                        <div class="tmb-info-label">Jumlah</div>
                        <div id="tmbModalQuantity" class="tmb-info-val">0</div>
                    </div>
                    <div class="tmb-info-tile">
                        <div class="tmb-info-label">Kondisi</div>
                        <div id="tmbModalCondition" class="tmb-info-val">-</div>
                    </div>
                </div>

                <div class="tmb-info-tile">
                    <div class="tmb-info-label">Lokasi</div>
                    <div id="tmbModalLocation" class="tmb-info-val">-</div>
                </div>

                <div class="tmb-info-tile">
                    <div class="tmb-info-label">Keterangan</div>
                    <div id="tmbModalDescription" class="tmb-info-val">-</div>
                </div>
            </div>

            <div class="tmb-modal-footer">
                <a id="tmbModalEditLink" href="#" class="tmb-action-edit" style="padding: 9px 15px; border-radius: 11px; font-size: 0.8125rem; font-weight: 700;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Barang</span>
                </a>
                <button type="button" id="tmbModalFooterClose" class="tmb-btn-close-modal">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Popup Notifikasi Sukses & Konfirmasi Hapus -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById('tmbItemDetailModal');
            const closeBtn = document.getElementById('tmbCloseModalBtn');
            const footerCloseBtn = document.getElementById('tmbModalFooterClose');

            function openItemModal(el) {
                if (!modal || !el) return;
                document.getElementById('tmbModalBadgeNo').textContent = el.dataset.no || '#';
                document.getElementById('tmbModalName').textContent = el.dataset.name || '-';
                document.getElementById('tmbModalCode').textContent = el.dataset.code || '-';
                document.getElementById('tmbModalCategory').textContent = el.dataset.category || '-';
                document.getElementById('tmbModalQuantity').textContent = el.dataset.quantity ?? '0';
                document.getElementById('tmbModalCondition').textContent = el.dataset.condition || '-';
                document.getElementById('tmbModalLocation').textContent = el.dataset.location || '-';
                document.getElementById('tmbModalDescription').textContent = el.dataset.description || '-';
                document.getElementById('tmbModalEditLink').setAttribute('href', el.dataset.editUrl || '#');

                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeItemModal() {
                if (!modal) return;
                modal.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.tmb-detail-trigger').forEach(el => {
                el.addEventListener('click', function () {
                    openItemModal(this);
                });
            });

            if (closeBtn) closeBtn.addEventListener('click', closeItemModal);
            if (footerCloseBtn) footerCloseBtn.addEventListener('click', closeItemModal);
            if (modal) {
                modal.addEventListener('click', closeItemModal);
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
                    closeItemModal();
                }
            });

            @if(session('success'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: @json(session('success')),
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
                }
            @endif

            document.querySelectorAll('.form-hapus').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Data Barang?',
                            text: 'Data barang ini akan dihapus secara permanen dari inventaris.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#475569',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'tmb-swal-card',
                                title: 'tmb-swal-title',
                                htmlContainer: 'tmb-swal-text',
                                actions: 'tmb-swal-actions',
                                confirmButton: 'tmb-swal-btn',
                                cancelButton: 'tmb-swal-btn'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else if (confirm('Apakah Anda yakin ingin menghapus data barang ini secara permanen?')) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>