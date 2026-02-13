@extends('layouts.app')

@section('title', 'Buku Besar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buku Besar</h1>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card card-dashboard shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-filter me-2"></i>Filter Buku Besar</h6>
            </div>
            <div class="card-body bg-light">
                <form action="{{ route('akuntansi.buku_besar.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="akun_id" class="form-label small text-muted fw-bold">Pilih Akun</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-wallet2"></i></span>
                                <select class="form-select" name="akun_id" required>
                                    <option value="">-- Pilih Akun --</option>
                                    @foreach($akun as $a)
                                        <option value="{{ $a->id }}" {{ $selected_akun == $a->id ? 'selected' : '' }}>
                                            {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label small text-muted fw-bold">Dari Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control" name="start_date" value="{{ $start_date }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label small text-muted fw-bold">Sampai Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control" name="end_date" value="{{ $end_date }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($selected_akun)
    @php 
        $saldo = $saldo_awal; 
        $saldoNormal = $akun->find($selected_akun)->saldo_normal;
        $totalDebit = 0;
        $totalKredit = 0;
        
        // Pre-calculate totals for summary
        foreach($details as $d) {
            $totalDebit += $d->debit;
            $totalKredit += $d->kredit;
        }
        
        // Calculate final balance for summary
        if ($saldoNormal == 'debit') {
            $saldoAkhir = $saldo_awal + $totalDebit - $totalKredit;
        } else {
            $saldoAkhir = $saldo_awal + $totalKredit - $totalDebit;
        }
    @endphp

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                <div class="card-body py-3">
                    <small class="text-muted fw-bold text-uppercase">Saldo Awal</small>
                    <h5 class="mb-0 fw-bold mt-2 text-dark">{{ number_format($saldo_awal, 0, ',', '.') }}</h5>
                    <small class="text-muted" style="font-size: 0.75rem;">Per {{ \Carbon\Carbon::parse($start_date)->subDay()->format('d M Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body py-3">
                    <small class="text-muted fw-bold text-uppercase">Total Mutasi Debit</small>
                    <h5 class="mb-0 fw-bold mt-2 text-success">+ {{ number_format($totalDebit, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body py-3">
                    <small class="text-muted fw-bold text-uppercase">Total Mutasi Kredit</small>
                    <h5 class="mb-0 fw-bold mt-2 text-danger">- {{ number_format($totalKredit, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body py-3">
                    <small class="text-muted fw-bold text-uppercase">Saldo Akhir</small>
                    <h5 class="mb-0 fw-bold mt-2 text-primary">{{ number_format($saldoAkhir, 0, ',', '.') }}</h5>
                    <small class="text-muted" style="font-size: 0.75rem;">Per {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-dashboard border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="bi bi-list-columns me-2"></i>Rincian Transaksi: <span class="text-primary">{{ $akun->find($selected_akun)->kode_akun }} - {{ $akun->find($selected_akun)->nama_akun }}</span></h6>
            <span class="badge bg-secondary">{{ strtoupper($saldoNormal) }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 border-bottom-0">Tanggal</th>
                            <th class="py-3 border-bottom-0">No Ref</th>
                            <th class="py-3 border-bottom-0">Keterangan</th>
                            <th class="text-end py-3 border-bottom-0">Debit</th>
                            <th class="text-end py-3 border-bottom-0">Kredit</th>
                            <th class="text-end pe-4 py-3 border-bottom-0">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Saldo Awal Row -->
                        <tr class="bg-light-info">
                            <td class="ps-4 fw-bold text-muted" colspan="3">
                                <i class="bi bi-arrow-right-circle me-2"></i>Saldo Awal
                            </td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end text-muted">-</td>
                            <td class="text-end pe-4 fw-bold">{{ number_format($saldo_awal, 0, ',', '.') }}</td>
                        </tr>

                        @forelse($details as $d)
                            @php
                                if ($saldoNormal == 'debit') {
                                    $saldo += ($d->debit - $d->kredit);
                                } else {
                                    $saldo += ($d->kredit - $d->debit);
                                }
                            @endphp
                            <tr>
                                <td class="ps-4 text-nowrap">{{ \Carbon\Carbon::parse($d->jurnal->tanggal)->format('d/m/Y') }}</td>
                                <td class="small text-muted">
                                    <a href="#" class="text-decoration-none text-secondary">
                                        {{ $d->jurnal->no_ref }}
                                    </a>
                                </td>
                                <td>{{ $d->jurnal->keterangan }}</td>
                                <td class="text-end {{ $d->debit > 0 ? 'text-dark' : 'text-muted' }}">
                                    {{ $d->debit > 0 ? number_format($d->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end {{ $d->kredit > 0 ? 'text-dark' : 'text-muted' }}">
                                    {{ $d->kredit > 0 ? number_format($d->kredit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="text-end pe-4 fw-bold text-primary">{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada transaksi tambahan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light border-top">
                        <tr>
                            <td colspan="5" class="text-end fw-bold py-3">Saldo Akhir</td>
                            <td class="text-end fw-bold pe-4 py-3">{{ number_format($saldo, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection
