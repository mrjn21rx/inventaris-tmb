<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ItemExport;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index()
    {
        // Mengambil semua barang dari database beserta nama kategorinya
        $items = Item::with('category')->latest()->get();
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
        'condition' => 'required|in:Baik,Rusak,Rusak Berat',
        'location' => 'required|string', // <-- Tambahkan validasi lokasi
        'description' => 'nullable|string'
    ]);

    Item::create($request->all());
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
        'condition' => 'required|in:Baik,Rusak,Rusak Berat',
        'location' => 'required|string', // <-- Tambahkan validasi lokasi
        'description' => 'nullable|string'
    ]);

    $item->update($request->all());
    return redirect()->route('items.index')->with('success', 'Data barang berhasil diubah!');
}

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }
    public function exportPdf()
    {
        // Ambil semua data barang
        $items = Item::with('category')->latest()->get();

        // Load file view PDF dan kirim datanya
        $pdf = Pdf::loadView('items.pdf', compact('items'));

        // Unduh file PDF
        return $pdf->download('laporan-inventaris.pdf');
    }

    public function exportExcel()
    {
    return Excel::download(new ItemExport, 'Laporan_Inventaris.xlsx');
    }
}