@extends('layouts.app')

@section('title', 'Tambah Kas Transfer')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Kas Transfer</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('akuntansi.kas_transfer.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card card-dashboard">
            <div class="card-header bg-light py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2"></i>Informasi Transaksi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('akuntansi.kas_transfer.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label small text-muted">Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label small text-muted">Jumlah (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" class="form-control" name="jumlah" min="1" placeholder="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="akun_kredit" class="form-label small text-muted">Dari Akun (Kredit)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-box-arrow-up"></i></span>
                                <select class="form-select" name="akun_kredit" required>
                                    <option value="">Pilih Akun Asal</option>
                                    @foreach($akun as $a)
                                        <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text small">Akun Kas/Bank asal yang uangnya berkurang.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="akun_debit" class="form-label small text-muted">Ke Akun (Debit)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-box-arrow-in-down"></i></span>
                                <select class="form-select" name="akun_debit" required>
                                    <option value="">Pilih Akun Tujuan</option>
                                    @foreach($akun as $a)
                                        <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text small">Akun Kas/Bank tujuan yang uangnya bertambah.</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="form-label small text-muted">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Contoh: Transfer antar bank untuk operasional..."></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-light me-2">Reset</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-dashboard h-100">
                    <div class="card-body bg-light rounded">
                        <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-info-circle me-2"></i>Panduan Kas Transfer</h6>
                        <p class="small text-muted mb-2">Fitur ini digunakan untuk mencatat pemindahan dana (mutasi) antar akun Kas atau Bank internal perusahaan.</p>
                        <ul class="small text-muted ps-3 mb-3">
                            <li class="mb-1">Setor tunai dari Kas ke Bank</li>
                            <li class="mb-1">Tarik tunai dari Bank ke Kas Kecil</li>
                            <li class="mb-1">Transfer antar rekening Bank</li>
                        </ul>
                        <div class="alert alert-info small mb-3 border-0 bg-white">
                            <i class="bi bi-lightbulb me-1"></i> <strong>Tips:</strong> Transaksi ini tidak mempengaruhi Laba/Rugi, hanya mengubah posisi Aset.
                        </div>
                        <div class="alert alert-warning small mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i> Pastikan akun Asal dan Tujuan <strong>berbeda</strong>.
                        </div>
                    </div>
                </div>
    </div>
</div>
@endsection
