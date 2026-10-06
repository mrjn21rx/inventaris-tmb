<?php
namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Enumerable; // <-- Tambahkan baris ini

class ItemExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    // Tambahkan ": Enumerable" di akhir nama fungsi
    public function collection(): Enumerable
    {
        return Item::with('category')->latest()->get();
    }

    public function map($item): array
    {
        return [
            ++$this->rowNumber,
            $item->name,
            $item->category->name ?? '-',
            $item->quantity,
            $item->condition,
            $item->location, 
            $item->description
        ];
    }

    public function headings(): array
    {
        return ['No', 'Nama Barang', 'Kategori', 'Jumlah', 'Kondisi', 'Lokasi', 'Keterangan'];
    }
}