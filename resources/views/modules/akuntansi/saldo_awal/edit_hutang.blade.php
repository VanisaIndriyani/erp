@extends('layouts.app')

@section('title', 'Edit Saldo Awal Hutang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Saldo Awal Hutang</h1>
    <a href="{{ route('akuntansi.saldo_awal.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card card-dashboard border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3 px-4">
        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Form Edit Saldo Hutang</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('akuntansi.saldo_awal.update_hutang', $jurnal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" name="tanggal" value="{{ $jurnal->tanggal }}" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label small text-muted">Supplier</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-truck"></i></span>
                        <select class="form-select" name="supplier_id" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ $pembelian->supplier_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->kode }} - {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-3 g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Akun Hutang</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-journal-arrow-down"></i></span>
                        <select class="form-select" name="akun_hutang" required>
                            <option value="">Pilih Akun Kewajiban</option>
                            @foreach($akun as $a)
                                <option value="{{ $a->id }}" {{ $detailHutang->akun_id == $a->id ? 'selected' : '' }}>
                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Akun Penyeimbang</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-journal-check"></i></span>
                        <select class="form-select" name="akun_penyeimbang" required>
                            <option value="">Pilih Akun (Biasanya Modal/Ekuitas)</option>
                            @foreach($akun as $a)
                                <option value="{{ $a->id }}" {{ $detailPenyeimbang->akun_id == $a->id ? 'selected' : '' }}>
                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small text-muted">Jumlah Hutang (Rp)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">Rp</span>
                    <input type="number" class="form-control" name="jumlah" min="1" value="{{ $detailHutang->kredit }}" required>
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
