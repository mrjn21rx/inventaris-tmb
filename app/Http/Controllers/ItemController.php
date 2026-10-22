<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ItemExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ActivityLog;

class ItemController extends Controller
{
   public function index(Request $request)
    {
        $query = Item::with('category')->latest();

        // 1. Filter Pencarian (dibungkus dalam function agar tidak bentrok dengan filter lain)
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Filter Kondisi
        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        $items = $query->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        // Mengambil semua kategori untuk ditampilkan di pilihan form
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'category_id' => 'required',
        'quantity' => 'required|numeric',
        'condition' => 'required|string|max:255',
        'location' => 'required|string', // <-- Tambahkan validasi lokasi
        'description' => 'nullable|string'
    ]);

    Item::create($request->all());
        ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'Tambah Barang',
    'description' => 'Menambahkan barang baru: ' . $request->name
    ]);
    return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan!');

}
    
    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

public function update(Request $request, Item $item)
{
    $request->validate([
        'name' => 'required',
        'category_id' => 'required',
        'quantity' => 'required|numeric',
        'condition' => 'required|string|max:255',
        'location' => 'required|string', // <-- Tambahkan validasi lokasi
        'description' => 'nullable|string'
    ]);

    $item->update($request->all());
        ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'Edit Barang',
    'description' => 'Memperbarui data barang: ' . $item->name
    ]);
    return redirect()->route('items.index')->with('success', 'Data barang berhasil diubah!');

}

    public function destroy(Item $item)
    {
        $item->delete();
        ActivityLog::create([
    'user_id' => auth()->id(),
    'action' => 'Hapus Barang',
    'description' => 'Menghapus barang: ' . $item->name
    ]);
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function exportPdf()
    {
        // Mengambil data berdasarkan Kategori
        $categories = \App\Models\Category::with('items')->get();
        
        $pdf = Pdf::loadView('items.pdf', compact('categories'));
        return $pdf->download('Laporan_Inventaris_RRI.pdf');
    }

    public function exportExcel()
    {
    return Excel::download(new ItemExport, 'Laporan_Inventaris.xlsx');
    }
}