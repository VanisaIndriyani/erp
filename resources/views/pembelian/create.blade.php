@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Tambah Pembelian Baru</h1>
        <p class="text-muted small mb-0">Input data pembelian barang dari supplier</p>
    </div>
    <a href="{{ route('pembelian.transaksi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<form action="{{ route('pembelian.transaksi.store') }}" method="POST" id="pembelianForm">
    @csrf
    <div class="row g-4">
        <!-- Left Column: Invoice Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Informasi Faktur</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Invoice <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash"></i></span>
                            <input type="text" name="no_invoice" class="form-control border-start-0 ps-0" placeholder="Contoh: INV-2023001" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="tanggal" class="form-control border-start-0 ps-0" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No PO (Optional)</label>
                        <input type="text" name="no_po" class="form-control" placeholder="Nomor Purchase Order">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Gudang / Lokasi</label>
                        <input type="text" name="gudang" class="form-control" placeholder="Contoh: Gudang Utama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Detail Barang</h5>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" id="addItemBtn">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="itemsTable">
                            <thead class="bg-light text-uppercase small fw-bold text-muted">
                                <tr>
                                    <th style="min-width: 250px;">Item Barang</th>
                                    <th style="width: 120px;" class="text-center">Qty</th>
                                    <th style="width: 180px;" class="text-end">Harga Satuan</th>
                                    <th style="width: 180px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody" class="border-top-0">
                                <!-- Dynamic Rows -->
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold pt-3">Subtotal</td>
                                    <td class="text-end fw-bold pt-3" id="totalSubtotal">0</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold align-middle">
                                        PPN (Rp)
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light">Rp</span>
                                            <input type="number" name="ppn" id="ppnInput" class="form-control text-end" value="0" min="0">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-end fw-bold fs-5 text-dark pt-3">Grand Total</td>
                                    <td class="text-end fw-bold fs-5 text-primary pt-3" id="grandTotal">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="alert alert-info border-0 bg-info-subtle text-info mt-3 mb-0 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <small>Stok barang akan bertambah otomatis setelah transaksi disimpan.</small>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-lg btn-success px-5 rounded-pill shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Template Row for JS -->
<template id="rowTemplate">
    <tr>
        <td class="ps-0">
            <select name="items[{index}][item_id]" class="form-select item-select" required onchange="updateItemPrice(this)">
                <option value="">-- Pilih Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-price="{{ $item->harga_pokok }}">
                        {{ $item->kode_item }} - {{ $item->nama_item }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="text-center">
            <input type="number" name="items[{index}][qty]" class="form-control text-center qty-input" value="1" min="1" required oninput="calculateRow(this)">
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text bg-light small">Rp</span>
                <input type="number" name="items[{index}][harga]" class="form-control text-end price-input" required oninput="calculateRow(this)">
            </div>
        </td>
        <td class="text-end fw-bold row-subtotal pe-2">0</td>
        <td class="text-end pe-0">
            <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-row" onclick="removeRow(this)" title="Hapus Baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

@endsection

@push('scripts')
<script>
    let rowIndex = 0;

    function addItem() {
        const template = document.getElementById('rowTemplate').innerHTML;
        const newRow = template.replace(/{index}/g, rowIndex++);
        document.getElementById('itemsBody').insertAdjacentHTML('beforeend', newRow);
        
        // Initialize the new row calculation
        const insertedRow = document.getElementById('itemsBody').lastElementChild;
        // Optionally trigger price update if we set a default item, but here we don't.
    }

    function removeRow(btn) {
        if (document.querySelectorAll('#itemsBody tr').length > 1) {
            btn.closest('tr').remove();
            calculateTotal();
        } else {
            alert('Minimal harus ada satu item barang.');
        }
    }

    function updateItemPrice(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            const price = option.dataset.price;
            const row = select.closest('tr');
            row.querySelector('.price-input').value = price;
            calculateRow(select);
        }
    }

    function calculateRow(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        
        row.querySelector('.row-subtotal').textContent = new Intl.NumberFormat('id-ID').format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('#itemsBody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            total += qty * price;
        });

        document.getElementById('totalSubtotal').textContent = new Intl.NumberFormat('id-ID').format(total);
        
        const ppn = parseFloat(document.getElementById('ppnInput').value) || 0;
        const grandTotal = total + ppn;
        
        document.getElementById('grandTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }

    document.getElementById('addItemBtn').addEventListener('click', addItem);
    document.getElementById('ppnInput').addEventListener('input', calculateTotal);

    // Add initial row on load
    addItem();
</script>
@endpush
