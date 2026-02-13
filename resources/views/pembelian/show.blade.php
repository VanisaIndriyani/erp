@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Pembelian: {{ $pembelian->no_invoice }}</h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Faktur</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold">No Invoice</td>
                        <td>: {{ $pembelian->no_invoice }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">No PO</td>
                        <td>: {{ $pembelian->no_po ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal</td>
                        <td>: {{ $pembelian->tanggal }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Supplier</td>
                        <td>: {{ $pembelian->supplier->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Keterangan</td>
                        <td>: {{ $pembelian->keterangan ?? '-' }}</td>
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
                            @php $subtotal = 0; @endphp
                            @foreach($pembelian->details as $detail)
                            @php $subtotal += $detail->subtotal; @endphp
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
                                <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                <td class="text-end fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">PPN</td>
                                <td class="text-end">Rp {{ number_format($pembelian->ppn, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total</td>
                                <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
