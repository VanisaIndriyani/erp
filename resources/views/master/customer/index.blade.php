@extends('layouts.app')

@section('title', 'Master Data Customer')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Master Data Customer</h1>
        <p class="text-muted small mb-0">Kelola data pelanggan dan term pembayaran</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <form action="{{ route('customers.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari customer..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
            <i class="bi bi-plus-lg me-2"></i> Customer Baru
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="customersTable">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="py-3">Nama Customer</th>
                        <th class="py-3">Alamat</th>
                        <th class="py-3">Jatuh Tempo</th>
                        <th class="text-end px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="px-4 fw-medium text-primary">{{ $customer->kode }}</td>
                        <td class="fw-medium">{{ $customer->nama }}</td>
                        <td class="text-muted small" style="max-width: 250px;">{{ Str::limit($customer->alamat, 50) }}</td>
                        <td>
                            @if($customer->jatuh_tempo > 0)
                                <span class="badge bg-light text-dark border">{{ $customer->jatuh_tempo }} Hari</span>
                            @else
                                <span class="badge bg-light text-secondary border">Cash / COD</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light text-primary border-0 btn-edit" 
                                    data-item="{{ json_encode($customer) }}"
                                    data-bs-toggle="modal" data-bs-target="#editCustomerModal"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus customer ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border-0" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Belum ada data customer
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Create -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Tambah Customer Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-muted">Kode Customer <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="kode" class="form-control" required placeholder="Contoh: CUST-001">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-muted">Nama Customer <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap / Perusahaan">
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
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="number" name="jatuh_tempo" class="form-control" value="0" min="0" step="1">
                                <span class="input-group-text">Hari</span>
                            </div>
                            <div class="form-text small">Masa kredit (Term of Payment). Isi 0 untuk Cash/COD. Hanya angka bulat.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Customer: <span id="editTitleName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-muted">Kode Customer <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="kode" id="edit_kode" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-muted">Nama Customer <span class="text-danger">*</span></label>
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
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="number" name="jatuh_tempo" id="edit_tempo" class="form-control" value="0" min="0" step="1">
                                <span class="input-group-text">Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
    $(document).ready(function() {
        // Edit Modal Population
        $('.btn-edit').click(function() {
            let item = $(this).data('item');

            $('#editForm').attr('action', '/customers/' + item.id);
            $('#editTitleName').text(item.nama);
            
            $('#edit_kode').val(item.kode);
            $('#edit_nama').val(item.nama);
            $('#edit_alamat').val(item.alamat);
            $('#edit_tempo').val(item.jatuh_tempo);
        });
    });
</script>
@endpush
