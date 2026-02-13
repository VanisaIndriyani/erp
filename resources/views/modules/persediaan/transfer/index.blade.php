@extends('layouts.app')

@section('title', 'Daftar Item Transfer')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Transfer Item Antar Gudang</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('persediaan.exportTransfer', ['search' => request('search')]) }}" class="btn btn-sm btn-success me-2">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
        <a href="{{ route('persediaan.createTransfer') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Transfer
        </a>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('persediaan.transfer') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari No Transaksi / SJ..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Dari Gudang</th>
                        <th>Ke Gudang</th>
                        <th>No SJ</th>
                        <th>Jumlah Item</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                    <tr>
                        <td><strong>{{ $transfer->no_transaksi }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($transfer->tanggal)->format('d/m/Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $transfer->gudang_asal }}</span></td>
                        <td><span class="badge bg-primary">{{ $transfer->gudang_tujuan }}</span></td>
                        <td>{{ $transfer->no_sj ?? '-' }}</td>
                        <td>{{ $transfer->details->count() }} Item</td>
                        <td>{{ $transfer->user->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('persediaan.showTransfer', $transfer->id) }}" class="btn btn-xs btn-info" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('persediaan.printTransferPdf', $transfer->id) }}" target="_blank" class="btn btn-xs btn-danger" title="Cetak PDF"><i class="bi bi-file-pdf"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">Belum ada data transfer item.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $transfers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
