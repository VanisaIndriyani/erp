@extends('layouts.app')

@section('title', 'Daftar Pembayaran Hutang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Daftar Pembayaran Hutang</h1>
        <p class="text-muted small mb-0">Kelola riwayat pembayaran hutang ke supplier</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('pembelian.pembayaran.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pembayaran
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title fw-bold mb-0">Riwayat Pembayaran</h5>
            <form action="{{ route('pembelian.pembayaran.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari no bayar/supplier..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th>No Transaksi</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Cara Bayar</th>
                        <th class="text-end">Total Bayar</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $bayar)
                    <tr>
                        <td class="fw-bold text-primary">{{ $bayar->no_bayar }}</td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ date('d/m/Y', strtotime($bayar->tanggal)) }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-success-subtle text-success me-2">
                                    {{ substr($bayar->supplier->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $bayar->supplier->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                {{ $bayar->cara_bayar }}
                            </span>
                            @if($bayar->no_referensi)
                                <small class="d-block text-muted mt-1">{{ $bayar->no_referensi }}</small>
                            @endif
                        </td>
                        <td class="text-end fw-bold">Rp {{ number_format($bayar->total_bayar, 0, ',', '.') }}</td>
                        <td>
                            @if($bayar->keterangan)
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> {{ Str::limit($bayar->keterangan, 30) }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="modal" data-bs-target="#detailModal{{ $bayar->id }}" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" alt="Empty" style="width: 64px; opacity: 0.5;">
                            <p class="text-muted mt-3 mb-0">Belum ada data pembayaran hutang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            {{ $pembayarans->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
@foreach($pembayarans as $bayar)
<div class="modal fade" id="detailModal{{ $bayar->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-6">
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Informasi Pembayaran</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="100">No Bayar</td>
                                <td class="fw-bold text-dark">: {{ $bayar->no_bayar }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal</td>
                                <td class="fw-bold text-dark">: {{ date('d/m/Y', strtotime($bayar->tanggal)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Supplier</td>
                                <td class="fw-bold text-dark">: {{ $bayar->supplier->nama }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Dibayar</h6>
                        <h3 class="fw-bold text-success mb-0">Rp {{ number_format($bayar->total_bayar, 0, ',', '.') }}</h3>
                        <div class="badge bg-light text-dark border mt-2">
                            {{ $bayar->cara_bayar }} {{ $bayar->no_referensi ? '('.$bayar->no_referensi.')' : '' }}
                        </div>
                    </div>
                </div>

                <div class="table-responsive rounded-3 border">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No Invoice</th>
                                <th>Tanggal Invoice</th>
                                <th class="text-end">Jumlah Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bayar->details as $detail)
                            <tr>
                                <td class="fw-bold">{{ $detail->pembelian->no_invoice ?? '-' }}</td>
                                <td>{{ $detail->pembelian ? date('d/m/Y', strtotime($detail->pembelian->tanggal)) : '-' }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($detail->jumlah_bayar, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 text-end">
                    <small class="text-muted fst-italic">Dibuat oleh: {{ $bayar->user->name ?? 'System' }}</small>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endpush
