<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Super Admin yang boleh melihat log
        if (auth()->user()->role !== 'super-admin') {
            abort(403, 'Akses Ditolak.');
        }

        $query = ActivityLog::with('user')->latest();

        // 1. Filter Jenis Aksi saja (jika dipilih saja, tampilkan semua log aksi tersebut)
        if ($request->filled('action')) {
            $action = trim((string) $request->input('action'));
            $actionLower = strtolower($action);

            $query->where(function ($q) use ($action, $actionLower) {
                $q->whereRaw('LOWER(action) LIKE ?', ['%' . $actionLower . '%']);

                if (str_contains($actionLower, 'tambah') || str_contains($actionLower, 'create')) {
                    $q->orWhereRaw('LOWER(action) LIKE ?', ['%tambah%'])
                      ->orWhereRaw('LOWER(action) LIKE ?', ['%create%']);
                } elseif (str_contains($actionLower, 'edit') || str_contains($actionLower, 'ubah') || str_contains($actionLower, 'update')) {
                    $q->orWhereRaw('LOWER(action) LIKE ?', ['%edit%'])
                      ->orWhereRaw('LOWER(action) LIKE ?', ['%ubah%'])
                      ->orWhereRaw('LOWER(action) LIKE ?', ['%update%']);
                } elseif (str_contains($actionLower, 'hapus') || str_contains($actionLower, 'delete')) {
                    $q->orWhereRaw('LOWER(action) LIKE ?', ['%hapus%'])
                      ->orWhereRaw('LOWER(action) LIKE ?', ['%delete%']);
                }
            });
        }

        // 2. Filter Kata Kunci (nama barang, kode, deskripsi, atau nama pengguna)
        if ($request->filled('search')) {
            $search = '%' . trim((string) $request->input('search')) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', $search)
                  ->orWhere('action', 'like', $search)
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', $search);
                  });
            });
        }

        // 3. Filter Dari Tanggal (hanya aktif jika diisi)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        // 4. Filter Sampai Tanggal (hanya aktif jika diisi)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Seluruh data tetap tersimpan di database untuk audit, tampil 50 baris/halaman
        $logs = $query->paginate(50)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}