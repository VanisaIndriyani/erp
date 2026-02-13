<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Transfer - {{ $transfer->no_transaksi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .info-table td {
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .items-table th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <!-- Buttons for screen only -->
        <div class="no-print mb-4">
            <button onclick="window.print()" class="btn btn-primary">Cetak</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        </div>

        <div class="header">
            <h2>Bukti Transfer Barang</h2>
            <p>Inventory ERP System</p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <table class="info-table">
                    <tr>
                        <td width="120"><strong>No Transaksi</strong></td>
                        <td>: {{ $transfer->no_transaksi }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>: {{ \Carbon\Carbon::parse($transfer->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>No Surat Jalan</strong></td>
                        <td>: {{ $transfer->no_sj ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="info-table">
                    <tr>
                        <td width="120"><strong>Dari Gudang</strong></td>
                        <td>: {{ $transfer->gudang_asal }}</td>
                    </tr>
                    <tr>
                        <td><strong>Ke Gudang</strong></td>
                        <td>: {{ $transfer->gudang_tujuan }}</td>
                    </tr>
                    <tr>
                        <td><strong>PIC</strong></td>
                        <td>: {{ $transfer->pic ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode Item</th>
                    <th width="35%">Nama Item</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Satuan</th>
                    <th width="25%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfer->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->item->kode_item }}</td>
                    <td>{{ $detail->item->nama_item }}</td>
                    <td class="text-center">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->satuan ?? $detail->item->satuan }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <div class="signature-line"></div>
                <p>{{ $transfer->user->name ?? 'Admin' }}</p>
            </div>
            <div class="signature-box">
                <p>Dikirim Oleh,</p>
                <div class="signature-line"></div>
                <p>Logistik / Gudang</p>
            </div>
            <div class="signature-box">
                <p>Diterima Oleh,</p>
                <div class="signature-line"></div>
                <p>{{ $transfer->pic ?? '...................' }}</p>
            </div>
        </div>
        
        <div class="mt-5 text-center text-muted" style="font-size: 10px;">
            Dicetak pada: {{ date('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
