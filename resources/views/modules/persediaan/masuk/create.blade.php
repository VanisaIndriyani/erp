@extends('layouts.app')

@section('title', 'Item Masuk')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Item Masuk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.masuk') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<form action="{{ route('persediaan.storeMasuk') }}" method="POST" id="itemMasukForm">
    @csrf
    
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
                            <input type="text" class="form-control bg-light" name="no_transaksi" value="{{ $no_transaksi }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="keterangan" rows="2" placeholder="Contoh: Stok awal tahun..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Gudang Tujuan <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select" name="gudang_tujuan" required>
                                <option value="UTAMA">GUDANG UTAMA</option>
                                <option value="GUDANG A">GUDANG A</option>
                                <option value="GUDANG B">GUDANG B</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">Akun Persediaan</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="akun_id">
                                <option value="">-- Pilih Akun (Opsional) --</option>
                                @foreach($akuns as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Akun lawan untuk jurnal otomatis (jika ada).</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted">User</label>
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
            <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Detail Item</h6>
            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" onclick="addRow()">
                <i class="bi bi-plus-circle me-1"></i> Tambah Baris
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="detailTable">
                    <thead class="table-light">
                        <tr class="text-secondary text-uppercase small">
                            <th class="text-center" width="50">No</th>
                            <th width="250">Item Barang</th>
                            <th>Keterangan Item</th>
                            <th class="text-center" width="120">Qty</th>
                            <th class="text-center" width="100">Satuan</th>
                            <th class="text-end" width="180">Harga Satuan</th>
                            <th class="text-end" width="200">Subtotal</th>
                            <th class="text-center" width="50"><i class="bi bi-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <!-- Dynamic Rows -->
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="6" class="text-end fw-bold py-3">Total Nilai Transaksi :</td>
                            <td class="text-end fw-bold fs-5 text-primary py-3" id="totalValue">0,00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Empty State (Hidden by default, shown via JS if needed) -->
            <div id="emptyState" class="text-center py-5 d-none">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" width="64" class="opacity-25 mb-2">
                <p class="text-muted small">Belum ada item yang ditambahkan.</p>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">Tambah Item Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Footer Action Buttons -->
    <div class="card card-dashboard shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('persediaan.masuk') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</form>

<!-- Template for Item Row -->
<template id="rowTemplate">
    <tr>
        <td class="text-center row-number text-muted">1</td>
        <td>
            <select class="form-select form-select-sm item-select border-0 bg-light" name="details[INDEX][item_id]" required onchange="updateItemDetails(this)">
                <option value="">-- Pilih Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" 
                            data-nama="{{ $item->nama_item }}" 
                            data-jenis="{{ $item->jenis }}" 
                            data-satuan="{{ $item->satuan }}" 
                            data-harga="{{ $item->harga_pokok }}">
                        {{ $item->kode_item }} - {{ $item->nama_item }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm border-0" name="details[INDEX][keterangan_item]" placeholder="Keterangan opsional">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm text-center item-qty" name="details[INDEX][qty]" value="1" min="0.01" step="0.01" required oninput="calculateRow(this)">
        </td>
        <td>
            <input type="text" class="form-control form-control-sm text-center border-0 bg-white item-satuan" name="details[INDEX][satuan]" readonly>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text border-0 bg-light">Rp</span>
                <input type="number" class="form-control text-end item-harga" name="details[INDEX][harga]" value="0" min="0" step="0.01" required oninput="calculateRow(this)">
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm text-end border-0 bg-white fw-bold item-total" readonly value="0,00">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeRow(this)"><i class="bi bi-x-circle-fill"></i></button>
        </td>
    </tr>
</template>

<script>
    let rowIndex = 0;

    function addRow() {
        const template = document.getElementById('rowTemplate');
        const clone = template.content.cloneNode(true);
        const tbody = document.querySelector('#detailTable tbody');
        
        // Update Names with Index
        clone.querySelectorAll('[name*="INDEX"]').forEach(el => {
            el.name = el.name.replace('INDEX', rowIndex);
        });

        tbody.appendChild(clone);
        updateRowNumbers();
        rowIndex++;
        
        // Hide empty state
        document.getElementById('emptyState').classList.add('d-none');
    }

    function removeRow(btn) {
        const row = btn.closest('tr');
        row.remove();
        updateRowNumbers();
        calculateTotals();
        
        // Show empty state if no rows
        if(document.querySelectorAll('#detailTable tbody tr').length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
        }
    }

    function updateRowNumbers() {
        document.querySelectorAll('#detailTable tbody tr').forEach((row, index) => {
            row.querySelector('.row-number').textContent = index + 1;
        });
    }

    function updateItemDetails(select) {
        const row = select.closest('tr');
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            row.querySelector('.item-satuan').value = option.dataset.satuan || '';
            
            // Set default price from master data if current price is 0
            const currentPrice = parseFloat(row.querySelector('.item-harga').value) || 0;
            if (currentPrice === 0) {
                 row.querySelector('.item-harga').value = option.dataset.harga || 0;
            }
        } else {
            row.querySelector('.item-satuan').value = '';
            row.querySelector('.item-harga').value = 0;
        }
        calculateRow(select);
    }

    function calculateRow(element) {
        const row = element.closest('tr');
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
        const total = qty * harga;
        
        row.querySelector('.item-total').value = total.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        calculateTotals();
    }

    function calculateTotals() {
        let totalValue = 0;

        document.querySelectorAll('#detailTable tbody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
            totalValue += (qty * harga);
        });

        document.getElementById('totalValue').textContent = totalValue.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Add initial row
    document.addEventListener('DOMContentLoaded', function() {
        addRow();
    });
</script>
@endsection
