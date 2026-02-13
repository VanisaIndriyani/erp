@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Penjualan: {{ $penjualan->no_transaksi }}</h1>
    <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Transaksi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">No Transaksi</td>
                        <td>: {{ $penjualan->no_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No PO</td>
                        <td>: {{ $penjualan->no_po ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal</td>
                        <td>: {{ $penjualan->tanggal }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Customer</td>
                        <td>: {{ $penjualan->customer->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Unit</td>
                        <td>: {{ $penjualan->unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Keterangan</td>
                        <td>: {{ $penjualan->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">Detail Barang</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->details as $detail)
                            <tr>
                                <td>{{ $detail->item->kode_item }} - {{ $detail->item->nama_item }}</td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
