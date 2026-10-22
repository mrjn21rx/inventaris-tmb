<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris RRI</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .category-title { font-weight: bold; margin-top: 20px; text-transform: uppercase; background-color: #d1d5db; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .footer-total { font-weight: bold; background-color: #e5e7eb; }
    </style>
</head>
<body>

    <h2>REKAP BARANG/ALAT TEKNIK RRI BUKITTINGGI {{ date('Y') }}</h2>

    @foreach($categories as $category)
        <!-- Hanya tampilkan kategori jika ada barangnya -->
        @if($category->items->count() > 0)
            <div class="category-title">{{ $category->name }}</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%" class="text-center">NO</th>
                        <th width="30%">NAMA BARANG</th>
                        <th width="10%" class="text-center">JUMLAH</th>
                        <th width="15%">KONDISI</th>
                        <th width="40%">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalJumlah = 0; 
                        $rekapKondisi = []; 
                    @endphp

                    @foreach($category->items as $index => $item)
                        @php 
                            $totalJumlah += $item->quantity; 
                            // Menghitung rekap kondisi
                            $kondisi = $item->condition;
                            if(!isset($rekapKondisi[$kondisi])) {
                                $rekapKondisi[$kondisi] = 0;
                            }
                            $rekapKondisi[$kondisi] += $item->quantity;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>{{ $item->condition }}</td>
                            <td>{{ $item->description }}</td>
                        </tr>
                    @endforeach

                    <!-- Baris Total Keseluruhan -->
                    <tr class="footer-total">
                        <td colspan="2">TOTAL KESELURUHAN</td>
                        <td class="text-center">{{ $totalJumlah }}</td>
                        <td colspan="2">
                            @foreach($rekapKondisi as $namaKondisi => $jumlahKondisi)
                                {{ $namaKondisi }}: {{ $jumlahKondisi }} @if(!$loop->last) | @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    @endforeach

</body>
</html>