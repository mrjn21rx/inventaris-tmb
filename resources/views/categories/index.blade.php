<x-app-layout>
    @php
        $totalCategories = method_exists($categories, 'total') ? $categories->total() : $categories->count();
    @endphp

    <style>
        .tmb-cat-wrap {
            width: 100%;
            max-width: 72rem;
            margin: 0 auto;
            padding: 14px 0 24px 0;
        }

        .tmb-cat-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
            align-items: start;
        }
        @media (min-width: 768px) {
            .tmb-cat-layout {
                grid-template-columns: 22rem minmax(0, 1fr);
            }
        }

        .tmb-cat-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 22px;
            box-shadow: 0 10px 24px -14px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .tmb-cat-top {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .tmb-cat-tag {
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

        .tmb-cat-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .tmb-cat-input {
            width: 100%;
            min-width: 0;
            padding: 10px 14px;
            background-color: #ffffff;
            color: #0f172a;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .tmb-cat-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        /* Keterangan muncul hanya saat bagian dipilih */
        .tmb-cat-hint {
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
            animation: tmbCatHintFade 0.14s ease-out;
        }
        .tmb-cat-hint.is-visible {
            display: flex;
        }
        @keyframes tmbCatHintFade {
            from {
                opacity: 0;
                transform: translateY(-3px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tmb-cat-btn-save {
            width: 100%;
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
            cursor: pointer;
            box-shadow: 0 8px 16px -8px rgba(37, 99, 235, 0.5);
            transition: transform 0.15s ease, filter 0.15s ease;
        }
        .tmb-cat-btn-save:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .tmb-cat-count-pill {
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

        .tmb-cat-table-shell {
            width: 100%;
            overflow-x: auto;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            background-color: #ffffff;
        }

        .tmb-cat-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
            color: #0f172a;
        }

        .tmb-cat-table thead {
            background-color: #f8fafc;
            border-bottom: 1.5px solid #e2e8f0;
        }

        .tmb-cat-table thead th {
            padding: 12px 16px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #334155;
            white-space: nowrap;
        }

        .tmb-cat-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.14s ease;
        }
        .tmb-cat-table tbody tr:last-child {
            border-bottom: none;
        }
        .tmb-cat-table tbody tr:hover {
            background-color: #eff6ff;
        }

        .tmb-cat-table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
        }

        .tmb-cat-num {
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

        .tmb-cat-del-btn {
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
        }
        .tmb-cat-del-btn:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #b91c1c;
            box-shadow: 0 6px 12px -6px rgba(220, 38, 38, 0.45);
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-base sm:text-xl leading-tight">
                            Kelola Kategori
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[9px] sm:text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #334155; font-weight: 600;" class="flex items-start sm:items-center gap-1.5 text-[11px] sm:text-xs leading-snug mt-1">
                        <span style="background-color: #2563eb;" class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-1 sm:mt-0"></span>
                        <span>
                            Atur kelompok kategori barang & aset di lingkungan
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
        <div class="tmb-cat-wrap">
            <div class="tmb-cat-layout">

                <!-- Kartu Form Tambah Kategori -->
                <div class="tmb-cat-card">
                    <div class="tmb-cat-top">
                        <div>
                            <div class="tmb-cat-tag">
                                <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                                <span>Form Kategori</span>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white leading-snug">
                                Tambah Kategori Baru
                            </h3>
                        </div>
                    </div>

                    <div class="p-5">
                        @if($errors->any())
                            <div style="background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;" class="p-3.5 mb-4 rounded-xl text-xs font-semibold">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form id="tmbAddCategoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="tmb-cat-field-group">
                                <label for="category_name" class="tmb-cat-label">
                                    Nama Kategori <span class="text-rose-600">*</span>
                                </label>
                                <input id="category_name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="tmb-cat-input"
                                       placeholder="Contoh: Perangkat Audio / Kabel"
                                       required>

                                <div class="tmb-cat-hint">
                                    <span>•</span>
                                    <span>Wajib diisi nama kategori yang singkat dan jelas (contoh: <strong>Elektronik</strong>, <strong>Furnitur</strong>, atau <strong>Alat Tulis Kantor</strong>).</span>
                                </div>
                            </div>

                            <button type="submit" id="tmbSaveCategoryBtn" class="tmb-cat-btn-save">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Simpan Kategori</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Kartu Daftar Kategori -->
                <div class="tmb-cat-card">
                    <div class="tmb-cat-top">
                        <div>
                            <div class="tmb-cat-tag">
                                <span style="background-color: #34d399;" class="w-1.5 h-1.5 rounded-full"></span>
                                <span>Daftar Klasifikasi</span>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white leading-snug">
                                Kategori Terdaftar
                            </h3>
                        </div>

                        <span class="tmb-cat-count-pill">
                            <span style="background-color: #60a5fa;" class="w-2 h-2 rounded-full"></span>
                            <span>{{ $totalCategories }} Kategori</span>
                        </span>
                    </div>

                    <div class="p-5">
                        @if(session('success'))
                            <div style="background-color: #ecfdf5; color: #065f46; border: 1.5px solid #6ee7b7;" class="p-3.5 mb-4 text-xs sm:text-sm font-bold rounded-xl flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <div class="tmb-cat-table-shell">
                            <table class="tmb-cat-table">
                                <thead>
                                    <tr>
                                        <th class="w-14 text-center">No</th>
                                        <th class="text-left">Nama Kategori</th>
                                        <th class="w-28 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $index => $cat)
                                        @php
                                            $catNum = method_exists($categories, 'firstItem') ? $categories->firstItem() + $index : $index + 1;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <span class="tmb-cat-num">{{ $catNum }}</span>
                                            </td>
                                            <td>
                                                <span class="font-extrabold text-slate-900">{{ $cat->name }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('categories.destroy', $cat->id) }}"
                                                      method="POST"
                                                      class="inline form-hapus-kategori"
                                                      data-category-name="{{ e($cat->name) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="tmb-cat-del-btn" title="Hapus {{ $cat->name }}">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-9 text-center">
                                                <p class="text-sm font-extrabold text-slate-700">Belum ada kategori terdaftar.</p>
                                                <p class="text-xs font-medium text-slate-500 mt-1">
                                                    Gunakan form di samping untuk menambahkan kategori baru.
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($categories, 'hasPages') && $categories->hasPages())
                            <div class="mt-4">
                                {{ $categories->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Interaksi & Popup Modern -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const catGroups = document.querySelectorAll('.tmb-cat-field-group');

            catGroups.forEach(group => {
                const hint = group.querySelector('.tmb-cat-hint');
                if (!hint) return;

                group.addEventListener('focusin', () => hint.classList.add('is-visible'));
                group.addEventListener('click', () => hint.classList.add('is-visible'));
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.tmb-cat-field-group')) {
                    document.querySelectorAll('.tmb-cat-hint').forEach(h => h.classList.remove('is-visible'));
                }
            });

            const addForm = document.getElementById('tmbAddCategoryForm');
            const saveBtn = document.getElementById('tmbSaveCategoryBtn');
            if (addForm) {
                addForm.addEventListener('submit', function () {
                    if (saveBtn) {
                        saveBtn.disabled = true;
                        saveBtn.style.opacity = '0.8';
                    }
                });
            }

            @if($errors->any())
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan Kategori',
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

            document.querySelectorAll('.form-hapus-kategori').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const catName = this.dataset.categoryName || 'kategori ini';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Kategori?',
                            text: `Kategori "${catName}" akan dihapus dari daftar.`,
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
                    } else if (confirm(`Apakah Anda yakin ingin menghapus kategori "${catName}"?`)) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>