<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Kelola Kategori</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
        <!-- Form Tambah -->
        <div class="w-full md:w-1/3 bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
            <h3 class="font-bold text-gray-700 mb-4">Tambah Kategori</h3>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="text" name="name" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 mb-4" placeholder="Nama Kategori..." required>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            </form>
        </div>

        <!-- Tabel Kategori -->
        <div class="w-full md:w-2/3 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            @if(session('success')) <div class="text-green-600 mb-4">{{ session('success') }}</div> @endif
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr><th class="py-3 px-4">Nama Kategori</th><th class="py-3 px-4 text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr class="border-b">
                        <td class="py-3 px-4">{{ $cat->name }}</td>
                        <td class="py-3 px-4 text-center">
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="form-hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>