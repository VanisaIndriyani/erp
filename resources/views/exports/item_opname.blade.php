<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-weight: bold; font-size: 18pt;">CV. KARYA JAYA HOSEINDO</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 12pt;">Specialist Hose Hydraulic & Industrial</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN STOK OPNAME</th>
        </tr>
        <tr>
            <th colspan="9"></th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Tanggal</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Gudang</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Item</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Stok Sistem</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Stok Fisik</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Selisih</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">Keterangan</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #cccccc; text-align: center;">User</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $item->gudang }}</td>
            <td style="border: 1px solid #000000;">{{ $item->item->kode_item ?? '-' }} - {{ $item->item->nama_item ?? '-' }}</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $item->stok_sistem }}</td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $item->stok_fisik }}</td>
            <td style="border: 1px solid #000000; text-align: right; color: {{ $item->selisih < 0 ? '#FF0000' : ($item->selisih > 0 ? '#008000' : '#000000') }};">{{ $item->selisih }}</td>
            <td style="border: 1px solid #000000;">{{ $item->keterangan }}</td>
            <td style="border: 1px solid #000000;">{{ $item->user->name ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>