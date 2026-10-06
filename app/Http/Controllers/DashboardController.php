<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung statistik barang
        $totalItems = Item::count();
        $newItems = Item::where('condition', 'Baru')->count();
        $goodItems = Item::where('condition', 'Baik')->count();
        $brokenItems = Item::where('condition', 'Rusak')->count();

        // Mengirim data ke halaman dashboard
        return view('dashboard', compact('totalItems', 'newItems', 'goodItems', 'brokenItems'));
    }
}