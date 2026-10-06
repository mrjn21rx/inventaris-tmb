<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Halaman daftar admin
    public function index()
    {
        // Cek apakah yang login adalah super-admin
        if (auth()->user()->role !== 'super-admin') {
            abort(403, 'Akses Ditolak. Anda bukan Super Admin.');
        }

        // Ambil semua user yang rolenya 'admin'
        $users = User::where('role', 'admin')->latest()->get();
        return view('users.index', compact('users'));
    }

    // Halaman form tambah admin
    public function create()
    {
        if (auth()->user()->role !== 'super-admin') abort(403);
        
        return view('users.create');
    }

    // Proses simpan admin baru
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super-admin') abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password dienkripsi
            'role' => 'admin', // Otomatis diset sebagai admin
        ]);

        return redirect()->route('users.index')->with('success', 'Akun Admin berhasil ditambahkan!');
    }

    // Proses hapus akun admin
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'super-admin') abort(403);
        
        // Proteksi: Super admin tidak boleh menghapus dirinya sendiri/super admin lain
        if ($user->role === 'super-admin') {
            return back()->with('error', 'Akun Super Admin tidak bisa dihapus!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun Admin berhasil dihapus!');
    }
}