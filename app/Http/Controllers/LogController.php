<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;

class LogController extends Controller
{
    public function index()
    {
        // Hanya Super Admin yang boleh melihat log
        if (auth()->user()->role !== 'super-admin') {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil data log beserta nama usernya, urutkan dari yang terbaru
        $logs = ActivityLog::with('user')->latest()->get();
        
        return view('logs.index', compact('logs'));
    }
}