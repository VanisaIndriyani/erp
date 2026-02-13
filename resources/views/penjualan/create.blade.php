@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Tambah Penjualan</h1>
        <p class="text-muted small mb-0">Input transaksi penjualan baru</p>
    </div>
    <a href="{{ route('penjualan.transaksi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<form action="{{ route('penjualan.transaksi.store') }}" method="POST" id="transactionForm">
    @csrf
    
    <div class="row g-4">
        <!-- Left Column: Transaction Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-cart3 me-2 text-primary"></i>Rincian Barang</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <!-- Item Selection Area -->
                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold">Pilih Barang</label>
                                <select class="form-select" id="itemSelect">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" 
                                                data-code="{{ $item->kode_item }}" 
                                                data-name="{{ $item->nama_item }}" 
                                                data-price="{{ $item->harga_jual }}"
                                                data-stock="{{ $item->stok }}"
                                                data-unit="{{ $item->satuan }}">
                                            {{ $item->kode_item }} - {{ $item->nama_item }} (Stok: {{ $item->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Qty</label>
                                <input type="number" class="form-control" id="qtyInput" value="1" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">Rp</span>
                                    <input type="number" class="form-control border-start-0" id="priceInput" readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary w-100" id="addItemBtn">
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" id="cartTable">
                            <thead class="bg-light text-uppercase small fw-bold text-muted">
                                <tr>
                                    <th>Barang</th>
                                    <th class="text-center" style="width: 100px;">Qty</th>
                                    <th class="text-end" style="width: 150px;">Harga</th>
                                    <th class="text-end" style="width: 150px;">Subtotal</th>
                                    <th class="text-center" style="width: 50px;"><i class="bi bi-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-2 d-block mb-2"></i>
                                        Belum ada barang dipilih
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body px-4 py-4">
                    <label class="form-label fw-bold small text-muted">Keterangan Tambahan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan untuk transaksi ini..."></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary & Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Info Transaksi</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Transaksi</label>
                        <input type="text" class="form-control bg-light fw-bold" name="no_transaksi" value="{{ $no_transaksi }}" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Customer <span class="text-danger">*</span></label>
                        <select class="form-select" name="customer_id" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No PO (Opsional)</label>
                        <input type="text" class="form-control" name="no_po" placeholder="Nomor Purchase Order">
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="text-white-50 text-uppercase small fw-bold mb-3">Total Pembayaran</h6>
                    <h1 class="display-5 fw-bold mb-0" id="displayGrandTotal">Rp 0</h1>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg btn-success shadow-sm rounded-pill" id="submitBtn" disabled>
                    <i class="bi bi-check-circle me-2"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    const itemSelect = document.getElementById('itemSelect');
    const priceInput = document.getElementById('priceInput');
    const qtyInput = document.getElementById('qtyInput');
    const addItemBtn = document.getElementById('addItemBtn');
    const cartTableBody = document.getElementById('cartTableBody');
    const emptyRow = document.getElementById('emptyRow');
    const displayGrandTotal = document.getElementById('displayGrandTotal');
    const submitBtn = document.getElementById('submitBtn');

    // Store cart items
    let cart = [];

    // Handle Item Selection
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const stock = parseFloat(selectedOption.getAttribute('data-stock'));
            
            priceInput.value = price;
            qtyInput.value = 1;
            qtyInput.max = stock;
            
            if(stock <= 0) {
                 alert('Stok barang habis!');
                 this.value = '';
                 priceInput.value = '';
            }
        } else {
            priceInput.value = '';
            qtyInput.value = 1;
            qtyInput.removeAttribute('max');
        }
    });

    // Add Item to Cart
    addItemBtn.addEventListener('click', function() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        
        if (!selectedOption.value) {
            alert('Silahkan pilih barang terlebih dahulu');
            return;
        }

        const itemId = selectedOption.value;
        const itemCode = selectedOption.getAttribute('data-code');
        const itemName = selectedOption.getAttribute('data-name');
        const price = parseFloat(priceInput.value);
        const qty = parseInt(qtyInput.value);
        const stock = parseFloat(selectedOption.getAttribute('data-stock'));

        if (isNaN(qty) || qty <= 0) {
            alert('Jumlah harus lebih dari 0');
            return;
        }

        if (qty > stock) {
            alert('Jumlah melebihi stok tersedia (' + stock + ')');
            return;
        }

        // Check if item already exists
        const existingItemIndex = cart.findIndex(item => item.id === itemId);

        if (existingItemIndex > -1) {
            // Update quantity
            const newQty = cart[existingItemIndex].qty + qty;
            if (newQty > stock) {
                alert('Total jumlah melebihi stok tersedia');
                return;
            }
            cart[existingItemIndex].qty = newQty;
            cart[existingItemIndex].subtotal = newQty * price;
        } else {
            // Add new item
            cart.push({
                id: itemId,
                code: itemCode,
                name: itemName,
                price: price,
                qty: qty,
                subtotal: qty * price,
                max_stock: stock
            });
        }

        renderCart();
        resetInput();
    });

    function resetInput() {
        itemSelect.value = '';
        priceInput.value = '';
        qtyInput.value = 1;
        qtyInput.removeAttribute('max');
    }

    function renderCart() {
        cartTableBody.innerHTML = '';

        if (cart.length === 0) {
            cartTableBody.appendChild(emptyRow);
            submitBtn.disabled = true;
            displayGrandTotal.textContent = 'Rp 0';
            return;
        }

        let grandTotal = 0;

        cart.forEach((item, index) => {
            grandTotal += item.subtotal;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <div class="small text-muted">${item.code}</div>
                    <input type="hidden" name="items[${index}][item_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][harga]" value="${item.price}">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-center" 
                        name="items[${index}][qty]" 
                        value="${item.qty}" 
                        min="1" 
                        max="${item.max_stock}"
                        onchange="updateQty(${index}, this.value)">
                </td>
                <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</td>
                <td class="text-end fw-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeItem(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            cartTableBody.appendChild(row);
        });

        displayGrandTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
        submitBtn.disabled = false;
    }

    window.updateQty = function(index, newQty) {
        newQty = parseInt(newQty);
        const item = cart[index];

        if (isNaN(newQty) || newQty < 1) {
            newQty = 1;
        }
        
        if (newQty > item.max_stock) {
            alert('Jumlah melebihi stok tersedia (' + item.max_stock + ')');
            newQty = item.max_stock;
        }

        cart[index].qty = newQty;
        cart[index].subtotal = newQty * item.price;
        renderCart();
    };

    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    // Form Validation before submit
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('Harap pilih minimal satu barang');
        }
    });
</script>
@endpush
