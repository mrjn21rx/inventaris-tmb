<x-app-layout>
    @php
        $currentCode = old('code', $item->code ?? $item->item_code ?? '');
    @endphp

    <style>
        .tmb-edit-wrap {
            width: 100%;
            max-width: 56rem;
            margin: 0 auto;
            padding: 16px 0 24px 0;
        }

        .tmb-edit-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 22px;
            box-shadow: 0 10px 24px -14px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .tmb-edit-top {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 18px 24px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .tmb-asset-strip {
            background-color: #eff6ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .tmb-section-box {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px 20px;
        }

        .tmb-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #1e3a8a;
            margin-bottom: 14px;
        }

        .tmb-field-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-bottom: 5px;
        }

        /* Keterangan tersembunyi default, muncul hanya saat bagian terkait dipilih */
        .tmb-field-hint {
            display: none;
            align-items: flex-start;
            gap: 6px;
            margin-top: 7px;
            padding: 7px 11px;
            border-radius: 10px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            font-size: 0.73rem;
            font-weight: 600;
            line-height: 1.4;
            animation: tmbEditHintFade 0.14s ease-out;
        }
        .tmb-field-hint.is-visible {
            display: flex;
        }
        .tmb-field-hint.is-neutral {
            background-color: #ffffff;
            border-color: #cbd5e1;
            color: #334155;
        }
        @keyframes tmbEditHintFade {
            from {
                opacity: 0;
                transform: translateY(-3px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tmb-input,
        .tmb-select,
        .tmb-textarea {
            width: 100%;
            padding: 10px 14px;
            background-color: #ffffff;
            color: #0f172a;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .tmb-input:focus,
        .tmb-select:focus,
        .tmb-textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        .tmb-code-group,
        .tmb-qty-group {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }

        .tmb-gen-btn,
        .tmb-step-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 0 13px;
            border-radius: 12px;
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1.5px solid #93c5fd;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .tmb-gen-btn:hover,
        .tmb-step-btn:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .tmb-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 9px;
        }

        .tmb-cond-chip {
            padding: 4px 11px;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            background-color: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.14s ease;
        }
        .tmb-cond-chip:hover {
            border-color: #2563eb;
            color: #1d4ed8;
            background-color: #eff6ff;
        }
        .tmb-cond-chip.is-active {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #1d4ed8;
        }

        .tmb-form-grid-2 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) {
            .tmb-form-grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .tmb-revert-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 15px;
            border-radius: 12px;
            background-color: #fffbeb;
            color: #b45309;
            border: 1.5px solid #fcd34d;
            font-size: 0.8125rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-revert-btn:hover {
            background-color: #f59e0b;
            color: #ffffff;
            border-color: #d97706;
        }

        .tmb-update-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.875rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 16px -8px rgba(37, 99, 235, 0.5);
            transition: transform 0.15s ease, filter 0.15s ease;
            white-space: nowrap;
        }
        .tmb-update-btn:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .tmb-cancel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 18px;
            border-radius: 12px;
            background-color: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .tmb-cancel-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
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
        <div class="flex items-center justify-between gap-3 py-0.5">
            <div class="flex items-center gap-3 min-w-0">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2.5 rounded-xl shadow-sm flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-lg sm:text-xl leading-tight truncate">
                            Edit Data Barang
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full flex-shrink-0">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #334155; font-weight: 600;" class="flex items-center gap-1.5 text-xs leading-snug mt-0.5 truncate">
                        <span style="background-color: #2563eb;" class="w-1.5 h-1.5 rounded-full flex-shrink-0"></span>
                        <span>
                            Perbarui informasi, kode, dan spesifikasi aset
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
        <div class="tmb-edit-wrap">
            <div class="tmb-edit-card">

                <!-- Header Strip di Dalam Kartu -->
                <div class="tmb-edit-top">
                    <div>
                        <span style="background-color: rgba(255, 255, 255, 0.16); color: #ffffff;" class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1">
                            <span style="background-color: #93c5fd;" class="w-1.5 h-1.5 rounded-full"></span>
                            Mode Pembaruan Aset #{{ $item->id }}
                        </span>
                        <h3 class="text-sm sm:text-base font-extrabold tracking-tight text-white">
                            {{ $item->name }}
                        </h3>
                    </div>

                    <a href="{{ route('items.index') }}" style="background-color: rgba(255, 255, 255, 0.14); border: 1px solid rgba(255, 255, 255, 0.25); color: #ffffff;" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-white/20 transition whitespace-nowrap flex-shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>

                <div class="p-5 sm:p-7">

                    <!-- Strip Status Perubahan Aset -->
                    <div class="tmb-asset-strip">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <span style="background-color: #ffffff; color: #1d4ed8; border: 1px solid #93c5fd;" class="px-2.5 py-1 rounded-lg font-mono text-xs font-bold">
                                {{ $currentCode ?: 'Tanpa Kode' }}
                            </span>
                            <span class="text-xs font-semibold text-blue-900">
                                Lokasi awal: <strong>{{ $item->location }}</strong>
                            </span>
                        </div>

                        <span id="tmbChangeBadge" style="background-color: #ffffff; color: #475569; border: 1px solid #cbd5e1;" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold">
                            <span id="tmbChangeDot" style="background-color: #94a3b8;" class="w-2 h-2 rounded-full"></span>
                            <span id="tmbChangeText">Belum ada perubahan</span>
                        </span>
                    </div>

                    @if($errors->any())
                        <div style="background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;" class="p-4 mb-5 rounded-2xl text-xs font-semibold">
                            <p class="font-extrabold text-sm text-red-800 mb-1">Periksa kembali isian formulir:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="tmbEditItemForm" action="{{ route('items.update', $item->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Bagian 1: Identitas Barang -->
                        <div class="tmb-section-box">
                            <div class="tmb-section-title">
                                <span style="background-color: #2563eb;" class="w-2 h-2 rounded-full"></span>
                                <span>1. Identitas Barang</span>
                            </div>

                            <div class="space-y-4">
                                <!-- Nama Barang -->
                                <div class="tmb-field-group">
                                    <label for="edit_item_name" class="tmb-field-label">Nama Barang <span class="text-rose-600">*</span></label>
                                    <input id="edit_item_name" type="text" name="name" value="{{ old('name', $item->name) }}" data-original="{{ $item->name }}" class="tmb-input" required>
                                    <div class="tmb-field-hint">
                                        <span>•</span>
                                        <span>Wajib diisi dengan nama barang yang jelas dan akurat sesuai aset fisik.</span>
                                    </div>
                                </div>

                                <div class="tmb-form-grid-2">
                                    <!-- Kode Barang -->
                                    <div class="tmb-field-group">
                                        <label for="edit_item_code" class="tmb-field-label">Kode Barang</label>
                                        <div class="tmb-code-group">
                                            <input id="edit_item_code" type="text" name="code" value="{{ $currentCode }}" data-original="{{ $item->code ?? $item->item_code ?? '' }}" placeholder="Contoh: RRI-TMB-001 atau Tidak ada kode barang" class="tmb-input font-mono">
                                            <button type="button" id="tmbEditGenerateCodeBtn" class="tmb-gen-btn" title="Buat kode baru otomatis">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                                <span>Auto Kode</span>
                                            </button>
                                        </div>
                                        <div class="tmb-field-hint is-neutral">
                                            <span>•</span>
                                            <span>Perbarui kode jika diperlukan, klik <strong>Auto Kode</strong>, ketik <strong>Tidak ada kode barang</strong> bila belum ada kodenya, atau biarkan kosong.</span>
                                        </div>
                                    </div>

                                    <!-- Kategori -->
                                    <div class="tmb-field-group">
                                        <label for="edit_item_category" class="tmb-field-label">Kategori <span class="text-rose-600">*</span></label>
                                        <select id="edit_item_category" name="category_id" data-original="{{ $item->category_id }}" class="tmb-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="tmb-field-hint">
                                            <span>•</span>
                                            <span>Wajib dipilih sesuai kelompok kategori aset/barang.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian 2: Kondisi, Jumlah & Lokasi -->
                        <div class="tmb-section-box">
                            <div class="tmb-section-title">
                                <span style="background-color: #10b981;" class="w-2 h-2 rounded-full"></span>
                                <span>2. Status, Jumlah & Penempatan</span>
                            </div>

                            <div class="space-y-4">
                                <div class="tmb-form-grid-2">
                                    <!-- Jumlah + Stepper -->
                                    <div class="tmb-field-group">
                                        <label for="edit_item_quantity" class="tmb-field-label">Jumlah (Unit) <span class="text-rose-600">*</span></label>
                                        <div class="tmb-qty-group">
                                            <button type="button" id="tmbQtyMinusBtn" class="tmb-step-btn" title="Kurangi 1 unit">−</button>
                                            <input id="edit_item_quantity" type="number" name="quantity" min="0" value="{{ old('quantity', $item->quantity) }}" data-original="{{ $item->quantity }}" class="tmb-input text-center" required>
                                            <button type="button" id="tmbQtyPlusBtn" class="tmb-step-btn" title="Tambah 1 unit">+</button>
                                        </div>
                                        <div class="tmb-field-hint">
                                            <span>•</span>
                                            <span>Wajib diisi angka jumlah fisik unit barang saat ini (bisa diketik atau pakai tombol − / +).</span>
                                        </div>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="tmb-field-group">
                                        <label for="edit_item_location" class="tmb-field-label">Lokasi <span class="text-rose-600">*</span></label>
                                        <input id="edit_item_location" type="text" name="location" value="{{ old('location', $item->location) }}" data-original="{{ $item->location }}" placeholder="Contoh: Studio Pro 1 / Ruang Maintenance TMB" class="tmb-input" required>
                                        <div class="tmb-field-hint">
                                            <span>•</span>
                                            <span>Wajib diisi lokasi atau ruangan tempat barang berada saat ini.</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kondisi + Quick Chips -->
                                <div class="tmb-field-group">
                                    <label for="edit_item_condition" class="tmb-field-label">Kondisi <span class="text-rose-600">*</span></label>
                                    <input id="edit_item_condition" type="text" name="condition" value="{{ old('condition', $item->condition) }}" data-original="{{ $item->condition }}" list="condition-options" class="tmb-input" required placeholder="Pilih cepat di bawah atau ketik kondisi...">

                                    <datalist id="condition-options">
                                        <option value="Baru">
                                        <option value="Baik">
                                        <option value="Rusak">
                                        <option value="Rusak Berat">
                                        <option value="PSU OK">
                                        <option value="PSU LEMAH">
                                    </datalist>

                                    <div class="tmb-chip-row" aria-label="Pilihan cepat kondisi">
                                        @foreach(['Baru', 'Baik', 'Rusak', 'Rusak Berat', 'PSU OK', 'PSU LEMAH'] as $presetCond)
                                            <button type="button" class="tmb-cond-chip" data-cond="{{ $presetCond }}">
                                                {{ $presetCond }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <div class="tmb-field-hint">
                                        <span>•</span>
                                        <span>Wajib diisi: pilih kondisi cepat di atas atau ketik kondisi terbaru sesuai pengecekan.</span>
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="tmb-field-group">
                                    <label for="edit_item_description" class="tmb-field-label">Keterangan (Opsional)</label>
                                    <textarea id="edit_item_description" name="description" rows="3" data-original="{{ $item->description ?? '' }}" placeholder="Tambahkan catatan kondisi fisik, nomor seri, atau keterangan penyimpanan..." class="tmb-textarea">{{ old('description', $item->description) }}</textarea>
                                    <div class="tmb-field-hint is-neutral">
                                        <span>•</span>
                                        <span>Opsional: boleh dikosongkan atau diisi catatan tambahan seperti nomor seri atau riwayat pemindahan/perbaikan.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-slate-200">
                            <button type="button" id="tmbRevertChangesBtn" class="tmb-revert-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                <span>Kembalikan Awal</span>
                            </button>

                            <div class="flex items-center gap-2.5 justify-end">
                                <a href="{{ route('items.index') }}" class="tmb-cancel-btn">
                                    Batal
                                </a>
                                <button type="submit" id="tmbUpdateItemBtn" class="tmb-update-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Update Barang</span>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Interaksi & Popup Modern -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fieldGroups = document.querySelectorAll('.tmb-field-group');

            function showGroupHint(group) {
                fieldGroups.forEach(other => {
                    const otherHint = other.querySelector('.tmb-field-hint');
                    if (otherHint) {
                        otherHint.classList.toggle('is-visible', other === group);
                    }
                });
            }

            fieldGroups.forEach(group => {
                group.addEventListener('focusin', function () {
                    showGroupHint(group);
                });
                group.addEventListener('click', function () {
                    showGroupHint(group);
                });
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.tmb-field-group')) {
                    fieldGroups.forEach(group => {
                        const hint = group.querySelector('.tmb-field-hint');
                        if (hint) hint.classList.remove('is-visible');
                    });
                }
            });

            const nameInput = document.getElementById('edit_item_name');
            const codeInput = document.getElementById('edit_item_code');
            const catSelect = document.getElementById('edit_item_category');
            const qtyInput = document.getElementById('edit_item_quantity');
            const locInput = document.getElementById('edit_item_location');
            const condInput = document.getElementById('edit_item_condition');
            const descInput = document.getElementById('edit_item_description');
            const chips = document.querySelectorAll('.tmb-cond-chip');
            const genBtn = document.getElementById('tmbEditGenerateCodeBtn');
            const minusBtn = document.getElementById('tmbQtyMinusBtn');
            const plusBtn = document.getElementById('tmbQtyPlusBtn');
            const revertBtn = document.getElementById('tmbRevertChangesBtn');
            const form = document.getElementById('tmbEditItemForm');
            const updateBtn = document.getElementById('tmbUpdateItemBtn');

            const changeBadge = document.getElementById('tmbChangeBadge');
            const changeDot = document.getElementById('tmbChangeDot');
            const changeText = document.getElementById('tmbChangeText');

            const trackedInputs = [nameInput, codeInput, catSelect, qtyInput, locInput, condInput, descInput].filter(Boolean);

            function syncActiveChip() {
                if (!condInput) return;
                const currentVal = condInput.value.trim().toLowerCase();
                chips.forEach(chip => {
                    const isMatch = chip.dataset.cond.toLowerCase() === currentVal;
                    chip.classList.toggle('is-active', isMatch);
                });
            }

            function updateChangeCounter() {
                let changedCount = 0;
                trackedInputs.forEach(el => {
                    const orig = (el.dataset.original ?? '').toString();
                    const curr = (el.value ?? '').toString();
                    if (orig !== curr) changedCount++;
                });

                if (!changeBadge || !changeDot || !changeText) return;
                if (changedCount > 0) {
                    changeBadge.style.backgroundColor = '#eff6ff';
                    changeBadge.style.color = '#1d4ed8';
                    changeBadge.style.borderColor = '#93c5fd';
                    changeDot.style.backgroundColor = '#2563eb';
                    changeText.textContent = `${changedCount} bagian diubah`;
                } else {
                    changeBadge.style.backgroundColor = '#ffffff';
                    changeBadge.style.color = '#475569';
                    changeBadge.style.borderColor = '#cbd5e1';
                    changeDot.style.backgroundColor = '#94a3b8';
                    changeText.textContent = 'Belum ada perubahan';
                }
            }

            chips.forEach(chip => {
                chip.addEventListener('click', function () {
                    if (!condInput) return;
                    condInput.value = this.dataset.cond;
                    syncActiveChip();
                    updateChangeCounter();
                    condInput.dispatchEvent(new Event('change'));
                });
            });

            if (condInput) {
                condInput.addEventListener('input', function () {
                    syncActiveChip();
                    updateChangeCounter();
                });
                syncActiveChip();
            }

            if (minusBtn && qtyInput) {
                minusBtn.addEventListener('click', function () {
                    const val = parseInt(qtyInput.value || '0', 10);
                    qtyInput.value = Math.max(0, val - 1);
                    updateChangeCounter();
                });
            }

            if (plusBtn && qtyInput) {
                plusBtn.addEventListener('click', function () {
                    const val = parseInt(qtyInput.value || '0', 10);
                    qtyInput.value = Math.max(0, val + 1);
                    updateChangeCounter();
                });
            }

            if (genBtn && codeInput) {
                genBtn.addEventListener('click', function () {
                    const rawName = (nameInput && nameInput.value.trim()) ? nameInput.value.trim() : 'BRG';
                    const slug = rawName
                        .toUpperCase()
                        .replace(/[^A-Z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '')
                        .slice(0, 6) || 'BRG';

                    const now = new Date();
                    const dd = String(now.getDate()).padStart(2, '0');
                    const mm = String(now.getMonth() + 1).padStart(2, '0');
                    const yy = String(now.getFullYear()).slice(-2);
                    const rand = String(Math.floor(10 + Math.random() * 89));

                    codeInput.value = `RRI-${slug}-${dd}${mm}${yy}-${rand}`;
                    updateChangeCounter();
                    codeInput.focus();
                });
            }

            trackedInputs.forEach(el => {
                el.addEventListener('input', updateChangeCounter);
                el.addEventListener('change', updateChangeCounter);
            });
            updateChangeCounter();

            if (revertBtn) {
                revertBtn.addEventListener('click', function () {
                    trackedInputs.forEach(el => {
                        el.value = el.dataset.original ?? '';
                    });
                    syncActiveChip();
                    updateChangeCounter();
                });
            }

            if (form) {
                form.addEventListener('submit', function () {
                    if (updateBtn) {
                        updateBtn.disabled = true;
                        updateBtn.style.opacity = '0.8';
                    }
                });
            }

            @if($errors->any())
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Perubahan Belum Valid',
                        text: @json($errors->first()),
                        confirmButtonText: 'Perbaiki Isian',
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

            @if(session('success'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Diperbarui!',
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
        });
    </script>
</x-app-layout>