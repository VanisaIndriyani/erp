@extends('layouts.app')

@section('title', 'Detail Item Keluar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Item Keluar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.keluar') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('persediaan.printKeluarPdf', $data->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                <i class="bi bi-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>
</div>

<div class="card card-dashboard mb-3">
    <div class="card-body">
        <div class="row mb-2">
            <label class="col-sm-2 col-form-label fw-bold">No Transaksi</label>
            <div class="col-sm-4 col-form-label">: {{ $data->no_transaksi }}</div>
            
            <label class="col-sm-2 col-form-label fw-bold">Tanggal</label>
            <div class="col-sm-4 col-form-label">: {{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</div>
        </div>

        <div class="row mb-2">
            <label class="col-sm-2 col-form-label fw-bold">Gudang Asal</label>
            <div class="col-sm-4 col-form-label">: {{ $data->gudang_asal }}</div>

            <label class="col-sm-2 col-form-label fw-bold">Dibuat Oleh</label>
            <div class="col-sm-4 col-form-label">: {{ $data->user->name ?? '-' }}</div>
        </div>

        <div class="row mb-2">
            <label class="col-sm-2 col-form-label fw-bold">Keterangan</label>
            <div class="col-sm-10 col-form-label">: {{ $data->keterangan ?? '-' }}</div>
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
                        <th>Keterangan</th>
                        <th class="text-center" width="10%">Qty</th>
                        <th class="text-center" width="10%">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->item->kode_item }}</td>
                        <td>{{ $detail->item->nama_item }}</td>
                        <td>{{ $detail->keterangan ?? '-' }}</td>
                        <td class="text-center">{{ number_format($detail->qty, 2) }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total Quantity :</td>
                        <td class="text-center fw-bold">{{ number_format($data->details->sum('qty'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
