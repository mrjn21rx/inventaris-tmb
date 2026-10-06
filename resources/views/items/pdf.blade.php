<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris TMB RRI Bukittinggi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <h2 class="text-center">Laporan Data Inventaris Barang TMB RRI</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>

        <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td>{{ $item->condition }}</td>
                <td>{{ $item->location }}</td>
                <td>{{ $item->description ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>