@extends('layouts.app')

@section('title', 'Tambah Transfer Item')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Transfer Item</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('persediaan.transfer') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form action="{{ route('persediaan.storeTransfer') }}" method="POST" id="transferForm">
    @csrf
    
    <div class="row">
        <!-- Informasi Transaksi -->
        <div class="col-md-4 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header fw-bold">
                    <i class="bi bi-info-circle me-2"></i> Informasi Transaksi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">No Transaksi</label>
                        <input type="text" class="form-control form-control-sm bg-light" name="no_transaksi" value="{{ $no_transaksi }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Dari Gudang (Asal)</label>
                        <select class="form-select form-select-sm" name="gudang_asal" required>
                            <option value="UTAMA">UTAMA</option>
                            <option value="GUDANG A">GUDANG A</option>
                            <option value="GUDANG B">GUDANG B</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ke Gudang (Tujuan)</label>
                        <select class="form-select form-select-sm" name="gudang_tujuan" required>
                            <option value="GUDANG A">GUDANG A</option>
                            <option value="UTAMA">UTAMA</option>
                            <option value="GUDANG B">GUDANG B</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">No Surat Jalan (SJ)</label>
                        <input type="text" class="form-control form-control-sm" name="no_sj" placeholder="Contoh: SJ-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">PIC (Penanggung Jawab)</label>
                        <input type="text" class="form-control form-control-sm" name="pic" placeholder="Nama PIC">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan</label>
                        <textarea class="form-control form-control-sm" name="keterangan" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Item -->
        <div class="col-md-8 mb-4">
            <div class="card card-dashboard h-100">
                <div class="card-header fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam me-2"></i> Detail Item</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                        <i class="bi bi-plus-lg"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="detailTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 35%">Item</th>
                                    <th style="width: 15%">Stok Tersedia</th>
                                    <th style="width: 15%">Qty Transfer</th>
                                    <th style="width: 10%">Satuan</th>
                                    <th style="width: 20%">Ket. Item</th>
                                    <th style="width: 5%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows added dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div id="emptyState" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-up-circle display-4"></i>
                        <p class="mt-2">Klik tombol "Tambah Item" untuk memulai.</p>
                    </div>
                </div>
                <div class="card-footer text-end bg-light">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let rowCount = 0;
    const items = @json($items);

    function addRow() {
        rowCount++;
        
        // Hide empty state
        document.getElementById('emptyState').style.display = 'none';
        
        const row = `
            <tr id="row_${rowCount}">
                <td class="text-center align-middle row-number"></td>
                <td>
                    <select class="form-select form-select-sm item-select" name="details[${rowCount}][item_id]" onchange="updateItemDetails(this, ${rowCount})" required>
                        <option value="">-- Pilih Item --</option>
                        ${items.map(item => `<option value="${item.id}" data-stok="${item.stok}" data-satuan="${item.satuan || 'PCS'}">${item.kode_item} - ${item.nama_item}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm bg-light item-stok" readonly tabindex="-1">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty" name="details[${rowCount}][qty]" min="0.01" step="0.01" required placeholder="0" oninput="validateQty(this, ${rowCount})">
                    <div class="invalid-feedback">Melebihi stok!</div>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm bg-light item-satuan" name="details[${rowCount}][satuan]" readonly tabindex="-1">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="details[${rowCount}][keterangan]" placeholder="Ket...">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="removeRow(${rowCount})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        document.querySelector('#detailTable tbody').insertAdjacentHTML('beforeend', row);
        updateRowNumbers();
    }

    function updateItemDetails(select, rowId) {
        const option = select.options[select.selectedIndex];
        const row = document.getElementById(`row_${rowId}`);
        
        if (option.value) {
            row.querySelector('.item-stok').value = option.dataset.stok;
            row.querySelector('.item-satuan').value = option.dataset.satuan;
            // Re-validate in case qty was already entered
            validateQty(row.querySelector('.item-qty'), rowId);
        } else {
            row.querySelector('.item-stok').value = '';
            row.querySelector('.item-satuan').value = '';
        }
    }

    function validateQty(input, rowId) {
        const row = document.getElementById(`row_${rowId}`);
        const stok = parseFloat(row.querySelector('.item-stok').value) || 0;
        const qty = parseFloat(input.value) || 0;
        
        if (qty > stok) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    function removeRow(rowId) {
        document.getElementById(`row_${rowId}`).remove();
        updateRowNumbers();
        
        // Show empty state if no rows
        if (document.querySelectorAll('#detailTable tbody tr').length === 0) {
            document.getElementById('emptyState').style.display = 'block';
        }
    }

    function updateRowNumbers() {
        document.querySelectorAll('#detailTable tbody tr').forEach((row, index) => {
            row.querySelector('.row-number').textContent = index + 1;
        });
    }

    // Validation before submit
    document.getElementById('transferForm').addEventListener('submit', function(e) {
        let valid = true;
        
        if (document.querySelectorAll('#detailTable tbody tr').length === 0) {
            alert('Minimal satu item harus ditambahkan.');
            e.preventDefault();
            return;
        }

        const gudangAsal = document.querySelector('select[name="gudang_asal"]').value;
        const gudangTujuan = document.querySelector('select[name="gudang_tujuan"]').value;

        if (gudangAsal === gudangTujuan) {
             alert('Gudang Asal dan Gudang Tujuan tidak boleh sama!');
             e.preventDefault();
             return;
        }

        document.querySelectorAll('#detailTable tbody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const stok = parseFloat(row.querySelector('.item-stok').value) || 0;
            
            if (qty > stok) {
                valid = false;
                row.querySelector('.item-qty').classList.add('is-invalid');
            }
        });

        if (!valid) {
            alert('Terdapat item dengan jumlah melebihi stok tersedia. Mohon periksa kembali.');
            e.preventDefault();
        }
    });
</script>
@endsection
