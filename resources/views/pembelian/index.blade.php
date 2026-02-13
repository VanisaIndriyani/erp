@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Transaksi Pembelian</h1>
        <p class="text-muted small mb-0">Riwayat pembelian barang dari supplier</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <form action="{{ route('pembelian.transaksi.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari invoice/supplier..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <a href="{{ route('pembelian.transaksi.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Tambah Pembelian
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase small fw-bold text-muted">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="py-3">No Invoice</th>
                        <th class="py-3">Supplier</th>
                        <th class="py-3">Gudang</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="text-end px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $pembelian)
                    <tr>
                        <td class="px-4 text-nowrap">{{ date('d/m/Y', strtotime($pembelian->tanggal)) }}</td>
                        <td class="fw-medium text-primary">{{ $pembelian->no_invoice }}</td>
                        <td>{{ $pembelian->supplier->nama }}</td>
                        <td>{{ $pembelian->gudang ?? '-' }}</td>
                        <td class="fw-bold">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                        <td>
                            @if($pembelian->status_lunas == 'Lunas')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Lunas</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="text-end px-4">
                            <button type="button" class="btn btn-sm btn-light text-info border-0 btn-detail" 
                                data-item="{{ json_encode($pembelian) }}"
                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                            Belum ada data pembelian
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
<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Detail Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="100">No Invoice</td>
                                <td class="fw-bold" id="detail_invoice"></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal</td>
                                <td id="detail_tanggal"></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Supplier</td>
                                <td id="detail_supplier"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="100">No PO</td>
                                <td id="detail_po"></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Gudang</td>
                                <td id="detail_gudang"></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td id="detail_status"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center" width="100">Qty</th>
                                <th class="text-end" width="150">Harga</th>
                                <th class="text-end" width="150">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail_items">
                            <!-- Items will be populated by JS -->
                        </tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">PPN</td>
                                <td class="text-end" id="detail_ppn"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">Grand Total</td>
                                <td class="text-end fs-5 text-primary" id="detail_total"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-light border mb-0">
                    <small class="text-muted fw-bold d-block mb-1">Keterangan:</small>
                    <span id="detail_keterangan" class="text-dark small">-</span>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
    $(document).ready(function() {
        // Detail Modal Population
        $('.btn-detail').click(function() {
            let item = $(this).data('item');
            
            // Header Info
            $('#detail_invoice').text(item.no_invoice);
            $('#detail_tanggal').text(new Date(item.tanggal).toLocaleDateString('id-ID'));
            $('#detail_supplier').text(item.supplier.nama);
            $('#detail_po').text(item.no_po || '-');
            $('#detail_gudang').text(item.gudang || '-');
            $('#detail_status').text(item.status_lunas);
            $('#detail_keterangan').text(item.keterangan || '-');

            // Items
            let itemsHtml = '';
            item.details.forEach(function(detail) {
                itemsHtml += `
                    <tr>
                        <td>${detail.item ? detail.item.nama_item : 'Item Deleted'}</td>
                        <td class="text-center">${detail.qty}</td>
                        <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(detail.harga)}</td>
                        <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(detail.subtotal)}</td>
                    </tr>
                `;
            });
            $('#detail_items').html(itemsHtml);

            // Totals
            $('#detail_ppn').text('Rp ' + new Intl.NumberFormat('id-ID').format(item.ppn));
            $('#detail_total').text('Rp ' + new Intl.NumberFormat('id-ID').format(item.total));
        });
    });
</script>
@endpush
