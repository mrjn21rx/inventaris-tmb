<table>
    <thead>
        <!-- Baris Kop Surat dengan Logo di Kiri & Teks Sejajar Sempurna -->
        <tr>
            <td rowspan="4" style="text-align: center; vertical-align: middle; border: none; background-color: #ffffff;">
                <img src="{{ public_path('images/logo_rri.png') }}" height="42" alt="Logo RRI" style="display: block; margin: 0 auto;">
            </td>
            <th colspan="5" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 12px; background-color: #ffffff; color: #1e1b4b; border: none; padding: 2px;">
                LEMBAGA PENYIARAN PUBLIK RADIO REPUBLIK INDONESIA
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 13px; background-color: #ffffff; color: #0f172a; border: none; padding: 2px;">
                RRI BUKITTINGGI
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; vertical-align: middle; font-size: 8.5px; background-color: #ffffff; color: #4b5563; border: none; padding: 1px;">
                Jl. M. Yamin No. 199, Aur Kuning, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26131
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; vertical-align: middle; font-size: 8.5px; background-color: #ffffff; color: #4b5563; border: none; padding: 1px; border-bottom: 2px solid #0f172a; padding-bottom: 6px;">
                Telepon: (0752) 22583 | Unit Teknologi dan Media Baru (TMB)
            </th>
        </tr>
        <tr><td colspan="6" style="border: none; height: 10px;"></td></tr>

        <!-- Judul Laporan -->
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 12px; background-color: #0f172a; color: #ffffff; padding: 10px; border: 1px solid #000;">
                REKAPITULASI INVENTARIS ASET / BARANG TEKNIK TAHUN {{ date('Y') }}
            </th>
        </tr>
        <tr><td colspan="6" style="border: none; height: 6px;"></td></tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            @if($category->items->count() > 0)
                <!-- Judul Kategori -->
                <tr>
                    <td colspan="6" style="font-weight: bold; font-size: 11px; text-transform: uppercase; background-color: #f1f5f9; color: #1e1b4b; border: 1px solid #cbd5e1; padding: 6px;">
                        KATEGORI: {{ $category->name }}
                    </td>
                </tr>
                <!-- Header Tabel Item dengan Lebar Kolom NO yang Aman -->
                <tr>
                    <th style="font-weight: bold; text-align: center; background-color: #0f172a; color: #ffffff; border: 1px solid #000; width: 50px; padding: 6px;">NO</th>
                    <th style="font-weight: bold; text-align: left; background-color: #0f172a; color: #ffffff; border: 1px solid #000; padding: 6px;">NAMA BARANG</th>
                    <th style="font-weight: bold; text-align: center; background-color: #0f172a; color: #ffffff; border: 1px solid #000; padding: 6px;">KODE</th>
                    <th style="font-weight: bold; text-align: center; background-color: #0f172a; color: #ffffff; border: 1px solid #000; padding: 6px;">JML</th>
                    <th style="font-weight: bold; text-align: left; background-color: #0f172a; color: #ffffff; border: 1px solid #000; padding: 6px;">KONDISI</th>
                    <th style="font-weight: bold; text-align: left; background-color: #0f172a; color: #ffffff; border: 1px solid #000; padding: 6px;">KETERANGAN</th>
                </tr>
        
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
                        <td style="text-align: center; border: 1px solid #cbd5e1; vertical-align: middle; padding: 5px;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #cbd5e1; vertical-align: middle; padding: 5px;">{{ $item->name }}</td>
                        <td style="text-align: center; border: 1px solid #cbd5e1; font-family: monospace; vertical-align: middle; padding: 5px;">{{ $item->code ?? $item->item_code ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #cbd5e1; vertical-align: middle; padding: 5px;">{{ $item->quantity }}</td>
                        <td style="border: 1px solid #cbd5e1; vertical-align: middle; padding: 5px;">{{ $item->condition }}</td>
                        <td style="border: 1px solid #cbd5e1; vertical-align: middle; padding: 5px;">{{ $item->description ?: '-' }}</td>
                    </tr>
                @endforeach

                <!-- Baris Total Keseluruhan per Kategori -->
                <tr>
                    <td colspan="3" style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000; padding: 6px;">TOTAL KESELURUHAN KATEGORI</td>
                    <td style="font-weight: bold; text-align: center; background-color: #f8fafc; border: 1px solid #000; padding: 6px;">{{ $totalJumlah }}</td>
                    <td colspan="2" style="font-weight: bold; background-color: #f8fafc; border: 1px solid #000; padding: 6px;">
                        @foreach($rekapKondisi as $namaKondisi => $jumlahKondisi)
                            {{ $namaKondisi }}: {{ $jumlahKondisi }} @if(!$loop->last) | @endif
                        @endforeach
                    </td>
                </tr>
                <tr><td colspan="6" style="border: none; height: 12px;"></td></tr>
            @endif
        @endforeach
    </tbody>
</table>