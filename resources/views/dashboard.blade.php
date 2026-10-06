<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Banner -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Selamat datang, {{ Auth::user()->name }}! </h3>
                    <p class="text-gray-500 mt-1">Berikut adalah ringkasan inventaris barang Anda hari ini.</p>
                </div>
                <div class="hidden md:block text-right">
                    <p class="text-sm text-gray-400 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <!-- Kartu Ringkasan dengan Gradasi Modern -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Barang -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-lg shadow-blue-200 p-6 text-white relative overflow-hidden transition hover:-translate-y-1">
                    <div class="relative z-10">
                        <h3 class="text-blue-100 text-sm font-bold uppercase tracking-wider mb-1">Total Barang</h3>
                        <p class="text-4xl font-extrabold">{{ $totalItems }}</p>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                
                <!-- Kondisi Baru -->
                <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg shadow-emerald-200 p-6 text-white relative overflow-hidden transition hover:-translate-y-1">
                    <div class="relative z-10">
                        <h3 class="text-emerald-100 text-sm font-bold uppercase tracking-wider mb-1">Kondisi Baru</h3>
                        <p class="text-4xl font-extrabold">{{ $newItems ?? 0 }}</p>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>

                <!-- Kondisi Baik -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl shadow-lg shadow-orange-200 p-6 text-white relative overflow-hidden transition hover:-translate-y-1">
                    <div class="relative z-10">
                        <h3 class="text-amber-100 text-sm font-bold uppercase tracking-wider mb-1">Kondisi Baik</h3>
                        <p class="text-4xl font-extrabold">{{ $goodItems }}</p>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>

                <!-- Kondisi Rusak / Rusak Berat -->
                <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl shadow-lg shadow-red-200 p-6 text-white relative overflow-hidden transition hover:-translate-y-1">
                    <div class="relative z-10">
                        <h3 class="text-rose-100 text-sm font-bold uppercase tracking-wider mb-1">Rusak</h3>
                        <p class="text-4xl font-extrabold">{{ $brokenItems }}</p>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>

            <!-- Area Grafik -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Statistik Kondisi Barang</h3>
                    <a href="{{ route('items.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">Lihat Detail &rarr;</a>
                </div>
                <div style="position: relative; height:40vh; width:100%">
                    <canvas id="inventoryChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js dengan Desain yang Disesuaikan -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('inventoryChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Baik', 'Rusak', 'Rusak Berat'],
                    datasets: [{
                        label: 'Jumlah Barang',
                        data: [
                            {{ $goodItems }}, 
                            {{ \App\Models\Item::where('condition', 'Rusak')->count() }}, 
                            {{ \App\Models\Item::where('condition', 'Rusak Berat')->count() }}
                        ],
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.8)', // Amber (Baik)
                            'rgba(244, 63, 94, 0.8)',  // Rose (Rusak)
                            'rgba(185, 28, 28, 0.8)'   // Dark Red (Rusak Berat)
                        ],
                        borderRadius: 8, // Ujung bar membulat
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false } // Menyembunyikan label legend di atas grafik agar lebih bersih
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#e5e7eb' }, // Garis putus-putus
                            ticks: { stepSize: 1, color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false }, // Menghilangkan garis vertikal
                            ticks: { color: '#4b5563', font: { weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>