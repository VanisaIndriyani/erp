<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 18pt;">CV. KARYA JAYA HOSEINDO</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 12pt;">Specialist Hose Hydraulic & Industrial</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN TRANSAKSI ITEM KELUAR</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">No Transaksi</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">Tanggal</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">Gudang Asal</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">Keterangan</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">Dibuat Oleh</th>
            <th style="font-weight: bold; border: 1px solid #000000; background-color: #f2f2f2;">Dibuat Pada</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td style="border: 1px solid #000000;">{{ $row->no_transaksi }}</td>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $row->gudang_asal }}</td>
            <td style="border: 1px solid #000000;">{{ $row->keterangan }}</td>
            <td style="border: 1px solid #000000;">{{ $row->user->name ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $row->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>