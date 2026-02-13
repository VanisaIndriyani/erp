@extends('layouts.app')

@section('title', 'Detail Item Masuk')

@section('content')
<style>
    @media print {
        body {
            background-color: white !important;
        }
        .navbar, .sidebar, .btn-toolbar, .btn, footer, .no-print {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            display: none !important;
        }
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .table th, .table td {
            color: #000 !important;
        }
    }
    .print-header {
        display: none;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom no-print">
    <h1 class="h2">Detail Item Masuk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.masuk') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('persediaan.printMasukPdf', $itemMasuk->id) }}" class="btn btn-sm btn-primary" target="_blank">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>
    </div>
</div>

<div class="print-header">
    <div class="d-flex align-items-center justify-content-center mb-2">
        <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="height: 80px; margin-right: 20px;">
        <div class="text-start">
            <h2 class="m-0 fw-bold text-uppercase" style="font-size: 24px;">CV. KARYA JAYA HOSEINDO</h2>
            <p class="mb-0" style="font-size: 14px;">Inventory ERP System</p>
            <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">Laporan Transaksi Item Masuk</p>
        </div>
    </div>
</div>

<div class="card card-dashboard mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="150" class="fw-bold">No Transaksi</td>
                        <td>: {{ $itemMasuk->no_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal</td>
                        <td>: {{ \Carbon\Carbon::parse($itemMasuk->tanggal)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Gudang Tujuan</td>
                        <td>: {{ $itemMasuk->gudang_tujuan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="150" class="fw-bold">Dibuat Oleh</td>
                        <td>: {{ $itemMasuk->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Akun</td>
                        <td>: {{ $itemMasuk->akun->nama_akun ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Nilai</td>
                        <td class="fw-bold text-primary">: Rp {{ number_format($itemMasuk->total_nilai, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-12">
                <strong>Keterangan:</strong>
                <p class="text-muted border p-2 rounded bg-light">{{ $itemMasuk->keterangan ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-header">
        Detail Item
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="15%">Kode Item</th>
                        <th>Nama Item</th>
                        <th class="text-center" width="10%">Qty</th>
                        <th class="text-center" width="10%">Satuan</th>
                        <th class="text-end" width="15%">Harga</th>
                        <th class="text-end" width="15%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemMasuk->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->item->kode_item }}</td>
                        <td>{{ $detail->item->nama_item }}</td>
                        <td class="text-center">{{ number_format($detail->qty, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-end">{{ number_format($detail->harga, 2, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($detail->total, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total :</td>
                        <td class="text-center fw-bold">{{ number_format($itemMasuk->details->sum('qty'), 2, ',', '.') }}</td>
                        <td></td>
                        <td></td>
                        <td class="text-end fw-bold">{{ number_format($itemMasuk->total_nilai, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
