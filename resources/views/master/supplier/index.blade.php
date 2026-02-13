@extends('layouts.app')

@section('title', 'Master Data Supplier')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Master Data Supplier</h1>
        <p class="text-muted small mb-0">Kelola data pemasok barang dan jasa</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <form action="{{ route('suppliers.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari supplier..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
            <i class="bi bi-plus-lg me-2"></i> Supplier Baru
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="suppliersTable">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="py-3">Nama Supplier</th>
                        <th class="py-3">Alamat</th>
                        <th class="py-3">Jatuh Tempo</th>
                        <th class="py-3">Nilai Pajak</th>
                        <th class="py-3">Status Pajak</th>
                        <th class="text-end px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td class="px-4 fw-medium text-primary">{{ $supplier->kode }}</td>
                        <td class="fw-medium">{{ $supplier->nama }}</td>
                        <td class="text-muted small" style="max-width: 250px;">{{ Str::limit($supplier->alamat, 50) }}</td>
                        <td>
                            @if($supplier->jatuh_tempo > 0)
                                <span class="badge bg-light text-dark border">{{ $supplier->jatuh_tempo }} Hari</span>
                            @else
                                <span class="badge bg-light text-secondary border">-</span>
                            @endif
                        </td>
                        <td>{{ $supplier->nilai_pajak }}%</td>
                        <td>
                            @if($supplier->menggunakan_pajak == 'include')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Include</span>
                            @elseif($supplier->menggunakan_pajak == 'exclude')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">Exclude</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">Non-PKP</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-primary border-0 btn-edit" 
                                    data-item="{{ json_encode($supplier) }}"
                                    data-bs-toggle="modal" data-bs-target="#editSupplierModal"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus supplier ini?')">
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
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-truck me-2"></i>Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Kode Supplier <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="kode" class="form-control" required placeholder="Contoh: SUP-001">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Nama Supplier <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama" class="form-control" required placeholder="Nama Perusahaan / Perorangan">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..."></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Jatuh Tempo</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="jatuh_tempo" class="form-control" value="0">
                                        <span class="input-group-text">Hari</span>
                                    </div>
                                    <div class="form-text small">Masa kredit pembayaran (Term of Payment)</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Menggunakan Pajak</label>
                                <div class="col-sm-4">
                                    <select name="menggunakan_pajak" class="form-select">
                                        <option value="non">Non-PKP</option>
                                        <option value="include">Include</option>
                                        <option value="exclude">Exclude</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Nilai Pajak</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="nilai_pajak" class="form-control" value="11" step="0.1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Supplier: <span id="editTitleName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Kode Supplier <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="kode" id="edit_kode" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Nama Supplier <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat" id="edit_alamat" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Jatuh Tempo</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="jatuh_tempo" id="edit_tempo" class="form-control" value="0">
                                        <span class="input-group-text">Hari</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Menggunakan Pajak</label>
                                <div class="col-sm-4">
                                    <select name="menggunakan_pajak" id="edit_pajak_tipe" class="form-select">
                                        <option value="non">Non-PKP</option>
                                        <option value="include">Include</option>
                                        <option value="exclude">Exclude</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label text-muted">Nilai Pajak</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" name="nilai_pajak" id="edit_nilai_pajak" class="form-control" value="11" step="0.1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<!-- DataTables & jQuery (Ensure these are loaded in layout or here) -->
<!-- Using native table styles from Bootstrap 5, but can add DataTables if needed -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> -->
<script>
    $(document).ready(function() {
        // Simple search filter if not using server-side
        // $('#suppliersTable').DataTable();

        // Edit Modal Population
        $('.btn-edit').click(function() {
            let item = $(this).data('item');

            $('#editForm').attr('action', '/suppliers/' + item.id);
            $('#editTitleName').text(item.nama);
            
            $('#edit_kode').val(item.kode);
            $('#edit_nama').val(item.nama);
            $('#edit_alamat').val(item.alamat);
            $('#edit_tempo').val(item.jatuh_tempo);
            $('#edit_pajak_tipe').val(item.menggunakan_pajak);
            $('#edit_nilai_pajak').val(item.nilai_pajak);
        });
    });
</script>
@endpush
