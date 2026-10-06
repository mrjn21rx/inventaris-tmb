<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Barang Baru</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('items.store') }}" method="POST" class="space-y-5">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
        <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
        <input type="number" name="quantity" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
        <select name="condition" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <option value="Baik">Baik</option>
            <option value="Rusak">Rusak</option>
            <option value="Rusak Berat">Rusak Berat</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
        <input type="text" name="location" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
    </div>

    <div class="pt-4 flex items-center space-x-3">
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Simpan Barang</button>
        <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">Batal</a>
    </div>
</form>

            </div>
        </div>
    </div>
</x-app-layout>