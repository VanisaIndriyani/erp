<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 18pt;">CV. KARYA JAYA HOSEINDO</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 12pt;">Specialist Hose Hydraulic & Industrial</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN TRANSFER ITEM</th>
        </tr>
        <tr><td colspan="7"></td></tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000;">No Transaksi</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Tanggal</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Gudang Asal</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Gudang Tujuan</th>
            <th style="font-weight: bold; border: 1px solid #000000;">No SJ</th>
            <th style="font-weight: bold; border: 1px solid #000000;">Jumlah Item</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $item->no_transaksi }}</td>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $item->gudang_asal }}</td>
            <td style="border: 1px solid #000000;">{{ $item->gudang_tujuan }}</td>
            <td style="border: 1px solid #000000;">{{ $item->no_sj ?? '-' }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $item->details->count() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
