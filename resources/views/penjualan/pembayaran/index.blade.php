@extends('layouts.app')

@section('title', 'Daftar Pembayaran Piutang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Daftar Pembayaran Piutang</h1>
        <p class="text-muted small mb-0">Kelola pembayaran piutang dari pelanggan</p>
    </div>
    <a href="{{ route('penjualan.pembayaran.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg me-2"></i> Tambah Pembayaran
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <!-- Search & Filter -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <form action="{{ route('penjualan.pembayaran.index') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No Bayar / Customer / Ref..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive rounded-3 border">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="py-3 px-3">No Pembayaran</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Metode</th>
                        <th class="py-3">Akun</th>
                        <th class="py-3 text-end">Total Bayar</th>
                        <th class="py-3 text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $bayar)
                    <tr>
                        <td class="px-3">
                            <div class="fw-bold text-primary">{{ $bayar->no_bayar }}</div>
                            @if($bayar->no_ref)
                                <div class="small text-muted"><i class="bi bi-hash me-1"></i>{{ $bayar->no_ref }}</div>
                            @endif
                        </td>
                        <td>{{ date('d/m/Y', strtotime($bayar->tanggal)) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial rounded-circle bg-success-subtle text-success me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: bold;">
                                    {{ substr($bayar->customer->nama ?? 'C', 0, 1) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $bayar->customer->nama }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">{{ $bayar->cara_bayar }}</span>
                        </td>
                        <td>{{ $bayar->akun->nama_akun ?? '-' }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($bayar->total_bayar, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $bayar->id }}" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/2936/2936756.png" alt="No Data" style="width: 64px; opacity: 0.5;" class="mb-3">
                            <p class="text-muted fw-bold mb-0">Belum ada data pembayaran</p>
                            <p class="text-muted small">Silahkan buat pembayaran baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $pembayarans->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
@foreach($pembayarans as $bayar)
<div class="modal fade" id="detailModal{{ $bayar->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
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
                                <td class="text-muted">Customer</td>
                                <td class="fw-bold text-dark">: {{ $bayar->customer->nama }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Dibayar</h6>
                        <h3 class="fw-bold text-success mb-0">Rp {{ number_format($bayar->total_bayar, 0, ',', '.') }}</h3>
                        <div class="badge bg-light text-dark border mt-2">
                            {{ $bayar->cara_bayar }} {{ $bayar->no_ref ? '('.$bayar->no_ref.')' : '' }}
                        </div>
                    </div>
                </div>

                <div class="table-responsive rounded-3 border">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No Invoice</th>
                                <th>Tanggal Inv</th>
                                <th class="text-end">Potongan</th>
                                <th class="text-end">Jumlah Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bayar->details as $detail)
                            <tr>
                                <td class="fw-bold">{{ $detail->penjualan->no_transaksi ?? '-' }}</td>
                                <td>{{ $detail->penjualan ? date('d/m/Y', strtotime($detail->penjualan->tanggal)) : '-' }}</td>
                                <td class="text-end text-danger">{{ $detail->potongan > 0 ? 'Rp '.number_format($detail->potongan, 0, ',', '.') : '-' }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($detail->jumlah_bayar, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($bayar->keterangan)
                <div class="mt-3">
                    <small class="text-muted fw-bold">Keterangan:</small>
                    <p class="small text-muted mb-0">{{ $bayar->keterangan }}</p>
                </div>
                @endif

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
