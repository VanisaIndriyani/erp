@extends('layouts.app')

@section('title', 'Kas Masuk')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kas Masuk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('akuntansi.kas_masuk.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kas Masuk
        </a>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <!-- Search & Filter -->
        <form action="{{ route('akuntansi.kas_masuk.index') }}" method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                     <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-calendar-range"></i></span>
                        <input type="date" name="start_date" class="form-control border-start-0 ps-0" value="{{ request('start_date') }}" title="Tanggal Mulai">
                        <span class="input-group-text bg-white border-start-0 border-end-0">-</span>
                        <input type="date" name="end_date" class="form-control border-start-0 ps-0" value="{{ request('end_date') }}" title="Tanggal Akhir">
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari No. Ref / Keterangan..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
                <div class="col-md-2">
                     <a href="{{ route('akuntansi.kas_masuk.index') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th width="12%">Tanggal</th>
                        <th width="15%">No. Ref</th>
                        <th>Keterangan</th>
                        <th width="20%">Masuk Ke (Debit)</th>
                        <th width="20%">Sumber (Kredit)</th>
                        <th width="15%" class="text-end">Jumlah</th>
                        <th width="5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnal as $j)
                    @php
                        $debit = $j->details->where('debit', '>', 0)->first();
                        $kredit = $j->details->where('kredit', '>', 0)->first();
                    @endphp
                    <tr>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/Y') }}</td>
                        <td class="align-middle fw-bold text-primary">{{ $j->no_ref }}</td>
                        <td class="align-middle text-muted small">{{ $j->keterangan }}</td>
                        <td class="align-middle">
                            @if($debit)
                                <span class="badge bg-light text-dark border fw-normal">
                                    {{ $debit->akun->kode_akun }}
                                </span>
                                <span class="small ms-1">{{ $debit->akun->nama_akun }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($kredit)
                                <span class="badge bg-light text-dark border fw-normal">
                                    {{ $kredit->akun->kode_akun }}
                                </span>
                                <span class="small ms-1">{{ $kredit->akun->nama_akun }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle text-end fw-bold">
                            Rp {{ number_format($debit ? $debit->debit : 0, 0, ',', '.') }}
                        </td>
                        <td class="align-middle text-center">
                            <form action="{{ route('akuntansi.kas_masuk.destroy', $j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon text-danger p-0" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-inbox display-4 text-secondary opacity-50"></i></div>
                            <h6 class="fw-bold">Belum ada data kas masuk</h6>
                            <p class="small mb-0">Silakan tambahkan transaksi kas masuk baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-3">
            {{ $jurnal->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
