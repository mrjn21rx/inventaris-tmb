<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Halaman daftar admin (termasuk Super Administrator & Admin Operasional)
    public function index()
    {
        if (auth()->user()->role !== 'super-admin') {
            abort(403, 'Akses Ditolak. Anda bukan Super Admin.');
        }

        // Urutkan super-admin di paling atas, lalu akun admin lainnya
        $users = User::whereIn('role', ['super-admin', 'admin'])
            ->orderByRaw("CASE WHEN role = 'super-admin' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('users.index', compact('users'));
    }

    // Halaman form tambah admin
    public function create()
    {
        if (auth()->user()->role !== 'super-admin') {
            abort(403);
        }

        return view('users.create');
    }

    // Proses simpan admin baru (mendukung admin operasional & super-admin cadangan)
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super-admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8'],
            'role' => ['nullable', 'string', Rule::in(['admin', 'super-admin', 'superadmin', 'super_admin'])],
        ]);

        $rawRole = strtolower(trim((string) ($validated['role'] ?? 'admin')));
        $assignedRole = in_array($rawRole, ['super-admin', 'superadmin', 'super_admin'], true)
            ? 'super-admin'
            : 'admin';

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $assignedRole,
        ]);

        if (class_exists(ActivityLog::class) && Schema::hasTable('activity_logs')) {
            $roleLabel = $assignedRole === 'super-admin' ? 'Super Admin' : 'Admin Operasional';
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Tambah Akun',
                'description' => "Menambahkan akun {$roleLabel}: {$user->name} ({$user->email})",
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Akun berhasil ditambahkan!');
    }

    // Proses update data akun admin (dari modal Edit di index.blade.php)
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'super-admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'min:8'],
        ]);

        $data = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data akun berhasil diperbarui!');
    }

    // Proses hapus akun admin
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'super-admin') {
            abort(403);
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->role === 'super-admin') {
            $superAdminCount = User::where('role', 'super-admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Akun Super Admin terakhir tidak bisa dihapus!');
            }
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus!');
    }
}