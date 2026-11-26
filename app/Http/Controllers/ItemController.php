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

        // 1. Filter Pencarian (Mendukung nama, kode, dan deskripsi)
        if ($request->filled('search')) {
            $search =$request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '\%' .$search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id',$request->category_id);
        }

        // 3. Filter Kondisi
        if ($request->filled('condition')) {
            $condition = trim($request->condition);
            if ($condition === 'rusak-berats') {$query->whereIn('condition', ['Rusak', 'Rusak Berat']);
            } else {
                $query->where('condition',$condition);
            }
        }

        $items =$query->get();
        return view('items.index', compact('items'));
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $selectedCondition =$request->get('condition', '');

        return view('items.create', compact('categories', 'selectedCondition'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'category_id' => 'required',
            'quantity' => 'required|numeric',
            'condition' => 'required|string|max:255',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Item::create($request->all());

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Tambah Barang',
            'description' => 'Menambahkan barang baru: ' . $request->name . ' (Kode: ' . ($request->code ?? '-') . ')',
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item$item)
    {
        $validated =$request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'category_id' => 'required',
            'quantity' => 'required|numeric',
            'condition' => 'required|string|max:255',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $categories = Category::pluck('name', 'id');

        $oldValues = [
            'Nama Barang' => (string) ($item->name ?? '-'),
            'Kode Barang' => (string) ($item->code ?? '-'),
            'Kategori'    => (string) ($categories[$item->category_id] ?? ($item->category->name ?? $item->category_id ?? '-')),
            'Jumlah'      => (string) ($item->quantity ?? '0'),
            'Kondisi'     => (string) ($item->condition ?? '-'),
            'Lokasi'      => (string) ($item->location ?? '-'),
            'Keterangan'  => (string) (($item->description ?? '') !== '' ? $item->description : '-'),
        ];

        $item->update($validated);$item->refresh();

        $newValues = [
            'Nama Barang' => (string) ($item->name ?? '-'),
            'Kode Barang' => (string) ($item->code ?? '-'),
            'Kategori'    => (string) ($categories[$item->category_id] ?? ($item->category->name ?? $item->category_id ?? '-')),
            'Jumlah'      => (string) ($item->quantity ?? '0'),
            'Kondisi'     => (string) ($item->condition ?? '-'),
            'Lokasi'      => (string) ($item->location ?? '-'),
            'Keterangan'  => (string) (($item->description ?? '') !== '' ? $item->description : '-'),
        ];

        $diffParts = [];
        foreach ($newValues as$label => $newVal) {$oldVal = $oldValues[$label] ?? '-';
            if ($oldVal !==$newVal) {
                $diffParts[] = "{$label}: {$oldVal} -> {$newVal}";
            }
        }

        $logDescription = 'Memperbarui data barang: ' .$item->name;
        if (!empty($diffParts)) {
            $logDescription .= ' [PERUBAHAN] ' . implode(' [PERUBAHAN] ', $diffParts);
        } else {
            $logDescription .= ' [PERUBAHAN] (tidak ada perubahan nilai)';
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Edit Barang',
            'description' => $logDescription,
        ]);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diubah!');
    }

    public function destroy(Item $item)
    {
        $categories = Category::pluck('name', 'id');

        $itemName = (string) ($item->name ?? '-');
        $itemCode = (string) (($item->code ?? '') !== '' ? $item->code : '-');$categoryName = (string) ($categories[$item->category_id] ?? ($item->category->name ?? $item->category_id ?? '-'));
        $quantity = (string) ($item->quantity ?? '0');
        $condition = (string) (($item->condition ?? '') !== '' ? $item->condition : '-');$location = (string) (($item->location ?? '') !== '' ? $item->location : '-');
        $note = (string) (($item->description ?? '') !== '' ? $item->description : '-');

        $detailParts = [
            "Nama Barang: {$itemName}",
            "Kode Barang: {$itemCode}",
            "Kategori: {$categoryName}",
            "Jumlah: {$quantity}",
            "Kondisi: {$condition}",
            "Lokasi: {$location}",
            "Keterangan: {$note}",
        ];

        $item->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Hapus Barang',
            'description' => 'Menghapus barang: ' . $itemName . ' \vert{} ' . implode(' \vert{} ', $detailParts),
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function exportPdf()
    {
        $categories = Category::with('items')->get();$pdf = Pdf::loadView('items.pdf', compact('categories'));
        return $pdf->download('Laporan_Inventaris_RRI.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ItemExport, 'Laporan_Inventaris.xlsx');
    }
}