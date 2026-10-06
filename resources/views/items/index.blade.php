<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Manajemen Barang
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    
                    <!-- Header Action Buttons -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-3 md:space-y-0">
                        <a href="{{ route('items.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition shadow-sm">
                            + Tambah Barang Baru
                        </a>
                        
                        <div class="flex space-x-2">
                            <!-- Tombol Excel akan ditambahkan di sini nanti -->
                             <a href="{{ route('items.excel') }}" class="px-5 py-2.5 bg-green-50 text-green-600 font-medium text-sm rounded-lg border border-green-100 hover:bg-green-100 transition">
                                Export Excel
                            </a>
                            <a href="{{ route('items.pdf') }}" class="px-5 py-2.5 bg-red-50 text-red-600 font-medium text-sm rounded-lg border border-red-100 hover:bg-red-100 transition">
                                Export PDF
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="p-4 mb-6 text-sm text-green-700 bg-green-50 rounded-lg border border-green-100">{{ session('success') }}</div>
                    @endif

                    <!-- Modern Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">No</th>
                                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                    <th class="px-6 py-4 font-semibold">Kategori</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                                    <th class="px-6 py-4 font-semibold text-center">Kondisi</th>
                                    <th class="px-6 py-4 font-semibold">Lokasi</th> 
                                    <th class="px-6 py-4 font-semibold">Keterangan</th>
                                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->name }}</td>
                                    <td class="px-6 py-4">{{ $item->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 rounded-full font-medium">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->condition == 'Baik')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Baik</span>
                                        @elseif($item->condition == 'Rusak')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Rusak</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Rusak Berat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $item->location }}</td>
                                    <td class="px-6 py-4 text-gray-500 truncate max-w-xs">{{ $item->description ?: '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('items.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>