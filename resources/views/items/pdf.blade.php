<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris RRI Bukittinggi</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #1f2937; 
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        /* Kop Surat 3 Kolom untuk Keseimbangan Sempurna */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-col {
            width: 22%;
            text-align: left;
        }
        .logo-img {
            height: 50px;
            width: auto;
            display: block;
        }
        .title-col {
            width: 56%;
            text-align: center;
        }
        .spacer-col {
            width: 22%; /* Lebar sama dengan kolom logo agar teks di tengah simetris sempurna */
        }
        
        .title-col h3 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
            color: #1e1b4b;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .title-col h2 {
            margin: 4px 0;
            font-size: 14px;
            text-transform: uppercase;
            color: #0f172a;
            font-weight: 900;
            letter-spacing: 0.5px;
        }
        .title-col p {
            margin: 0;
            font-size: 10px;
            color: #4b5563;
        }

        /* Judul Kategori */
        .category-title { 
            font-weight: bold; 
            font-size: 11px;
            margin-top: 18px; 
            margin-bottom: 6px;
            text-transform: uppercase; 
            background-color: #f1f5f9; 
            color: #1e1b4b;
            padding: 5px 8px; 
            border-left: 4px solid #2563eb;
            border-radius: 4px;
        }

        /* Tabel Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 12px; 
            page-break-inside: avoid;
        }
        table, th, td { 
            border: 1px solid #cbd5e1; 
        }
        th, td { 
            padding: 5px 7px; 
            text-align: left; 
            vertical-align: middle;
        }
        th { 
            background-color: #0f172a; 
            color: #ffffff; 
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
            text-align: center;
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; font-weight: bold; }
        .footer-total { 
            font-weight: bold; 
            background-color: #f8fafc; 
            color: #0f172a;
        }
    </style>
</head>
<body>

    @php
        date_default_timezone_set('Asia/Jakarta');

        $bulanIndo = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $tglSekarang = date('d') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');
        $waktuSekarang = date('H:i') . ' WIB';

        $logoPath = public_path('images/logo_rri.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }
    @endphp

    <!-- Kop Surat 3 Kolom Presisi -->
    <table class="header-table">
        <tr>
            <td class="logo-col">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo RRI">
                @else
                    <span style="font-weight: bold; font-size: 10px; color: #dc2626;">[Logo Tidak Ditemukan]</span>
                @endif
            </td>
            <td class="title-col">
                <h3>LPP RRI BUKITTINGGI</h3>
                <h2>REKAPITULASI INVENTARIS ASET / BARANG TEKNIK</h2>
                <p>Tahun Anggaran {{ date('Y') }} &nbsp;|&nbsp; Dicetak pada: {{ $tglSekarang }}, {{ $waktuSekarang }}</p>
            </td>
            <td class="spacer-col"></td>
        </tr>
    </table>

    @foreach($categories as $category)
        @if($category->items->count() > 0)
            <div class="category-title">{{ $category->name }}</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">NO</th>
                        <th width="26%">NAMA BARANG</th>
                        <th width="14%">KODE</th>
                        <th width="8%" class="text-center">JML</th>
                        <th width="15%">KONDISI</th>
                        <th width="32%">KETERANGAN</th>
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
                            $kondisi = $item->condition;
                            if(!isset($rekapKondisi[$kondisi])) {
                                $rekapKondisi[$kondisi] = 0;
                            }
                            $rekapKondisi[$kondisi] += $item->quantity;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td class="text-center font-mono" style="font-size: 10px;">{{ $item->code ?? $item->item_code ?? '-' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>{{ $item->condition }}</td>
                            <td>{{ $item->description ?: '-' }}</td>
                        </tr>
                    @endforeach

                    <tr class="footer-total">
                        <td colspan="3" class="text-center">TOTAL KESELURUHAN KATEGORI</td>
                        <td class="text-center">{{ $totalJumlah }}</td>
                        <td colspan="2">
                            @foreach($rekapKondisi as $namaKondisi => $jumlahKondisi)
                                {{ $namaKondisi }}: {{ $jumlahKondisi }}@if(!$loop->last) | @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    @endforeach

</body>
</html>