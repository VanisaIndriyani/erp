@extends('layouts.app')

@section('title', 'Tambah Pembayaran Piutang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Tambah Pembayaran Piutang</h1>
        <p class="text-muted small mb-0">Input pembayaran piutang dari pelanggan</p>
    </div>
    <a href="{{ route('penjualan.pembayaran.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<form action="{{ route('penjualan.pembayaran.store') }}" method="POST" id="paymentForm">
    @csrf
    
    <div class="row g-4">
        <!-- Left Column: Payment Details -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Info Pembayaran</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Transaksi</label>
                        <input type="text" class="form-control bg-light fw-bold" name="no_bayar" value="{{ $no_bayar }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Pelanggan <span class="text-danger">*</span></label>
                        <select class="form-select" name="customer_id" id="customerSelect" required>
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Akun Penerima <span class="text-danger">*</span></label>
                        <select class="form-select" name="akun_id" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Metode Pembayaran</label>
                        <select class="form-select" name="cara_bayar" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Cek/Giro">Cek/Giro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Referensi / Cek</label>
                        <input type="text" class="form-control" name="no_ref" placeholder="Contoh: TRF-123456">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Invoices & Summary -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Daftar Tagihan (Invoice)</h5>
                    <span class="badge bg-primary-subtle text-primary" id="invoiceCount">0 Invoice</span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive rounded-3 border" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0" id="invoiceTable">
                            <thead class="bg-light text-uppercase small fw-bold text-muted sticky-top">
                                <tr>
                                    <th class="px-3">No Invoice</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="text-end">Sisa Tagihan</th>
                                    <th class="text-center" style="width: 120px;">Potongan</th>
                                    <th class="text-center" style="width: 150px;">Bayar</th>
                                    <th class="text-center" style="width: 50px;">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="invoiceTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-arrow-left-circle fs-2 d-block mb-2"></i>
                                        Silahkan pilih pelanggan terlebih dahulu
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-1">Total Pembayaran</h6>
                            <h1 class="display-5 fw-bold mb-0" id="displayTotalBayar">Rp 0</h1>
                        </div>
                        <div class="col-md-6 text-md-end border-start border-white-50 ps-md-4 mt-3 mt-md-0">
                            <div class="mb-2">
                                <span class="text-white-50 small text-uppercase fw-bold d-block">Total Potongan</span>
                                <span class="fs-4 fw-bold" id="displayTotalPotongan">Rp 0</span>
                            </div>
                            <button type="submit" class="btn btn-light text-primary fw-bold shadow-sm rounded-pill w-100" id="submitBtn" disabled>
                                <i class="bi bi-check-circle me-2"></i> Simpan Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Format Currency
    const formatCurrency = (num) => new Intl.NumberFormat('id-ID').format(num);
    const parseCurrency = (str) => parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;

    const customerSelect = document.getElementById('customerSelect');
    const invoiceTableBody = document.getElementById('invoiceTableBody');
    const invoiceCount = document.getElementById('invoiceCount');
    const displayTotalBayar = document.getElementById('displayTotalBayar');
    const displayTotalPotongan = document.getElementById('displayTotalPotongan');
    const submitBtn = document.getElementById('submitBtn');
    const checkAll = document.getElementById('checkAll');

    let invoices = [];

    // Load Invoices when Customer Selected
    customerSelect.addEventListener('change', function() {
        const customerId = this.value;
        
        if (!customerId) {
            invoiceTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-left-circle fs-2 d-block mb-2"></i>
                        Silahkan pilih pelanggan terlebih dahulu
                    </td>
                </tr>
            `;
            invoiceCount.textContent = '0 Invoice';
            resetSummary();
            return;
        }

        invoiceTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Memuat data invoice...</div></td></tr>';

        fetch(`{{ url('penjualan/pembayaran/unpaid') }}/${customerId}`)
            .then(response => response.json())
            .then(data => {
                invoices = data;
                renderInvoices();
            })
            .catch(error => {
                console.error('Error:', error);
                invoiceTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-danger"><i class="bi bi-exclamation-circle fs-2 d-block mb-2"></i>Gagal memuat data</td></tr>';
            });
    });

    function renderInvoices() {
        invoiceTableBody.innerHTML = '';
        invoiceCount.textContent = invoices.length + ' Invoice';

        if (invoices.length === 0) {
            invoiceTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                        Tidak ada tagihan yang belum lunas
                    </td>
                </tr>
            `;
            resetSummary();
            return;
        }

        invoices.forEach((inv, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-3">
                    <div class="fw-bold">${inv.no_invoice}</div>
                    <small class="text-muted">${inv.tanggal}</small>
                    <input type="hidden" name="details[${index}][penjualan_id]" value="${inv.id}">
                </td>
                <td>
                    <span class="badge bg-light text-dark border">${inv.jatuh_tempo}</span>
                </td>
                <td class="text-end fw-bold">Rp ${formatCurrency(inv.sisa)}</td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-end" 
                        name="details[${index}][potongan]" 
                        value="0" 
                        min="0" 
                        max="${inv.sisa}"
                        onchange="calculateRow(${index}, 'potongan', this.value)">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-end payment-input" 
                        name="details[${index}][jumlah_bayar]" 
                        value="0" 
                        min="0" 
                        max="${inv.sisa}"
                        data-sisa="${inv.sisa}"
                        onchange="calculateRow(${index}, 'bayar', this.value)">
                </td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input row-checkbox" 
                        onchange="toggleRow(${index}, this.checked)">
                </td>
            `;
            invoiceTableBody.appendChild(row);
        });
        
        checkAll.disabled = false;
        checkAll.checked = false;
    }

    // Check All Functionality
    checkAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach((cb, index) => {
            cb.checked = this.checked;
            toggleRow(index, this.checked);
        });
    });

    window.toggleRow = function(index, isChecked) {
        const row = invoiceTableBody.children[index];
        const payInput = row.querySelector('.payment-input');
        const sisa = parseFloat(payInput.getAttribute('data-sisa'));

        if (isChecked) {
            payInput.value = sisa; // Default pay full amount
        } else {
            payInput.value = 0;
        }
        calculateTotal();
    };

    window.calculateRow = function(index, type, value) {
        const row = invoiceTableBody.children[index];
        const payInput = row.querySelector('input[name="details[' + index + '][jumlah_bayar]"]');
        const potInput = row.querySelector('input[name="details[' + index + '][potongan]"]');
        const checkbox = row.querySelector('.row-checkbox');
        
        let pay = parseFloat(payInput.value) || 0;
        let pot = parseFloat(potInput.value) || 0;
        const sisa = parseFloat(payInput.getAttribute('data-sisa'));

        // Validate max amount
        if (pay + pot > sisa) {
            alert('Total bayar + potongan tidak boleh melebihi sisa tagihan');
            if (type === 'bayar') {
                pay = sisa - pot;
                payInput.value = pay;
            } else {
                pot = sisa - pay;
                potInput.value = pot;
            }
        }

        // Auto check if amount > 0
        if (pay > 0) {
            checkbox.checked = true;
        } else if (pay === 0 && pot === 0) {
            checkbox.checked = false;
        }

        calculateTotal();
    };

    function calculateTotal() {
        let totalBayar = 0;
        let totalPotongan = 0;
        let hasPayment = false;

        const rows = invoiceTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const payInput = row.querySelector('input[name="details[' + index + '][jumlah_bayar]"]');
            const potInput = row.querySelector('input[name="details[' + index + '][potongan]"]');
            
            if (payInput && potInput) {
                totalBayar += parseFloat(payInput.value) || 0;
                totalPotongan += parseFloat(potInput.value) || 0;
                
                if (parseFloat(payInput.value) > 0) hasPayment = true;
            }
        });

        displayTotalBayar.textContent = 'Rp ' + formatCurrency(totalBayar);
        displayTotalPotongan.textContent = 'Rp ' + formatCurrency(totalPotongan);
        
        submitBtn.disabled = !hasPayment;
    }

    function resetSummary() {
        displayTotalBayar.textContent = 'Rp 0';
        displayTotalPotongan.textContent = 'Rp 0';
        submitBtn.disabled = true;
        checkAll.disabled = true;
        checkAll.checked = false;
    }

    // Prevent submit if no payment
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            alert('Silahkan masukkan jumlah pembayaran minimal pada satu invoice');
        }
    });
</script>
@endpush
