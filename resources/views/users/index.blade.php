<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Manajemen Akun Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                
                <div class="mb-6">
                    <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition shadow-sm">
                        + Tambah Admin Baru
                    </a>
                </div>

                @if(session('success'))
                    <div class="p-4 mb-6 text-sm text-green-700 bg-green-50 rounded-lg border border-green-100">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-6 text-sm text-red-700 bg-red-50 rounded-lg border border-red-100">{{ session('error') }}</div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">No</th>
                                <th class="px-6 py-4 font-semibold">Nama Admin</th>
                                <th class="px-6 py-4 font-semibold">Email Login</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun admin ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 font-medium rounded-lg hover:bg-red-100 transition">Hapus Akun</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada akun admin tambahan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>