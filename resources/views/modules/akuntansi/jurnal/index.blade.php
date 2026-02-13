@extends('layouts.app')

@section('title', 'Jurnal Umum')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Jurnal Umum</h1>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <!-- Search & Filter -->
        <form action="{{ route('akuntansi.jurnal.index') }}" method="GET" class="mb-3">
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
                     <a href="{{ route('akuntansi.jurnal.index') }}" class="btn btn-sm btn-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th width="15%" class="ps-3">Tanggal</th>
                        <th width="20%">No. Bukti / Referensi</th>
                        <th>Keterangan / Akun</th>
                        <th width="12%" class="text-end">Debit</th>
                        <th width="12%" class="text-end pe-3">Kredit</th>
                    </tr>
                </thead>
                @forelse ($jurnal as $item)
                    <tbody class="border-bottom">
                        @foreach ($item->details as $index => $detail)
                            <tr>
                                @if($index === 0)
                                    <td rowspan="{{ $item->details->count() }}" class="ps-3 align-top py-3 bg-white border-end-0">
                                        <div class="fw-bold text-dark mb-1">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                                        <span class="badge bg-light text-secondary border rounded-pill" style="font-size: 0.65rem; font-weight: normal;">
                                            {{ str_replace('_', ' ', strtoupper($item->tipe)) }}
                                        </span>
                                    </td>
                                    <td rowspan="{{ $item->details->count() }}" class="align-top py-3 bg-white border-end-0">
                                        <div class="fw-bold text-primary">{{ $item->no_ref }}</div>
                                        <div class="small text-muted mt-1 fst-italic">{{ $item->keterangan }}</div>
                                    </td>
                                @endif

                                <td class="py-1 border-0 position-relative">
                                    <div class="d-flex align-items-center {{ $detail->kredit > 0 ? 'ps-4' : '' }}">
                                        <span class="fw-bold text-dark me-2 small bg-light px-1 rounded border">{{ $detail->akun->kode_akun }}</span>
                                        <span>{{ $detail->akun->nama_akun }}</span>
                                    </div>
                                </td>
                                <td class="text-end py-1 border-0 font-monospace">
                                    @if($detail->debit > 0)
                                        <span class="text-dark">{{ number_format($detail->debit, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="text-end py-1 pe-3 border-0 font-monospace">
                                    @if($detail->kredit > 0)
                                        <span class="text-dark">{{ number_format($detail->kredit, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @empty
                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="bi bi-journal-x display-4 text-secondary opacity-50"></i></div>
                                <h6 class="fw-bold">Belum ada data jurnal</h6>
                                <p class="small mb-0">Silakan lakukan transaksi terlebih dahulu.</p>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>

        <div class="mt-4 px-3">
            {{ $jurnal->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
