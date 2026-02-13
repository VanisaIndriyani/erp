<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transfer Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header img {
            height: 60px;
            vertical-align: middle;
            margin-right: 15px;
        }
        .header-text {
            display: inline-block;
            vertical-align: middle;
            text-align: left;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 0;
            font-size: 9pt;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 120px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .content-table th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/Logo KJH New.png') }}" alt="Logo">
        <div class="header-text">
            <h2>CV. KARYA JAYA HOSEINDO</h2>
            <p>Inventory ERP System</p>
        </div>
    </div>

    <div class="title">LAPORAN TRANSFER ITEM ANTAR GUDANG</div>

    <table class="info-table">
        <tr>
            <td class="label">No Transaksi</td>
            <td>: {{ $transfer->no_transaksi }}</td>
            <td class="label">Dibuat Oleh</td>
            <td>: {{ $transfer->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($transfer->tanggal)->format('d F Y') }}</td>
            <td class="label">No SJ</td>
            <td>: {{ $transfer->no_sj ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Gudang Asal</td>
            <td>: {{ $transfer->gudang_asal }}</td>
            <td class="label">PIC</td>
            <td>: {{ $transfer->pic ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Gudang Tujuan</td>
            <td>: {{ $transfer->gudang_tujuan }}</td>
            <td class="label">Keterangan</td>
            <td>: {{ $transfer->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Satuan</th>
                <th>Keterangan</th>
                <th class="text-right">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transfer->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->item->kode_item }}</td>
                <td>{{ $detail->item->nama_item }}</td>
                <td>{{ $detail->satuan ?? $detail->item->satuan }}</td>
                <td>{{ $detail->keterangan ?? '-' }}</td>
                <td class="text-right">{{ number_format($detail->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
