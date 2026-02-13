@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Daftar Transaksi Penjualan</h1>
        <p class="text-muted small mb-0">Kelola data penjualan barang</p>
    </div>
    <a href="{{ route('penjualan.transaksi.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-2"></i> Tambah Penjualan
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <!-- Search & Filter -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <form action="{{ route('penjualan.transaksi.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No Transaksi / Customer..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive rounded-3 border">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="py-3 px-3">No Transaksi</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Keterangan</th>
                        <th class="py-3 text-end">Total</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $penjualan)
                    <tr>
                        <td class="px-3 fw-bold text-primary">{{ $penjualan->no_transaksi }}</td>
                        <td>{{ date('d/m/Y', strtotime($penjualan->tanggal)) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial rounded-circle bg-primary-subtle text-primary me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                    {{ substr($penjualan->customer->nama ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $penjualan->customer->nama ?? 'Umum' }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $penjualan->customer->kode ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ Str::limit($penjualan->keterangan ?? '-', 30) }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($penjualan->status_pembayaran == 'lunas')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Lunas</span>
                            @elseif($penjualan->status_pembayaran == 'partial')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3">Sebagian</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('penjualan.transaksi.show', $penjualan->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <!-- <a href="#" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a> -->
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="No Data" style="width: 64px; opacity: 0.5;" class="mb-3">
                            <p class="text-muted fw-bold mb-0">Belum ada data penjualan</p>
                            <p class="text-muted small">Silahkan tambahkan transaksi penjualan baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $penjualans->links() }}
        </div>
    </div>
</div>
@endsection
