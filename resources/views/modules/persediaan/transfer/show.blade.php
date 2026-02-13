@extends('layouts.app')

@section('title', 'Detail Transfer Item')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Transfer Item</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('persediaan.transfer') }}" class="btn btn-sm btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('persediaan.printTransferPdf', $transfer->id) }}" target="_blank" class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf"></i> Cetak PDF
        </a>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150">No Transaksi</td>
                        <td>: <strong>{{ $transfer->no_transaksi }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ \Carbon\Carbon::parse($transfer->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Dari Gudang</td>
                        <td>: {{ $transfer->gudang_asal }}</td>
                    </tr>
                    <tr>
                        <td>Ke Gudang</td>
                        <td>: {{ $transfer->gudang_tujuan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150">No Surat Jalan</td>
                        <td>: {{ $transfer->no_sj ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>PIC</td>
                        <td>: {{ $transfer->pic ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $transfer->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Diinput Oleh</td>
                        <td>: {{ $transfer->user->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h5 class="mb-3">Detail Item</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Item</th>
                        <th>Nama Item</th>
                        <th width="150" class="text-center">Qty</th>
                        <th width="100">Satuan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfer->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->item->kode_item }}</td>
                        <td>{{ $detail->item->nama_item }}</td>
                        <td class="text-center">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                        <td>{{ $detail->satuan ?? $detail->item->satuan }}</td>
                        <td>{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
