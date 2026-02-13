@extends('layouts.app')

@section('title', 'Edit Item Keluar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Item Keluar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.keluar') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form action="{{ route('persediaan.updateKeluar', $data->id) }}" method="POST" id="itemKeluarForm">
    @csrf
    @method('PUT')
    
    <!-- Header Section -->
    <div class="card card-dashboard mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Informasi Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">No Transaksi</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control bg-light" name="no_transaksi" value="{{ $data->no_transaksi }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="tanggal" value="{{ $data->tanggal }}" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="keterangan" rows="2" placeholder="Contoh: Garansi Kerusakan, Expired, dll">{{ $data->keterangan }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Dari Gudang <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="gudang_asal">
                                <option value="UTAMA" {{ $data->gudang_asal == 'UTAMA' ? 'selected' : '' }}>GUDANG UTAMA</option>
                                <option value="GUDANG A" {{ $data->gudang_asal == 'GUDANG A' ? 'selected' : '' }}>GUDANG A</option>
                                <option value="GUDANG B" {{ $data->gudang_asal == 'GUDANG B' ? 'selected' : '' }}>GUDANG B</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">User Buat</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control-plaintext fw-bold" value="{{ $data->user->name ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">User Edit</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control-plaintext fw-bold" value="{{ Auth::user()->name ?? 'Admin' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Table Section -->
    <div class="card card-dashboard mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Detail Item</h6>
                <small class="text-muted">Edit item yang akan dikeluarkan</small>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="addRow()">
                <i class="bi bi-plus-lg me-1"></i> Tambah Item
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="detailTable">
                    <thead class="table-light">
                        <tr class="text-secondary small fw-bold">
                            <th class="text-center" width="50">No</th>
                            <th width="35%">Item Barang</th>
                            <th width="25%">Keterangan</th>
                            <th class="text-center" width="20%">Jumlah Keluar</th>
                            <th class="text-center" width="10%"><i class="bi bi-gear"></i></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <!-- Dynamic Rows -->
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-end fw-bold py-3 text-secondary">Total Item Keluar :</td>
                            <td class="text-center fw-bold fs-5 text-primary py-3" id="totalQty">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
             <!-- Empty State -->
             <div id="emptyState" class="text-center py-5 d-none">
                <div class="mb-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-box-seam text-muted fs-1"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-bold">Belum ada item</h6>
                <p class="text-muted small mb-3">Silakan tambahkan item barang yang akan dikeluarkan</p>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4" onclick="addRow()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Item
                </button>
            </div>
        </div>
    </div>

    <!-- Footer Action Buttons -->
    <div class="card card-dashboard shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Pastikan data yang diinput sudah benar sebelum disimpan.
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('persediaan.keluar') }}" class="btn btn-light border px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm fw-bold"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

</form>

<!-- Template Row -->
<template id="rowTemplate">
    <tr class="item-row">
        <td class="text-center row-number text-muted fw-bold"></td>
        <td>
            <div class="d-flex flex-column">
                <select class="form-select item-select border-1" name="details[INDEX][item_id]" onchange="itemChanged(this)" required>
                    <option value="">-- Pilih Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" 
                                data-satuan="{{ $item->satuan }}" 
                                data-stok="{{ $item->stok }}"
                                data-nama="{{ $item->nama_item }}">
                            {{ $item->kode_item }} - {{ $item->nama_item }}
                        </option>
                    @endforeach
                </select>
                <div class="mt-1 d-flex align-items-center">
                    <span class="badge bg-light text-dark border me-2 stock-badge">
                        <i class="bi bi-box me-1"></i> Stok: <span class="stock-val">-</span>
                    </span>
                    <input type="hidden" class="item-stok-input" value="0">
                    <input type="hidden" name="details[INDEX][satuan]" class="item-satuan-input">
                </div>
            </div>
        </td>
        <td>
            <input type="text" class="form-control item-keterangan" name="details[INDEX][keterangan]" placeholder="Contoh: Rusak bagian handle...">
        </td>
        <td>
            <div class="input-group has-validation">
                <input type="number" class="form-control text-center item-qty fw-bold" name="details[INDEX][qty]" step="0.01" min="0.01" placeholder="0" oninput="validateQty(this)" required>
                <span class="input-group-text bg-light item-satuan-text text-muted">-</span>
                <div class="invalid-feedback">
                    Jumlah melebihi stok tersedia!
                </div>
            </div>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-link text-danger p-0 btn-remove" onclick="removeRow(this)" title="Hapus Baris">
                <i class="bi bi-trash fs-5"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    let rowIndex = 0;
    const existingDetails = @json($data->details);

    document.addEventListener('DOMContentLoaded', function() {
        if (existingDetails.length > 0) {
            existingDetails.forEach(detail => {
                addRow(detail);
            });
        } else {
            addRow();
        }
    });

    function addRow(detail = null) {
        const template = document.getElementById('rowTemplate');
        const clone = template.content.cloneNode(true);
        const tbody = document.querySelector('#detailTable tbody');
        
        // Generate unique ID for this row index
        const currentIndex = rowIndex;
        
        clone.querySelectorAll('[name*="INDEX"]').forEach(el => {
            el.name = el.name.replace('INDEX', currentIndex);
        });

        tbody.appendChild(clone);
        
        const newRow = tbody.lastElementChild;
        const select = newRow.querySelector('select');
        const qtyInput = newRow.querySelector('.item-qty');
        const ketInput = newRow.querySelector('.item-keterangan');

        if (detail) {
            select.value = detail.item_id;
            qtyInput.value = detail.qty;
            ketInput.value = detail.keterangan || '';
            
            // Trigger change to load metadata
            itemChanged(select);
            
            // Adjust stock for existing item (Current Stock + Owned Qty)
            const stockInput = newRow.querySelector('.item-stok-input');
            const stockVal = newRow.querySelector('.stock-val');
            const satuanInput = newRow.querySelector('.item-satuan-input');
            
            const currentStock = parseFloat(stockInput.value) || 0;
            const ownedQty = parseFloat(detail.qty) || 0;
            const adjustedStock = currentStock + ownedQty;
            
            stockInput.value = adjustedStock;
            stockVal.textContent = adjustedStock + ' ' + satuanInput.value;
            
            // Update badge color based on adjusted stock
             const stockBadge = newRow.querySelector('.stock-badge');
             if (adjustedStock <= 0) {
                stockBadge.className = 'badge bg-danger text-white border me-2 stock-badge';
            } else if (adjustedStock < 10) {
                stockBadge.className = 'badge bg-warning text-dark border me-2 stock-badge';
            } else {
                stockBadge.className = 'badge bg-success text-white border me-2 stock-badge';
            }

            qtyInput.max = adjustedStock;
            validateQty(qtyInput);
        } else {
            if(select) select.focus();
        }

        updateRowNumbers();
        rowIndex++;
        
        document.getElementById('emptyState').classList.add('d-none');
        document.getElementById('detailTable').classList.remove('d-none');
        calculateTotals();
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        
        // Animation before remove
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
            updateRowNumbers();
            calculateTotals();
            
            if (document.querySelectorAll('#detailTable tbody tr').length === 0) {
                 document.getElementById('emptyState').classList.remove('d-none');
                 document.getElementById('detailTable').classList.add('d-none');
            }
        }, 200);
    }

    function updateRowNumbers() {
        document.querySelectorAll('#detailTable tbody tr').forEach((row, index) => {
            row.querySelector('.row-number').textContent = index + 1;
        });
    }

    function itemChanged(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        
        const stockBadge = row.querySelector('.stock-badge');
        const stockVal = row.querySelector('.stock-val');
        const stockInput = row.querySelector('.item-stok-input');
        const satuanInput = row.querySelector('.item-satuan-input');
        const satuanText = row.querySelector('.item-satuan-text');
        const qtyInput = row.querySelector('.item-qty');
        
        if (option.value) {
            const stok = parseFloat(option.dataset.stok);
            const satuan = option.dataset.satuan || '-';
            
            stockVal.textContent = stok + ' ' + satuan;
            stockInput.value = stok;
            satuanInput.value = satuan;
            satuanText.textContent = satuan;
            
            // Visual feedback for low stock
            if (stok <= 0) {
                stockBadge.className = 'badge bg-danger text-white border me-2 stock-badge';
            } else if (stok < 10) {
                stockBadge.className = 'badge bg-warning text-dark border me-2 stock-badge';
            } else {
                stockBadge.className = 'badge bg-success text-white border me-2 stock-badge';
            }
            
            qtyInput.max = stok;
            validateQty(qtyInput);
        } else {
            stockVal.textContent = '-';
            stockInput.value = 0;
            satuanInput.value = '';
            satuanText.textContent = '-';
            stockBadge.className = 'badge bg-light text-dark border me-2 stock-badge';
            qtyInput.removeAttribute('max');
        }
    }

    function validateQty(input) {
        const row = input.closest('tr');
        const qty = parseFloat(input.value) || 0;
        const stock = parseFloat(row.querySelector('.item-stok-input').value) || 0;
        
        if (qty > stock) {
            input.classList.add('is-invalid');
            // input.value = stock; // Optional: auto-correct
        } else {
            input.classList.remove('is-invalid');
        }
        
        calculateTotals();
    }

    function calculateTotals() {
        let totalQty = 0;
        
        document.querySelectorAll('#detailTable tbody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            totalQty += qty;
        });

        // Format number nicely
        document.getElementById('totalQty').textContent = new Intl.NumberFormat('id-ID').format(totalQty);
    }
    
    // Validate form before submit
    document.getElementById('itemKeluarForm').addEventListener('submit', function(e) {
        let valid = true;
        const rows = document.querySelectorAll('#detailTable tbody tr');
        
        if (rows.length === 0) {
            alert('Harap tambahkan minimal satu item!');
            e.preventDefault();
            return;
        }

        rows.forEach(row => {
            const qtyInput = row.querySelector('.item-qty');
            const qty = parseFloat(qtyInput.value) || 0;
            const stock = parseFloat(row.querySelector('.item-stok-input').value) || 0;
            const itemSelect = row.querySelector('.item-select');
            
            if (!itemSelect.value) {
                valid = false;
                itemSelect.classList.add('is-invalid');
            } else {
                itemSelect.classList.remove('is-invalid');
            }

            if (qty <= 0 || qty > stock) {
                valid = false;
                qtyInput.classList.add('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Mohon periksa kembali inputan Anda. Pastikan semua field terisi dan jumlah tidak melebihi stok.');
        }
    });
</script>
@endsection