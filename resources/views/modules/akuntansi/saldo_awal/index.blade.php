@extends('layouts.app')

@section('title', 'Saldo Awal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Saldo Awal</h1>
</div>

<div class="card card-dashboard border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3 px-4">
        <ul class="nav nav-tabs card-header-tabs" id="saldoAwalTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button" role="tab" aria-controls="akun" aria-selected="true">
                    <i class="bi bi-journals me-2"></i>Saldo Awal Akun
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="hutang-tab" data-bs-toggle="tab" data-bs-target="#hutang" type="button" role="tab" aria-controls="hutang" aria-selected="false">
                    <i class="bi bi-box-arrow-in-left me-2"></i>Saldo Awal Hutang
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="piutang-tab" data-bs-toggle="tab" data-bs-target="#piutang" type="button" role="tab" aria-controls="piutang" aria-selected="false">
                    <i class="bi bi-box-arrow-right me-2"></i>Saldo Awal Piutang
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="saldoAwalTabContent">
            
            <!-- TAB SALDO AWAL AKUN -->
            <div class="tab-pane fade show active" id="akun" role="tabpanel" aria-labelledby="akun-tab">
                <div class="alert alert-info border-0 bg-light-info text-dark mb-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Petunjuk Pengisian</h6>
                            <p class="mb-0 small text-muted">Masukkan saldo awal untuk setiap akun secara batch. Pastikan Total Aktiva = Total Pasiva (Balance).</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('akuntansi.saldo_awal.store_akun') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">Tanggal Saldo Awal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-calendar"></i></span>
                                <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-9 text-end">
                            <div class="d-inline-block p-2 rounded bg-light border">
                                <span class="fw-bold me-2">Status:</span>
                                <span id="balance-status" class="badge bg-success rounded-pill px-3">Seimbang</span>
                                <span class="mx-2 text-muted">|</span>
                                <small class="text-muted">Selisih: Rp <span id="balance-diff" class="fw-bold text-dark">0</span></small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- AKTIVA (Assets & Expenses) -->
                        <div class="col-md-6">
                            <div class="card h-100 border shadow-none">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Aktiva (Assets & Expenses)</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="ps-3">Kode</th>
                                                    <th>Nama Akun</th>
                                                    <th width="140" class="pe-3 text-end">Saldo (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($akunAktiva as $a)
                                                <tr>
                                                    <td class="ps-3 small align-middle">{{ $a->kode_akun }}</td>
                                                    <td class="small align-middle">{{ $a->nama_akun }}</td>
                                                    <td class="pe-3">
                                                        <input type="number" class="form-control form-control-sm text-end saldo-input aktiva-input border-0 bg-light" 
                                                            name="saldo[{{ $a->id }}]" 
                                                            value="{{ $saldoAwalMap[$a->id] ?? 0 }}" 
                                                            min="0" step="any" placeholder="0">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total Aktiva:</span>
                                        <span class="text-primary" id="total-aktiva">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PASIVA (Liabilities, Equity, Income) -->
                        <div class="col-md-6">
                            <div class="card h-100 border shadow-none">
                                <div class="card-header bg-danger text-white py-2">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-dash-circle me-2"></i>Pasiva (Liabilities, Equity, Income)</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="ps-3">Kode</th>
                                                    <th>Nama Akun</th>
                                                    <th width="140" class="pe-3 text-end">Saldo (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($akunPasiva as $a)
                                                <tr>
                                                    <td class="ps-3 small align-middle">{{ $a->kode_akun }}</td>
                                                    <td class="small align-middle">{{ $a->nama_akun }}</td>
                                                    <td class="pe-3">
                                                        <input type="number" class="form-control form-control-sm text-end saldo-input pasiva-input border-0 bg-light" 
                                                            name="saldo[{{ $a->id }}]" 
                                                            value="{{ $saldoAwalMap[$a->id] ?? 0 }}" 
                                                            min="0" step="any" placeholder="0">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total Pasiva:</span>
                                        <span class="text-danger" id="total-pasiva">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i> Simpan Saldo Awal Akun</button>
                    </div>
                </form>
            </div>

            <!-- TAB SALDO AWAL HUTANG -->
            <div class="tab-pane fade" id="hutang" role="tabpanel" aria-labelledby="hutang-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-plus-circle-dotted me-2"></i>Input Hutang Baru</h6>
                                <form action="{{ route('akuntansi.saldo_awal.store_hutang') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Tanggal</label>
                                        <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Supplier</label>
                                        <select class="form-select form-select-sm" name="supplier_id" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach($suppliers as $s)
                                                <option value="{{ $s->id }}">{{ $s->kode }} - {{ $s->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Akun Hutang</label>
                                        <select class="form-select form-select-sm" name="akun_hutang" required>
                                            @foreach($akun as $a)
                                                <option value="{{ $a->id }}" {{ str_contains(strtolower($a->nama_akun), 'hutang') ? 'selected' : '' }}>
                                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Akun Penyeimbang (Modal)</label>
                                        <select class="form-select form-select-sm" name="akun_penyeimbang" required>
                                            @foreach($akun as $a)
                                                <option value="{{ $a->id }}" {{ str_contains(strtolower($a->nama_akun), 'modal') || str_contains(strtolower($a->nama_akun), 'ekuitas') ? 'selected' : '' }}>
                                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Jumlah (Rp)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" name="jumlah" min="1" placeholder="0" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-save me-1"></i> Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i>Riwayat Saldo Awal Hutang</h6>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-hover mb-0">
                                <thead class="table-light small text-muted">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Ref</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($saldoAwalHutang as $sa)
                                    <tr>
                                        <td class="align-middle small">{{ \Carbon\Carbon::parse($sa->tanggal)->format('d/m/Y') }}</td>
                                        <td class="align-middle small fw-bold text-info">{{ $sa->no_ref }}</td>
                                        <td class="align-middle small text-muted">{{ $sa->keterangan }}</td>
                                        <td class="align-middle text-end fw-bold">Rp {{ number_format($sa->details->sum('kredit'), 0, ',', '.') }}</td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('akuntansi.saldo_awal.edit_hutang', $sa->id) }}" class="btn btn-sm btn-icon text-warning p-0 me-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('akuntansi.saldo_awal.destroy', $sa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus saldo awal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-icon text-danger p-0" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted small">Belum ada data saldo awal hutang.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB SALDO AWAL PIUTANG -->
            <div class="tab-pane fade" id="piutang" role="tabpanel" aria-labelledby="piutang-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-light border-0 mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-plus-circle-dotted me-2"></i>Input Piutang Baru</h6>
                                <form action="{{ route('akuntansi.saldo_awal.store_piutang') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Tanggal</label>
                                        <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Customer</label>
                                        <select class="form-select form-select-sm" name="customer_id" required>
                                            <option value="">Pilih Customer</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}">{{ $c->kode }} - {{ $c->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Akun Piutang</label>
                                        <select class="form-select form-select-sm" name="akun_piutang" required>
                                            @foreach($akun as $a)
                                                <option value="{{ $a->id }}" {{ str_contains(strtolower($a->nama_akun), 'piutang') ? 'selected' : '' }}>
                                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Akun Penyeimbang (Modal)</label>
                                        <select class="form-select form-select-sm" name="akun_penyeimbang" required>
                                            @foreach($akun as $a)
                                                <option value="{{ $a->id }}" {{ str_contains(strtolower($a->nama_akun), 'modal') || str_contains(strtolower($a->nama_akun), 'ekuitas') ? 'selected' : '' }}>
                                                    {{ $a->kode_akun }} - {{ $a->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Jumlah (Rp)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" name="jumlah" min="1" placeholder="0" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-save me-1"></i> Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i>Riwayat Saldo Awal Piutang</h6>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-hover mb-0">
                                <thead class="table-light small text-muted">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Ref</th>
                                        <th>Keterangan</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($saldoAwalPiutang as $sa)
                                    <tr>
                                        <td class="align-middle small">{{ \Carbon\Carbon::parse($sa->tanggal)->format('d/m/Y') }}</td>
                                        <td class="align-middle small fw-bold text-info">{{ $sa->no_ref }}</td>
                                        <td class="align-middle small text-muted">{{ $sa->keterangan }}</td>
                                        <td class="align-middle text-end fw-bold">Rp {{ number_format($sa->details->sum('debit'), 0, ',', '.') }}</td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('akuntansi.saldo_awal.edit_piutang', $sa->id) }}" class="btn btn-sm btn-icon text-warning p-0 me-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('akuntansi.saldo_awal.destroy', $sa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus saldo awal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-icon text-danger p-0" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted small">Belum ada data saldo awal piutang.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.saldo-input');
        
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        function calculateTotals() {
            let totalAktiva = 0;
            let totalPasiva = 0;

            document.querySelectorAll('.aktiva-input').forEach(input => {
                let val = parseFloat(input.value);
                if (isNaN(val)) val = 0;
                totalAktiva += val;
            });

            document.querySelectorAll('.pasiva-input').forEach(input => {
                let val = parseFloat(input.value);
                if (isNaN(val)) val = 0;
                totalPasiva += val;
            });

            document.getElementById('total-aktiva').innerText = formatNumber(totalAktiva);
            document.getElementById('total-pasiva').innerText = formatNumber(totalPasiva);
            
            const diff = totalAktiva - totalPasiva;
            const diffElem = document.getElementById('balance-diff');
            const statusElem = document.getElementById('balance-status');

            diffElem.innerText = formatNumber(Math.abs(diff));
            
            // Tolerance for float point errors
            if (Math.abs(diff) < 0.01) {
                statusElem.className = 'badge bg-success rounded-pill px-3';
                statusElem.innerText = 'Seimbang';
            } else {
                statusElem.className = 'badge bg-danger rounded-pill px-3';
                statusElem.innerText = 'Tidak Seimbang';
            }
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateTotals);
        });

        calculateTotals(); // Initial calculation
    });
</script>
@endsection