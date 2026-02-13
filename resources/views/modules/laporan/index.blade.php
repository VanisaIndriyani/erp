@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan</h1>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-cart-check fs-1 text-primary mb-3"></i>
                <h5 class="card-title">Laporan Pembelian</h5>
                <p class="card-text text-muted">Laporan transaksi pembelian per periode.</p>
                <a href="{{ route('laporan.pembelian') }}" class="btn btn-outline-primary stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-bag-check fs-1 text-success mb-3"></i>
                <h5 class="card-title">Laporan Penjualan</h5>
                <p class="card-text text-muted">Laporan transaksi penjualan per periode.</p>
                <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-success stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin fs-1 text-danger mb-3"></i>
                <h5 class="card-title">Hutang Beredar</h5>
                <p class="card-text text-muted">Daftar hutang yang belum lunas.</p>
                <a href="{{ route('laporan.hutang') }}" class="btn btn-outline-danger stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-wallet2 fs-1 text-info mb-3"></i>
                <h5 class="card-title">Piutang Beredar</h5>
                <p class="card-text text-muted">Daftar piutang yang belum dibayar customer.</p>
                <a href="{{ route('laporan.piutang') }}" class="btn btn-outline-info stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-boxes fs-1 text-warning mb-3"></i>
                <h5 class="card-title">Laporan Persediaan</h5>
                <p class="card-text text-muted">Stok barang saat ini.</p>
                <a href="{{ route('laporan.persediaan') }}" class="btn btn-outline-warning stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-book fs-1 text-secondary mb-3"></i>
                <h5 class="card-title">Buku Kas</h5>
                <p class="card-text text-muted">Mutasi Kas dan Bank.</p>
                <a href="{{ route('laporan.buku_kas') }}" class="btn btn-outline-secondary stretched-link">Lihat Laporan</a>
            </div>
        </div>
    </div>
</div>
@endsection
