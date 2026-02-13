<!DOCTYPE html>
<html>
<head>
    <title>Laporan Item Keluar</title>
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
        .text-center {
            text-align: center;
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

    <div class="title">LAPORAN TRANSAKSI ITEM KELUAR</div>

    <table class="info-table">
        <tr>
            <td class="label">No Transaksi</td>
            <td>: {{ $data->no_transaksi }}</td>
            <td class="label">Dibuat Oleh</td>
            <td>: {{ $data->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</td>
            <td class="label">Gudang Asal</td>
            <td>: {{ $data->gudang_asal }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 10px; font-weight: bold;">Detail Item:</div>
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 15%;">Kode Item</th>
                <th style="width: 40%;">Nama Item</th>
                <th style="width: 25%;">Keterangan</th>
                <th style="width: 15%; text-align: center;">Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->item->kode_item ?? '-' }}</td>
                <td>{{ $detail->item->nama_item ?? '-' }}</td>
                <td>{{ $detail->keterangan ?? '-' }}</td>
                <td class="text-center">{{ number_format($detail->qty, 2, ',', '.') }} {{ $detail->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right" style="font-weight: bold;">Total Qty</td>
                <td class="text-center" style="font-weight: bold;">{{ number_format($data->details->sum('qty'), 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 10px;">
        <strong>Keterangan:</strong><br>
        <div style="border: 1px solid #ccc; padding: 5px; min-height: 50px;">
            {{ $data->keterangan ?? '-' }}
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>