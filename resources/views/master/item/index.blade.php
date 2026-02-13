@extends('layouts.app')

@section('title', 'Master Data Item')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Master Data Item</h1>
        <p class="text-muted small mb-0">Kelola data barang dan jasa</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <form action="{{ route('items.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari item..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createItemModal">
            <i class="bi bi-plus-lg me-2"></i> Item Baru
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="itemsTable">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="px-4 py-3">Kode Item</th>
                        <th class="py-3">Nama Item</th>
                        <th class="py-3">Stok</th>
                        <th class="py-3">Satuan</th>
                        <th class="py-3">Jenis</th>
                        <th class="py-3">Merek</th>
                        <th class="text-end py-3">Harga Jual</th>
                        <th class="py-3">Status</th>
                        <th class="text-end px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td class="px-4 fw-medium text-primary">{{ $item->kode_item }}</td>
                        <td class="fw-medium">{{ $item->nama_item }}</td>
                        <td class="{{ $item->stok <= $item->stok_minimum ? 'text-danger fw-bold' : '' }}">
                            {{ number_format($item->stok, 0, ',', '.') }}
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $item->satuan }}</span></td>
                        <td>{{ $item->jenis }}</td>
                        <td>{{ $item->merk }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status_jual)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Discontinue</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-secondary border-0 btn-detail" 
                                    data-item="{{ json_encode($item) }}"
                                    data-bs-toggle="modal" data-bs-target="#detailItemModal"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('items.history', $item->id) }}" class="btn btn-sm btn-light text-info border-0" title="Kartu Stok">
                                    <i class="bi bi-card-list"></i>
                                </a>
                                <button class="btn btn-sm btn-light text-primary border-0 btn-edit" 
                                    data-item="{{ json_encode($item) }}"
                                    data-bs-toggle="modal" data-bs-target="#editItemModal"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus item ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border-0" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Create -->
<div class="modal fade" id="createItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2"></i>Tambah Item Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs nav-fill mb-4" id="createTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-medium" id="create-umum-tab" data-bs-toggle="tab" data-bs-target="#create-umum" type="button" role="tab">
                                <i class="bi bi-info-circle me-2"></i>Data Umum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" id="create-satuan-tab" data-bs-toggle="tab" data-bs-target="#create-satuan" type="button" role="tab">
                                <i class="bi bi-tag me-2"></i>Satuan & Harga
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" id="create-akunting-tab" data-bs-toggle="tab" data-bs-target="#create-akunting" type="button" role="tab">
                                <i class="bi bi-journal-text me-2"></i>Akunting
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="createTabContent">
                        <!-- Tab Data Umum -->
                        <div class="tab-pane fade show active" id="create-umum" role="tabpanel">
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label text-muted">Tipe Item</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="create_tipe_inventory" value="inventory" checked>
                                            <label class="form-check-label" for="create_tipe_inventory">Barang (INV)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="create_tipe_jasa" value="jasa">
                                            <label class="form-check-label" for="create_tipe_jasa">Jasa (SRV)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="create_tipe_rakitan" value="rakitan">
                                            <label class="form-check-label" for="create_tipe_rakitan">Rakitan (ASM)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="create_tipe_non" value="non-inventory">
                                            <label class="form-check-label" for="create_tipe_non">Non-Inventory</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Kode Item <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" name="kode_item" class="form-control" required placeholder="Contoh: BRG-001">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Nama Item <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama_item" class="form-control" required placeholder="Nama lengkap item">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Jenis</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" name="jenis" class="form-control" list="jenisList" placeholder="Pilih atau ketik baru">
                                        <datalist id="jenisList">
                                            @foreach($items->unique('jenis') as $i)
                                                @if($i->jenis) <option value="{{ $i->jenis }}"> @endif
                                            @endforeach
                                        </datalist>
                                        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Merek</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" name="merk" class="form-control" list="merkList" placeholder="Pilih atau ketik baru">
                                        <datalist id="merkList">
                                            @foreach($items->unique('merk') as $i)
                                                @if($i->merk) <option value="{{ $i->merk }}"> @endif
                                            @endforeach
                                        </datalist>
                                        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Rak</label>
                                <div class="col-sm-4">
                                    <input type="text" name="rak" class="form-control" placeholder="Lokasi rak">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">HPP System</label>
                                <div class="col-sm-4">
                                    <select name="hpp_system" class="form-select">
                                        <option value="AVERAGE">AVERAGE</option>
                                        <option value="FIFO">FIFO</option>
                                        <option value="LIFO">LIFO</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Pajak Include</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="checkbox" name="pajak_include" value="1">
                                        </div>
                                        <input type="text" class="form-control" value="11" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Status Jual</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jual" id="create_status_active" value="1" checked>
                                            <label class="form-check-label" for="create_status_active">Masih dijual</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jual" id="create_status_inactive" value="0">
                                            <label class="form-check-label" for="create_status_inactive">Tidak dijual / Discontinue</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Satuan & Harga -->
                        <div class="tab-pane fade" id="create-satuan" role="tabpanel">
                            <div class="alert alert-info bg-info-subtle border-0 mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i> Konfigurasi harga dan satuan item
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Pilihan Harga</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" value="satu_harga" checked>
                                            <label class="form-check-label">Satu Harga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" value="satuan">
                                            <label class="form-check-label">Berdasarkan Satuan</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" value="level">
                                            <label class="form-check-label">Berdasarkan Level Harga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" value="jumlah">
                                            <label class="form-check-label">Berdasarkan Jumlah</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Satuan Dasar</label>
                                <div class="col-sm-4">
                                    <select name="satuan" class="form-select">
                                        <option value="PCS">PCS</option>
                                        <option value="UNIT">UNIT</option>
                                        <option value="BOX">BOX</option>
                                        <option value="SET">SET</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Harga Pokok <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_pokok" id="create_pokok" class="form-control" required>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">UP (%)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="up_persen" id="create_up" class="form-control" step="0.01">
                                        <span class="input-group-text">= Harga Jual</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Harga Jual <span class="text-danger">*</span></label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_jual" id="create_jual" class="form-control" required>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Barcode</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                        <input type="text" name="barcode" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Stok Minimum</label>
                                <div class="col-sm-4">
                                    <input type="number" name="stok_minimum" class="form-control" value="0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Poin Dasar</label>
                                <div class="col-sm-4">
                                    <input type="number" name="poin_dasar" class="form-control" value="0">
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Komisi Sales</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="komisi_sales" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Akunting -->
                        <div class="tab-pane fade" id="create-akunting" role="tabpanel">
                            <div class="alert alert-warning bg-warning-subtle border-0 mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Pengaturan akun akuntansi untuk item ini
                            </div>
                            
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Harga Pokok Penjualan</label>
                                <div class="col-sm-9">
                                    <select name="akun_hpp_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Pendapatan Jual</label>
                                <div class="col-sm-9">
                                    <select name="akun_penjualan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Persediaan</label>
                                <div class="col-sm-9">
                                    <select name="akun_persediaan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Retur Penjualan</label>
                                <div class="col-sm-9">
                                    <select name="akun_retur_penjualan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <!-- Nav Tabs (Simplified for Edit, or same as Create) -->
                    <!-- We can reuse the same structure, but IDs must be unique or scoped.
                         For simplicity, we'll duplicate the structure but use class-based selection or specific IDs 
                         if we want to use the same JS logic. 
                         Let's use specific IDs for the Edit modal to avoid conflicts.
                    -->
                    <ul class="nav nav-tabs nav-fill mb-4" id="editTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-medium" id="edit-umum-tab" data-bs-toggle="tab" data-bs-target="#edit-umum" type="button" role="tab">
                                <i class="bi bi-info-circle me-2"></i>Data Umum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" id="edit-satuan-tab" data-bs-toggle="tab" data-bs-target="#edit-satuan" type="button" role="tab">
                                <i class="bi bi-tag me-2"></i>Satuan & Harga
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-medium" id="edit-akunting-tab" data-bs-toggle="tab" data-bs-target="#edit-akunting" type="button" role="tab">
                                <i class="bi bi-journal-text me-2"></i>Akunting
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editTabContent">
                        <!-- Tab Data Umum -->
                        <div class="tab-pane fade show active" id="edit-umum" role="tabpanel">
                            <!-- Fields matching Create but with edit_ prefix for IDs/names where appropriate for JS access, 
                                 but names must match DB columns for Laravel.
                                 We only need IDs for JS population.
                            -->
                             <div class="row mb-4">
                                <label class="col-sm-2 col-form-label text-muted">Tipe Item</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3">
                                        <!-- Radios need unique IDs but same name within the form -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="edit_tipe_inventory" value="inventory">
                                            <label class="form-check-label" for="edit_tipe_inventory">Barang (INV)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="edit_tipe_jasa" value="jasa">
                                            <label class="form-check-label" for="edit_tipe_jasa">Jasa (SRV)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="edit_tipe_rakitan" value="rakitan">
                                            <label class="form-check-label" for="edit_tipe_rakitan">Rakitan (ASM)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="tipe_item" id="edit_tipe_non" value="non-inventory">
                                            <label class="form-check-label" for="edit_tipe_non">Non-Inventory</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Kode Item</label>
                                <div class="col-sm-4">
                                    <input type="text" name="kode_item" id="edit_kode_item" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Nama Item</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama_item" id="edit_nama_item" class="form-control" required>
                                </div>
                            </div>

                             <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Jenis</label>
                                <div class="col-sm-4">
                                    <input type="text" name="jenis" id="edit_jenis" class="form-control" list="jenisList">
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Merek</label>
                                <div class="col-sm-4">
                                    <input type="text" name="merk" id="edit_merk" class="form-control" list="merkList">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Rak</label>
                                <div class="col-sm-4">
                                    <input type="text" name="rak" id="edit_rak" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">HPP System</label>
                                <div class="col-sm-4">
                                    <select name="hpp_system" id="edit_hpp_system" class="form-select">
                                        <option value="AVERAGE">AVERAGE</option>
                                        <option value="FIFO">FIFO</option>
                                        <option value="LIFO">LIFO</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Pajak Include</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <div class="input-group-text bg-white">
                                            <input class="form-check-input mt-0" type="checkbox" name="pajak_include" id="edit_pajak_include" value="1">
                                        </div>
                                        <input type="text" class="form-control" value="11" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Keterangan</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Status Jual</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jual" id="edit_status_active" value="1">
                                            <label class="form-check-label" for="edit_status_active">Masih dijual</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_jual" id="edit_status_inactive" value="0">
                                            <label class="form-check-label" for="edit_status_inactive">Tidak dijual / Discontinue</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Satuan & Harga -->
                        <div class="tab-pane fade" id="edit-satuan" role="tabpanel">
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Pilihan Harga</label>
                                <div class="col-sm-10">
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" id="edit_pilihan_satu" value="satu_harga">
                                            <label class="form-check-label" for="edit_pilihan_satu">Satu Harga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" id="edit_pilihan_satuan" value="satuan">
                                            <label class="form-check-label" for="edit_pilihan_satuan">Berdasarkan Satuan</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" id="edit_pilihan_level" value="level">
                                            <label class="form-check-label" for="edit_pilihan_level">Berdasarkan Level Harga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pilihan_harga" id="edit_pilihan_jumlah" value="jumlah">
                                            <label class="form-check-label" for="edit_pilihan_jumlah">Berdasarkan Jumlah</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Satuan Dasar</label>
                                <div class="col-sm-4">
                                    <select name="satuan" id="edit_satuan" class="form-select">
                                        <option value="PCS">PCS</option>
                                        <option value="UNIT">UNIT</option>
                                        <option value="BOX">BOX</option>
                                        <option value="SET">SET</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Harga Pokok</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_pokok" id="edit_pokok" class="form-control" required>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">UP (%)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="up_persen" id="edit_up" class="form-control" step="0.01">
                                        <span class="input-group-text">= Harga Jual</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Harga Jual</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="harga_jual" id="edit_jual" class="form-control" required>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Barcode</label>
                                <div class="col-sm-4">
                                    <input type="text" name="barcode" id="edit_barcode" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Stok Minimum</label>
                                <div class="col-sm-4">
                                    <input type="number" name="stok_minimum" id="edit_stok_minimum" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-muted">Poin Dasar</label>
                                <div class="col-sm-4">
                                    <input type="number" name="poin_dasar" id="edit_poin_dasar" class="form-control">
                                </div>
                                <label class="col-sm-2 col-form-label text-muted text-end">Komisi Sales</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="komisi_sales" id="edit_komisi_sales" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Akunting -->
                        <div class="tab-pane fade" id="edit-akunting" role="tabpanel">
                             <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Harga Pokok Penjualan</label>
                                <div class="col-sm-9">
                                    <select name="akun_hpp_id" id="edit_akun_hpp_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Pendapatan Jual</label>
                                <div class="col-sm-9">
                                    <select name="akun_penjualan_id" id="edit_akun_penjualan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Persediaan</label>
                                <div class="col-sm-9">
                                    <select name="akun_persediaan_id" id="edit_akun_persediaan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Retur Penjualan</label>
                                <div class="col-sm-9">
                                    <select name="akun_retur_penjualan_id" id="edit_akun_retur_penjualan_id" class="form-select">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($akun as $a)
                                            <option value="{{ $a->id }}">{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-info-circle me-2"></i>Detail Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th class="w-25 bg-light">Kode Item</th><td id="detail_kode_item"></td></tr>
                        <tr><th class="bg-light">Nama Item</th><td id="detail_nama_item"></td></tr>
                        <tr><th class="bg-light">Tipe</th><td id="detail_tipe_item"></td></tr>
                        <tr><th class="bg-light">Jenis</th><td id="detail_jenis"></td></tr>
                        <tr><th class="bg-light">Merek</th><td id="detail_merk"></td></tr>
                        <tr><th class="bg-light">Rak</th><td id="detail_rak"></td></tr>
                        <tr><th class="bg-light">Stok</th><td id="detail_stok"></td></tr>
                        <tr><th class="bg-light">Satuan</th><td id="detail_satuan"></td></tr>
                        <tr><th class="bg-light">Harga Pokok</th><td id="detail_harga_pokok"></td></tr>
                        <tr><th class="bg-light">Harga Jual</th><td id="detail_harga_jual"></td></tr>
                        <tr><th class="bg-light">Status</th><td id="detail_status_jual"></td></tr>
                        <tr><th class="bg-light">Keterangan</th><td id="detail_keterangan"></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Create Modal Logic ---
        const createPokok = document.getElementById('create_pokok');
        const createUp = document.getElementById('create_up');
        const createJual = document.getElementById('create_jual');

        function calcJual(pokokEl, upEl, jualEl) {
            const p = parseFloat(pokokEl.value) || 0;
            const u = parseFloat(upEl.value) || 0;
            const res = p + (p * u / 100);
            jualEl.value = Math.round(res);
        }

        function calcUp(pokokEl, upEl, jualEl) {
            const p = parseFloat(pokokEl.value) || 0;
            const j = parseFloat(jualEl.value) || 0;
            if(p > 0) {
                const u = ((j - p) / p) * 100;
                upEl.value = u.toFixed(2);
            }
        }

        if(createPokok && createUp && createJual) {
            createPokok.addEventListener('input', () => calcJual(createPokok, createUp, createJual));
            createUp.addEventListener('input', () => calcJual(createPokok, createUp, createJual));
            createJual.addEventListener('input', () => calcUp(createPokok, createUp, createJual));
        }

        // --- Edit Modal Logic ---
        const editModal = document.getElementById('editItemModal');
        const editPokok = document.getElementById('edit_pokok');
        const editUp = document.getElementById('edit_up');
        const editJual = document.getElementById('edit_jual');

        if(editPokok && editUp && editJual) {
            editPokok.addEventListener('input', () => calcJual(editPokok, editUp, editJual));
            editUp.addEventListener('input', () => calcJual(editPokok, editUp, editJual));
            editJual.addEventListener('input', () => calcUp(editPokok, editUp, editJual));
        }

        if (editModal) {
            editModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const item = JSON.parse(button.getAttribute('data-item'));
                
                // Form Action
                const form = document.getElementById('editItemForm');
                // Fix: Use Laravel route helper to generate correct URL including subfolder
                const updateUrl = "{{ route('items.update', ':id') }}";
                form.action = updateUrl.replace(':id', item.id);
                
                // Populate inputs
                document.getElementById('edit_kode_item').value = item.kode_item;
                document.getElementById('edit_nama_item').value = item.nama_item;
                document.getElementById('edit_jenis').value = item.jenis || '';
                document.getElementById('edit_merk').value = item.merk || '';
                document.getElementById('edit_rak').value = item.rak || '';
                document.getElementById('edit_hpp_system').value = item.hpp_system || 'AVERAGE';
                document.getElementById('edit_pajak_include').checked = item.pajak_include == 1;
                document.getElementById('edit_keterangan').value = item.keterangan || '';
                
                // Radios: Tipe Item
                const tipeRadio = document.querySelector(`#editItemForm input[name="tipe_item"][value="${item.tipe_item}"]`);
                if(tipeRadio) tipeRadio.checked = true;

                // Radios: Status Jual
                const statusRadio = document.querySelector(`#editItemForm input[name="status_jual"][value="${item.status_jual}"]`);
                if(statusRadio) statusRadio.checked = true;

                // Radios: Pilihan Harga
                const hargaRadio = document.querySelector(`#editItemForm input[name="pilihan_harga"][value="${item.pilihan_harga}"]`);
                if(hargaRadio) hargaRadio.checked = true;

                document.getElementById('edit_satuan').value = item.satuan || 'PCS';
                document.getElementById('edit_pokok').value = item.harga_pokok;
                document.getElementById('edit_up').value = item.up_persen || 0;
                document.getElementById('edit_jual').value = item.harga_jual;
                document.getElementById('edit_barcode').value = item.barcode || '';
                document.getElementById('edit_stok_minimum').value = item.stok_minimum || 0;
                document.getElementById('edit_poin_dasar').value = item.poin_dasar || 0;
                document.getElementById('edit_komisi_sales').value = item.komisi_sales || 0;

                document.getElementById('edit_akun_hpp_id').value = item.akun_hpp_id || '';
                document.getElementById('edit_akun_penjualan_id').value = item.akun_penjualan_id || '';
                document.getElementById('edit_akun_persediaan_id').value = item.akun_persediaan_id || '';
                document.getElementById('edit_akun_retur_penjualan_id').value = item.akun_retur_penjualan_id || '';
            });
        }

        // --- Detail Modal Logic ---
        const detailModal = document.getElementById('detailItemModal');
        if (detailModal) {
            detailModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const item = JSON.parse(button.getAttribute('data-item'));
                
                document.getElementById('detail_kode_item').textContent = item.kode_item;
                document.getElementById('detail_nama_item').textContent = item.nama_item;
                document.getElementById('detail_tipe_item').textContent = item.tipe_item;
                document.getElementById('detail_jenis').textContent = item.jenis || '-';
                document.getElementById('detail_merk').textContent = item.merk || '-';
                document.getElementById('detail_rak').textContent = item.rak || '-';
                document.getElementById('detail_stok').textContent = new Intl.NumberFormat('id-ID').format(item.stok);
                document.getElementById('detail_satuan').textContent = item.satuan;
                document.getElementById('detail_harga_pokok').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga_pokok);
                document.getElementById('detail_harga_jual').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga_jual);
                document.getElementById('detail_status_jual').innerHTML = item.status_jual ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Discontinue</span>';
                document.getElementById('detail_keterangan').textContent = item.keterangan || '-';
            });
        }

        // --- Form Validation for Hidden Tabs ---
        var forms = document.querySelectorAll('form');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('invalid', function(event) {
                // Prevent default browser error bubble to handle it manually
                // event.preventDefault(); 
                
                var invalidField = event.target;
                // Find the closest tab-pane
                var tabPane = invalidField.closest('.tab-pane');
                if (tabPane && !tabPane.classList.contains('active')) {
                    // Find the tab button that targets this pane
                    var tabId = tabPane.id;
                    var tabButton = document.querySelector('button[data-bs-target="#' + tabId + '"]');
                    if (tabButton) {
                        var tab = new bootstrap.Tab(tabButton);
                        tab.show();
                        // Wait for transition and focus
                        setTimeout(function() {
                            invalidField.focus();
                        }, 500);
                    }
                }
            }, true); // useCapture=true to catch invalid event
        });
    });
</script>
@endpush
