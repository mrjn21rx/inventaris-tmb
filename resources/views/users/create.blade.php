<x-app-layout>
    @php
        $currentUser = auth()->user();
        $currentRole = strtolower(trim((string) ($currentUser->role ?? '')));
        $isSuperAdmin = in_array($currentRole, ['super-admin', 'superadmin', 'super_admin'], true);
        $selectedRole = old('role', 'admin');
    @endphp

    <style>
        .tmb-admin-wrap {
            width: 100%;
            max-width: 42rem;
            margin: 0 auto;
            padding: 10px 0 calc(20px + env(safe-area-inset-bottom, 0px)) 0;
        }
        @media (min-width: 640px) {
            .tmb-admin-wrap {
                padding: 14px 0 24px 0;
            }
        }

        .tmb-admin-shell {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 18px;
            box-shadow: 0 10px 24px -16px rgba(15, 23, 42, 0.14);
            overflow: hidden;
        }
        @media (min-width: 640px) {
            .tmb-admin-shell {
                border-radius: 20px;
            }
        }

        .tmb-admin-hero {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            padding: 14px 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        @media (min-width: 640px) {
            .tmb-admin-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                gap: 12px;
            }
        }

        .tmb-admin-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            width: fit-content;
            background-color: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #eff6ff;
            border-radius: 7px;
            padding: 2px 8px;
            font-size: 0.58rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }

        .tmb-admin-body {
            padding: 12px;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }
        @media (min-width: 640px) {
            .tmb-admin-body {
                padding: 16px 20px;
            }
        }

        .tmb-form-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        @media (min-width: 640px) {
            .tmb-form-stack {
                gap: 12px;
            }
        }

        .tmb-field-card {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 13px;
            padding: 10px 12px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        @media (min-width: 640px) {
            .tmb-field-card {
                border-radius: 14px;
                padding: 11px 14px;
            }
        }
        .tmb-field-card:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.11);
        }

        .tmb-field-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #334155;
            margin-bottom: 6px;
        }
        @media (min-width: 640px) {
            .tmb-field-label {
                font-size: 0.68rem;
                margin-bottom: 7px;
            }
        }

        .tmb-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .tmb-input-icon {
            position: absolute;
            left: 11px;
            color: #64748b;
            pointer-events: none;
            display: inline-flex;
            align-items: center;
        }

        .tmb-input {
            width: 100%;
            padding: 8px 36px 8px 34px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.35;
            -webkit-appearance: none;
            transition: all 0.15s ease;
        }
        @media (min-width: 640px) {
            .tmb-input {
                padding: 8px 38px 8px 36px;
                font-size: 0.82rem;
            }
        }
        .tmb-input.no-toggle {
            padding-right: 12px;
        }
        .tmb-input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.13);
        }
        .tmb-input.is-error {
            border-color: #ef4444;
            background-color: #fff8f8;
        }

        /* Pilihan Level Akun yang Jelas & Simpel */
        .tmb-role-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        @media (min-width: 640px) {
            .tmb-role-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
        }

        .tmb-role-option {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 9px 11px;
            border: 1.5px solid #cbd5e1;
            border-radius: 11px;
            background-color: #ffffff;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition: all 0.15s ease;
        }
        @media (min-width: 640px) {
            .tmb-role-option {
                padding: 10px 12px;
            }
        }
        .tmb-role-option:hover {
            border-color: #94a3b8;
            background-color: #f8fafc;
        }
        .tmb-role-option input[type="radio"] {
            margin-top: 2px;
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            cursor: pointer;
        }
        .tmb-role-option input[type="radio"][value="admin"] {
            accent-color: #2563eb;
        }
        .tmb-role-option input[type="radio"][value="super-admin"] {
            accent-color: #4f46e5;
        }
        .tmb-role-option.is-active-admin {
            border-color: #2563eb;
            background-color: #eff6ff;
            box-shadow: 0 0 0 2.5px rgba(37, 99, 235, 0.14);
        }
        .tmb-role-option.is-active-super {
            border-color: #4f46e5;
            background-color: #eef2ff;
            box-shadow: 0 0 0 2.5px rgba(79, 70, 229, 0.15);
        }

        .tmb-role-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
        }
        .tmb-role-desc {
            font-size: 0.67rem;
            font-weight: 500;
            color: #475569;
            line-height: 1.35;
            margin-top: 2px;
        }

        .tmb-toggle-pass-btn {
            position: absolute;
            right: 8px;
            padding: 4px;
            border-radius: 7px;
            color: #475569;
            background: transparent;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .tmb-toggle-pass-btn:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .tmb-strength-track {
            height: 5px;
            width: 100%;
            background-color: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            margin-top: 8px;
        }
        .tmb-strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 9999px;
            transition: width 0.2s ease, background-color 0.2s ease;
        }

        .tmb-actions-bar {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1.5px solid #e2e8f0;
            display: flex;
            flex-direction: column-reverse;
            gap: 8px;
        }
        @media (min-width: 640px) {
            .tmb-actions-bar {
                margin-top: 16px;
                padding-top: 14px;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                gap: 10px;
            }
        }

        .tmb-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 14px -6px rgba(29, 78, 216, 0.5);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .tmb-btn-primary:hover {
            transform: translateY(-1px);
        }

        .tmb-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 14px;
            border-radius: 10px;
            background-color: #ffffff;
            color: #334155;
            border: 1.5px solid #cbd5e1;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }
        .tmb-btn-secondary:hover {
            background-color: #f1f5f9;
            border-color: #94a3b8;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-2.5">
            <div class="flex items-center gap-2 min-w-0">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2 rounded-xl shadow-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-sm sm:text-lg leading-tight">
                            Tambah Akun Admin
                        </h2>
                        <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="inline-flex items-center text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full">
                            Inventaris TMB
                        </span>
                    </div>
                    <p style="color: #475569; font-weight: 600;" class="text-[10px] sm:text-[11px] leading-snug mt-0.5">
                        Buat akun admin operasional atau cadangan super admin internal
                    </p>
                </div>
            </div>

            <a href="{{ route('users.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-extrabold text-slate-700 hover:text-blue-700 bg-white border border-slate-300 px-3 py-1.5 rounded-xl transition">
                <span>&larr;</span>
                <span>Daftar Admin</span>
            </a>
        </div>
    </x-slot>

    <div class="px-2.5 sm:px-6 lg:px-8">
        <div class="tmb-admin-wrap">
            <div class="tmb-admin-shell">

                <!-- Banner Header -->
                <div class="tmb-admin-hero">
                    <div>
                        <div class="tmb-admin-tag">
                            <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                            <span>Manajemen Pengguna</span>
                        </div>
                        <h3 class="text-sm sm:text-base font-extrabold tracking-tight text-white">
                            Buat Akun Admin Baru
                        </h3>
                        <p class="text-[11px] sm:text-xs text-blue-100 mt-0.5 leading-snug">
                            Tambahkan admin operasional atau super admin cadangan internal.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 self-start sm:self-center px-2.5 py-1 rounded-full text-[10px] font-extrabold shrink-0" style="background-color: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25); color: #ffffff;">
                        <span style="background-color: #34d399;" class="w-1.5 h-1.5 rounded-full"></span>
                        <span>{{ $isSuperAdmin ? 'Akses: Super Admin' : 'Akses: Admin' }}</span>
                    </span>
                </div>

                <!-- Form -->
                <div class="tmb-admin-body">
                    <form action="{{ route('users.store') }}" method="POST" class="tmb-form-stack">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="tmb-field-card">
                            <label for="admin_name" class="tmb-field-label">
                                <span>Nama Lengkap</span>
                                <span class="text-[9.5px] font-bold text-blue-600 normal-case tracking-normal">Wajib</span>
                            </label>
                            <div class="tmb-input-group">
                                <span class="tmb-input-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </span>
                                <input id="admin_name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="tmb-input no-toggle @error('name') is-error @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required
                                       autofocus>
                            </div>
                            @error('name')
                                <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Login -->
                        <div class="tmb-field-card">
                            <label for="admin_email" class="tmb-field-label">
                                <span>Email Login</span>
                                <span class="text-[9.5px] font-bold text-slate-400 normal-case tracking-normal">Wajib</span>
                            </label>
                            <div class="tmb-input-group">
                                <span class="tmb-input-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </span>
                                <input id="admin_email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="tmb-input no-toggle @error('email') is-error @enderror"
                                       placeholder="Masukkan email login"
                                       required>
                            </div>
                            @error('email')
                                <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level Akun -->
                        @if($isSuperAdmin)
                            <div class="tmb-field-card">
                                <div class="tmb-field-label">
                                    <span>Level Akun</span>
                                    <span id="tmbSelectedRoleBadge" class="text-[9.5px] font-extrabold px-2 py-0.5 rounded-full {{ $selectedRole === 'super-admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-blue-100 text-blue-700' }}">
                                        Dipilih: {{ $selectedRole === 'super-admin' ? 'Super Admin' : 'Admin Operasional' }}
                                    </span>
                                </div>

                                <div class="tmb-role-grid">
                                    <label class="tmb-role-option {{ $selectedRole === 'admin' ? 'is-active-admin' : '' }}" data-role-card="admin">
                                        <input type="radio" name="role" value="admin" {{ $selectedRole === 'admin' ? 'checked' : '' }}>
                                        <div>
                                            <div class="tmb-role-title">Admin Operasional</div>
                                            <div class="tmb-role-desc">Kelola inventaris harian (tidak bisa kelola akun).</div>
                                        </div>
                                    </label>

                                    <label class="tmb-role-option {{ $selectedRole === 'super-admin' ? 'is-active-super' : '' }}" data-role-card="super-admin">
                                        <input type="radio" name="role" value="super-admin" {{ $selectedRole === 'super-admin' ? 'checked' : '' }}>
                                        <div>
                                            <div class="tmb-role-title">Super Admin</div>
                                            <div class="tmb-role-desc">Akses penuh termasuk tambah &amp; edit akun admin.</div>
                                        </div>
                                    </label>
                                </div>

                                @error('role')
                                    <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="role" value="admin">
                        @endif

                        <!-- Password -->
                        <div class="tmb-field-card">
                            <label for="admin_password" class="tmb-field-label">
                                <span>Password</span>
                                <span id="tmbPassStrengthText" class="text-[10px] font-extrabold text-slate-400 normal-case tracking-normal">Minimal 8 karakter</span>
                            </label>
                            <div class="tmb-input-group">
                                <span class="tmb-input-icon">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </span>
                                <input id="admin_password"
                                       type="password"
                                       name="password"
                                       class="tmb-input @error('password') is-error @enderror"
                                       placeholder="Masukkan minimal 8 karakter"
                                       required
                                       minlength="8">
                                <button type="button" id="tmbTogglePassword" class="tmb-toggle-pass-btn" aria-label="Tampilkan atau sembunyikan password" title="Tampilkan / Sembunyikan Password">
                                    <svg id="tmbEyeOpen" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="tmbEyeClosed" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="tmb-strength-track">
                                <div id="tmbPassStrengthBar" class="tmb-strength-bar"></div>
                            </div>

                            @error('password')
                                <p class="text-red-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="tmb-actions-bar">
                            <a href="{{ route('users.index') }}" class="tmb-btn-secondary">
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="tmb-btn-primary">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Buat Akun</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const passInput = document.getElementById("admin_password");
            const toggleBtn = document.getElementById("tmbTogglePassword");
            const eyeOpen = document.getElementById("tmbEyeOpen");
            const eyeClosed = document.getElementById("tmbEyeClosed");
            const strengthBar = document.getElementById("tmbPassStrengthBar");
            const strengthText = document.getElementById("tmbPassStrengthText");
            const roleBadge = document.getElementById("tmbSelectedRoleBadge");

            function updatePasswordStrength() {
                if (!passInput || !strengthBar || !strengthText) return;
                const val = passInput.value || "";

                if (val.length === 0) {
                    strengthBar.style.width = "0%";
                    strengthBar.style.backgroundColor = "#cbd5e1";
                    strengthText.textContent = "Minimal 8 karakter";
                    strengthText.style.color = "#94a3b8";
                    return;
                }

                let score = 0;
                if (val.length >= 8) score++;
                if (val.length >= 12) score++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;

                if (val.length < 8 || score <= 1) {
                    strengthBar.style.width = "33%";
                    strengthBar.style.backgroundColor = "#ef4444";
                    strengthText.textContent = val.length < 8 ? `Kurang ${8 - val.length} karakter lagi` : "Lemah";
                    strengthText.style.color = "#dc2626";
                } else if (score <= 2) {
                    strengthBar.style.width = "66%";
                    strengthBar.style.backgroundColor = "#f59e0b";
                    strengthText.textContent = "Cukup aman";
                    strengthText.style.color = "#d97706";
                } else {
                    strengthBar.style.width = "100%";
                    strengthBar.style.backgroundColor = "#10b981";
                    strengthText.textContent = "Kuat";
                    strengthText.style.color = "#059669";
                }
            }

            if (toggleBtn && passInput) {
                toggleBtn.addEventListener("click", function () {
                    const isPass = passInput.type === "password";
                    passInput.type = isPass ? "text" : "password";
                    eyeOpen?.classList.toggle("hidden", isPass);
                    eyeClosed?.classList.toggle("hidden", !isPass);
                });
            }

            const roleCards = document.querySelectorAll("[data-role-card]");
            roleCards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                radio?.addEventListener("change", function () {
                    roleCards.forEach(c => {
                        c.classList.remove("is-active-admin", "is-active-super");
                    });
                    if (radio.checked) {
                        const isSuper = radio.value === "super-admin";
                        card.classList.add(isSuper ? "is-active-super" : "is-active-admin");
                        if (roleBadge) {
                            roleBadge.textContent = isSuper ? "Dipilih: Super Admin" : "Dipilih: Admin Operasional";
                            roleBadge.className = isSuper
                                ? "text-[9.5px] font-extrabold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700"
                                : "text-[9.5px] font-extrabold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700";
                        }
                    }
                });
            });

            passInput?.addEventListener("input", updatePasswordStrength);
            updatePasswordStrength();
        });
    </script>
</x-app-layout>