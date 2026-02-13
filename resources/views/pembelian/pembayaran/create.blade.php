@extends('layouts.app')

@section('title', 'Tambah Pembayaran Hutang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0">Tambah Pembayaran Hutang</h1>
        <p class="text-muted small mb-0">Input pembayaran hutang ke supplier</p>
    </div>
    <a href="{{ route('pembelian.pembayaran.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
</div>

<form action="{{ route('pembelian.pembayaran.store') }}" method="POST" id="paymentForm">
    @csrf
    <div class="row g-4">
        <!-- Left Column: Payment Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Informasi Pembayaran</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Transaksi</label>
                        <input type="text" class="form-control bg-light" name="no_bayar" value="{{ $no_bayar }}" readonly>
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
                        <select class="form-select" name="supplier_id" id="supplierSelect" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Pilih supplier untuk melihat tagihan.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Cara Bayar <span class="text-danger">*</span></label>
                        <select class="form-select" name="cara_bayar" required>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Cek/Giro">Cek/Giro</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Akun Kas/Bank</label>
                        <select class="form-select" name="akun_id">
                            <option value="">-- Pilih Akun --</option>
                            @foreach($akuns as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">No Referensi</label>
                        <input type="text" class="form-control" name="no_referensi" placeholder="Contoh: No Bukti Transfer">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Unpaid Invoices -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Daftar Invoice Belum Lunas</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="alert alert-info border-0 bg-info-subtle text-info mb-3 d-flex align-items-center" id="infoAlert">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <span>Silahkan pilih supplier terlebih dahulu untuk menampilkan daftar tagihan.</span>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" id="invoiceTable">
                            <thead class="bg-light text-uppercase small fw-bold text-muted">
                                <tr>
                                    <th>No Invoice</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="text-end">Total Tagihan</th>
                                    <th class="text-end">Sisa Tagihan</th>
                                    <th class="text-center" style="width: 50px;">Aksi</th>
                                    <th class="text-end" style="width: 200px;">Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-arrow-left-circle fs-2 d-block mb-2"></i>
                                        Pilih supplier di panel kiri
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light border-top">
                                <tr>
                                    <td colspan="5" class="text-end fw-bold py-3 fs-5">Total Pembayaran</td>
                                    <td class="text-end fw-bold py-3 fs-5 text-success" id="displayTotalBayar">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-lg btn-success px-5 rounded-pill shadow-sm" id="submitBtn" disabled>
                            <i class="bi bi-check-circle me-2"></i> Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('supplierSelect').addEventListener('change', function() {
        const supplierId = this.value;
        const tbody = document.getElementById('invoiceTableBody');
        const infoAlert = document.getElementById('infoAlert');
        const submitBtn = document.getElementById('submitBtn');
        
        // Reset Total
        document.getElementById('displayTotalBayar').textContent = 'Rp 0';
        submitBtn.disabled = true;

        if (!supplierId) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-left-circle fs-2 d-block mb-2"></i>
                        Pilih supplier di panel kiri
                    </td>
                </tr>`;
            infoAlert.innerHTML = '<i class="bi bi-info-circle-fill me-2 fs-5"></i><span>Silahkan pilih supplier terlebih dahulu.</span>';
            infoAlert.className = 'alert alert-info border-0 bg-info-subtle text-info mb-3 d-flex align-items-center';
            return;
        }

        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Memuat tagihan...</p></td></tr>';

        fetch(`{{ url('pembelian/pembayaran/unpaid') }}/${supplierId}`)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-2"></i>
                                <p class="text-muted mb-0">Tidak ada tagihan yang belum lunas untuk supplier ini.</p>
                            </td>
                        </tr>`;
                    infoAlert.innerHTML = '<i class="bi bi-check-circle-fill me-2 fs-5"></i><span>Semua tagihan lunas!</span>';
                    infoAlert.className = 'alert alert-success border-0 bg-success-subtle text-success mb-3 d-flex align-items-center';
                } else {
                    infoAlert.innerHTML = '<i class="bi bi-exclamation-circle-fill me-2 fs-5"></i><span>Silahkan input nominal pembayaran untuk invoice yang ingin dibayar.</span>';
                    infoAlert.className = 'alert alert-warning border-0 bg-warning-subtle text-warning-emphasis mb-3 d-flex align-items-center';
                    
                    data.forEach((inv, index) => {
                        const row = `
                            <tr>
                                <td class="fw-bold">
                                    ${inv.no_invoice}
                                    <input type="hidden" name="details[${index}][pembelian_id]" value="${inv.id}">
                                </td>
                                <td><span class="badge bg-light text-dark border">${inv.jatuh_tempo}</span></td>
                                <td class="text-end text-muted">Rp ${new Intl.NumberFormat('id-ID').format(inv.total)}</td>
                                <td class="text-end fw-bold text-danger">Rp ${new Intl.NumberFormat('id-ID').format(inv.sisa)}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="payFull(${index}, ${inv.sisa})" title="Bayar Penuh">
                                        Full
                                    </button>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input type="number" class="form-control text-end border-start-0 payment-input" 
                                            name="details[${index}][jumlah_bayar]" 
                                            value="0" 
                                            min="0" 
                                            max="${inv.sisa}"
                                            oninput="validateInput(this, ${inv.sisa}); updateTotal()">
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-danger">Gagal mengambil data tagihan. Silahkan coba lagi.</td></tr>';
            });
    });

    function payFull(index, amount) {
        const inputs = document.querySelectorAll('.payment-input');
        if (inputs[index]) {
            inputs[index].value = amount;
            // Add highlight effect
            inputs[index].classList.add('bg-success-subtle');
            setTimeout(() => inputs[index].classList.remove('bg-success-subtle'), 500);
            updateTotal();
        }
    }

    function validateInput(input, max) {
        let value = parseFloat(input.value);
        if (value > max) {
            input.value = max;
        }
        if (value < 0) {
            input.value = 0;
        }
    }

    function updateTotal() {
        const inputs = document.querySelectorAll('.payment-input');
        let total = 0;
        let hasPayment = false;
        
        inputs.forEach(input => {
            const val = parseFloat(input.value || 0);
            total += val;
            if (val > 0) hasPayment = true;
        });
        
        document.getElementById('displayTotalBayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        
        // Enable/Disable submit button based on total payment
        const submitBtn = document.getElementById('submitBtn');
        if (total > 0) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    // Intercept form submit for JSON response handling if needed, or let standard submit handle it
    // Using standard submit as Controller redirects on success
</script>
@endpush
