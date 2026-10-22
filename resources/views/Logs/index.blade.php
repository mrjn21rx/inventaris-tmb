<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Log Aktivitas Sistem</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Pengguna</th>
                                <th class="px-6 py-4 font-semibold">Aksi</th>
                                <th class="px-6 py-4 font-semibold">Detail Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $log->created_at->format('d M Y - H:i') }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $log->user->name ?? 'User Terhapus' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->action == 'Tambah Barang')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ $log->action }}</span>
                                    @elseif($log->action == 'Edit Barang')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $log->action }}</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $log->description }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas yang dicatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>