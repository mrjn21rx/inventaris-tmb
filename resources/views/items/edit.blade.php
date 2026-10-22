<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Barang</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                
                <!-- PERHATIKAN BAGIAN INI: Action mengarah ke update, dan ada @method('PUT') -->
                <form action="{{ route('items.update', $item->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <!-- value="{{ $item->name }}" digunakan untuk memanggil data lama ke dalam form -->
                        <input type="text" name="name" value="{{ $item->name }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="quantity" value="{{ $item->quantity }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                    <!-- Tambahkan value="{{ $item->condition }}" di sini -->
                    <input type="text" name="condition" value="{{ $item->condition }}" list="condition-options" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Pilih atau ketik (ex: PSU OK)">
                        
                        <datalist id="condition-options">
                            <option value="Baik">
                            <option value="Rusak">
                            <option value="Rusak Berat">
                            <option value="PSU OK">
                            <option value="PSU LEMAH">
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="location" value="{{ $item->location }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $item->description }}</textarea>
                    </div>

                    <div class="pt-4 flex items-center space-x-3">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Update Barang</button>
                        <a href="{{ route('items.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>