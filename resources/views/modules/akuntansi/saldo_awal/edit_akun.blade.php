@extends('layouts.app')

@section('title', 'Edit Saldo Awal Akun')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Saldo Awal Akun</h1>
    <a href="{{ route('akuntansi.saldo_awal.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card card-dashboard border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3 px-4">
        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Form Edit Saldo Akun</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('akuntansi.saldo_awal.update_akun', $jurnal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" name="tanggal" value="{{ $jurnal->tanggal }}" required>
                    </div>
                </div>
            </div>

            @php
                // Logic to determine which is main account and which is balancing
                $akunId = $detail1->akun_id;
                $penyeimbangId = $detail2->akun_id;
                $jumlah = $detail1->debit > 0 ? $detail1->debit : $detail1->kredit;
                $posisi = $detail1->debit > 0 ? 'debit' : 'kredit';
            @endphp

            <div class="row mb-3 g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Akun Utama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-wallet2"></i></span>
                        <select class="form-select" name="akun_id" required>
                            <option value="">Pilih Akun</option>
                            @foreach($akun as $a)
                                <option value="{{ $a->id }}" {{ $akunId == $a->id ? 'selected' : '' }}>
                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Akun Penyeimbang (Contra)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-arrow-left-right"></i></span>
                        <select class="form-select" name="akun_penyeimbang" required>
                            <option value="">Pilih Akun (Biasanya Modal/Ekuitas)</option>
                            @foreach($akun as $a)
                                <option value="{{ $a->id }}" {{ $penyeimbangId == $a->id ? 'selected' : '' }}>
                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Jumlah Saldo (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">Rp</span>
                        <input type="number" class="form-control" name="jumlah" min="1" value="{{ $jumlah }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Posisi Saldo Akun Utama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-sort-numeric-down"></i></span>
                        <select class="form-select" name="posisi" required>
                            <option value="debit" {{ $posisi == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="kredit" {{ $posisi == 'kredit' ? 'selected' : '' }}>Kredit</option>
                        </select>
                    </div>
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
