<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-weight: bold; font-size: 14px;">
                REKAP BARANG/ALAT TEKNIK RRI BUKITTINGGI {{ date('Y') }}
            </th>
        </tr>
        <tr></tr> <!-- Baris kosong pemisah -->
    </thead>
    <tbody>
        @foreach($categories as $category)
            @if($category->items->count() > 0)
                <tr>
                    <td colspan="5" style="font-weight: bold; text-transform: uppercase;">
                        {{ $category->name }}
                    </td>
                </tr>
                <tr>
                    <th style="font-weight: bold; border: 1px solid #000;">NO</th>
                    <th style="font-weight: bold; border: 1px solid #000;">NAMA BARANG</th>
                    <th style="font-weight: bold; border: 1px solid #000;">JUMLAH</th>
                    <th style="font-weight: bold; border: 1px solid #000;">KONDISI</th>
                    <th style="font-weight: bold; border: 1px solid #000;">KETERANGAN</th>
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
                        <td style="border: 1px solid #000;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #000;">{{ $item->name }}</td>
                        <td style="border: 1px solid #000;">{{ $item->quantity }}</td>
                        <td style="border: 1px solid #000;">{{ $item->condition }}</td>
                        <td style="border: 1px solid #000;">{{ $item->description }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2" style="font-weight: bold; border: 1px solid #000;">TOTAL KESELURUHAN</td>
                    <td style="font-weight: bold; border: 1px solid #000;">{{ $totalJumlah }}</td>
                    <td colspan="2" style="font-weight: bold; border: 1px solid #000;">
                        @foreach($rekapKondisi as $namaKondisi => $jumlahKondisi)
                            {{ $namaKondisi }}: {{ $jumlahKondisi }} @if(!$loop->last) | @endif
                        @endforeach
                    </td>
                </tr>
                <tr></tr> <!-- Baris kosong antar kategori -->
            @endif
        @endforeach
    </tbody>
</table>