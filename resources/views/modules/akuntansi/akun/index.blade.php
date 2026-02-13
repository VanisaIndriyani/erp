@extends('layouts.app')

@section('title', 'Daftar Perkiraan (Chart of Accounts)')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Perkiraan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAkunModal">
            <i class="bi bi-plus-lg"></i> Tambah Akun
        </button>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('akuntansi.akun.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Kode / Nama Akun..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i> Cari</button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th width="15%">Kode Akun</th>
                        <th width="35%">Nama Akun</th>
                        <th width="20%">Tipe</th>
                        <th width="15%">Saldo Normal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($akun as $a)
                    <tr>
                        <td class="fw-bold">{{ $a->kode_akun }}</td>
                        <td>{{ $a->nama_akun }}</td>
                        <td>
                            @if($a->tipe == 'asset') <span class="badge bg-success">Asset</span>
                            @elseif($a->tipe == 'liability') <span class="badge bg-warning text-dark">Liability</span>
                            @elseif($a->tipe == 'equity') <span class="badge bg-info text-dark">Equity</span>
                            @elseif($a->tipe == 'revenue') <span class="badge bg-primary">Revenue</span>
                            @elseif($a->tipe == 'expense') <span class="badge bg-danger">Expense</span>
                            @else <span class="badge bg-secondary">{{ $a->tipe }}</span>
                            @endif
                        </td>
                        <td>
                            @if($a->saldo_normal == 'debit') <span class="badge bg-light text-dark border">Debit</span>
                            @else <span class="badge bg-light text-dark border">Kredit</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-warning" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editAkunModal{{ $a->id }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('akuntansi.akun.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editAkunModal{{ $a->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title">Edit Akun</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('akuntansi.akun.update', $a->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="kode_akun" class="form-label">Kode Akun</label>
                                            <input type="text" class="form-control" name="kode_akun" value="{{ $a->kode_akun }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_akun" class="form-label">Nama Akun</label>
                                            <input type="text" class="form-control" name="nama_akun" value="{{ $a->nama_akun }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipe" class="form-label">Tipe Akun</label>
                                            <select class="form-select" name="tipe" required>
                                                <option value="asset" {{ $a->tipe == 'asset' ? 'selected' : '' }}>Asset (Harta)</option>
                                                <option value="liability" {{ $a->tipe == 'liability' ? 'selected' : '' }}>Liability (Kewajiban)</option>
                                                <option value="equity" {{ $a->tipe == 'equity' ? 'selected' : '' }}>Equity (Modal)</option>
                                                <option value="revenue" {{ $a->tipe == 'revenue' ? 'selected' : '' }}>Revenue (Pendapatan)</option>
                                                <option value="expense" {{ $a->tipe == 'expense' ? 'selected' : '' }}>Expense (Beban)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="saldo_normal" class="form-label">Saldo Normal</label>
                                            <select class="form-select" name="saldo_normal" required>
                                                <option value="debit" {{ $a->saldo_normal == 'debit' ? 'selected' : '' }}>Debit</option>
                                                <option value="kredit" {{ $a->saldo_normal == 'kredit' ? 'selected' : '' }}>Kredit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">Tidak ada data akun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $akun->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Add Modal -->
<div class="modal fade" id="addAkunModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Akun Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('akuntansi.akun.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_akun" class="form-label">Kode Akun</label>
                        <input type="text" class="form-control" name="kode_akun" placeholder="Contoh: 1001" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_akun" class="form-label">Nama Akun</label>
                        <input type="text" class="form-control" name="nama_akun" placeholder="Contoh: Kas Besar" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe Akun</label>
                        <select class="form-select" name="tipe" required>
                            <option value="">-- Pilih Tipe Akun --</option>
                            <option value="asset">Asset (Harta)</option>
                            <option value="liability">Liability (Kewajiban)</option>
                            <option value="equity">Equity (Modal)</option>
                            <option value="revenue">Revenue (Pendapatan)</option>
                            <option value="expense">Expense (Beban)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="saldo_normal" class="form-label">Saldo Normal</label>
                        <select class="form-select" name="saldo_normal" required>
                            <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
