<x-app-layout>
    @php
        $currentUser = auth()->user();
        $roleSlug = strtolower(trim((string) ($currentUser->role ?? 'admin')));
        $isSuperAdmin = in_array($roleSlug, ['super-admin', 'superadmin', 'super_admin'], true);
        $roleLabel = $isSuperAdmin ? 'Super Admin' : 'Admin Operasional';
        $superAdminTotal = \App\Models\User::whereIn('role', ['super-admin', 'superadmin', 'super_admin'])->count();
        $canDeleteOwnAccount = !($isSuperAdmin && $superAdminTotal <= 1);
    @endphp

    <style>
        .tmb-prof-wrap {
            width: 100%;
            max-width: 44rem;
            margin: 0 auto;
            padding: 10px 0 calc(22px + env(safe-area-inset-bottom, 0px)) 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        @media (min-width: 640px) {
            .tmb-prof-wrap {
                padding: 14px 0 24px 0;
                gap: 12px;
            }
        }

        .tmb-prof-hero {
            background: linear-gradient(125deg, #0b1324 0%, #0f172a 55%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e3a8a;
            border-radius: 18px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: 0 10px 24px -16px rgba(15, 23, 42, 0.25);
        }
        @media (min-width: 640px) {
            .tmb-prof-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                border-radius: 20px;
                padding: 15px 20px;
                gap: 12px;
            }
        }

        .tmb-prof-tag {
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

        .tmb-prof-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 13px 14px;
            box-shadow: 0 8px 20px -16px rgba(15, 23, 42, 0.14);
        }
        @media (min-width: 640px) {
            .tmb-prof-card {
                border-radius: 18px;
                padding: 16px 20px;
            }
        }

        .tmb-prof-card.is-danger {
            border-color: #fecdd3;
            background: linear-gradient(180deg, #fff8f8 0%, #ffffff 100%);
        }

        .tmb-prof-label {
            display: block;
            font-size: 0.66rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #334155;
            margin-bottom: 5px;
        }

        .tmb-prof-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .tmb-prof-input {
            width: 100%;
            padding: 8px 12px;
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
            .tmb-prof-input {
                font-size: 0.82rem;
            }
        }
        .tmb-prof-input.has-toggle {
            padding-right: 36px;
        }
        .tmb-prof-input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.13);
        }

        .tmb-prof-eye-btn {
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
        }
        .tmb-prof-eye-btn:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .tmb-prof-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 15px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: 1.5px solid #1e40af;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 6px 14px -6px rgba(29, 78, 216, 0.45);
            transition: transform 0.15s ease;
        }
        .tmb-prof-btn-primary:hover {
            transform: translateY(-1px);
        }

        .tmb-prof-btn-dark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 15px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border: 1.5px solid #0f172a;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.15s ease;
        }
        .tmb-prof-btn-dark:hover {
            transform: translateY(-1px);
        }

        .tmb-prof-btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 15px;
            border-radius: 10px;
            background-color: #dc2626;
            color: #ffffff;
            border: 1.5px solid #b91c1c;
            font-size: 0.8rem;
            font-weight: 800;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .tmb-prof-btn-danger:hover {
            background-color: #b91c1c;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-2.5">
            <div class="flex items-center gap-2 min-w-0">
                <div style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); color: #ffffff; border: 1.5px solid #1e3a8a;" class="p-2 rounded-xl shadow-sm flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h2 style="color: #090d16; font-weight: 900; letter-spacing: -0.025em;" class="text-sm sm:text-lg leading-tight">
                        Pengaturan Profil
                    </h2>
                    <p style="color: #475569; font-weight: 600;" class="text-[10px] sm:text-[11px] leading-snug mt-0.5">
                        Kelola informasi akun dan keamanan kata sandi Anda
                    </p>
                </div>
            </div>

            <span style="background-color: #eff6ff; color: #1d4ed8; border: 1.5px solid #93c5fd;" class="hidden sm:inline-flex items-center text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full shrink-0">
                {{ $roleLabel }}
            </span>
        </div>
    </x-slot>

    <div class="px-2.5 sm:px-6 lg:px-8">
        <div class="tmb-prof-wrap">

            <!-- Ringkasan Akun -->
            <div class="tmb-prof-hero">
                <div class="min-w-0">
                    <div class="tmb-prof-tag">
                        <span style="background-color: #60a5fa;" class="w-1.5 h-1.5 rounded-full"></span>
                        <span>Akun Aktif</span>
                    </div>
                    <h3 class="text-sm sm:text-base font-extrabold text-white truncate">
                        {{ $currentUser->name }}
                    </h3>
                    <p class="text-[11px] sm:text-xs text-blue-100 truncate">
                        {{ $currentUser->email }}
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 self-start sm:self-center px-2.5 py-1 rounded-full text-[10px] font-extrabold shrink-0" style="background-color: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.25); color: #ffffff;">
                    <span style="background-color: #34d399;" class="w-1.5 h-1.5 rounded-full"></span>
                    <span>{{ $roleLabel }}</span>
                </span>
            </div>

            <!-- 1. Informasi Profil -->
            <div class="tmb-prof-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- 2. Perbarui Kata Sandi -->
            <div class="tmb-prof-card">
                @include('profile.partials.update-password-form')
            </div>

            <!-- 3. Hapus Akun (Dikunci jika satu-satunya Super Admin) -->
            @if($canDeleteOwnAccount)
                <div class="tmb-prof-card is-danger">
                    @include('profile.partials.delete-user-form')
                </div>
            @endif

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("[data-toggle-prof-pass]").forEach(btn => {
                btn.addEventListener("click", function () {
                    const targetId = btn.getAttribute("data-toggle-prof-pass");
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    input.type = input.type === "password" ? "text" : "password";
                });
            });
        });
    </script>
</x-app-layout>