@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan & Monitoring</h1>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-cart-x fs-1 text-primary mb-3"></i>
                <h5 class="card-title">Monitoring PO (Sparepart & Hose)</h5>
                <p class="card-text text-muted">Monitor pembelian khusus item kategori Sparepart dan Hose.</p>
                <a href="{{ route('pengaturan.monitoring_po') }}" class="btn btn-outline-primary stretched-link">Lihat Monitoring</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-graph-up-arrow fs-1 text-success mb-3"></i>
                <h5 class="card-title">Monitoring Transaksi (Sparepart & Hose)</h5>
                <p class="card-text text-muted">Monitor penjualan khusus item kategori Sparepart dan Hose.</p>
                <a href="{{ route('pengaturan.monitoring_transaksi') }}" class="btn btn-outline-success stretched-link">Lihat Monitoring</a>
            </div>
        </div>
    </div>
</div>
@endsection
