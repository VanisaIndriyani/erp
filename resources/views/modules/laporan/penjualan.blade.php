@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid px-4">
    <div class="print-header">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="height: 80px; margin-right: 20px;">
            <div class="text-start">
                <h2 class="m-0 fw-bold text-uppercase" style="font-size: 24px;">CV. KARYA JAYA HOSEINDO</h2>
                <p class="mb-0" style="font-size: 14px;">Specialist Hose Hydraulic & Industrial</p>
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">LAPORAN PENJUALAN</p>
                @if(isset($start_date) && isset($end_date))
                <p class="mb-0 small mt-1">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 mt-3 d-print-none">
        <h2 class="mb-0 fw-bold text-dark">Laporan Penjualan</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Penjualan</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4 d-print-none">
        <!-- Filter Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-funnel me-2"></i>Filter Laporan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('laporan.penjualan') }}" method="GET" id="filterForm">
                        
                        <!-- Periode -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Periode Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-range"></i></span>
                                <input type="date" class="form-control bg-light border-start-0 ps-0" name="start_date" value="{{ $start_date }}">
                                <span class="input-group-text bg-light border-start-0 border-end-0">s/d</span>
                                <input type="date" class="form-control bg-light border-start-0 ps-0" name="end_date" value="{{ $end_date }}">
                            </div>
                        </div>

                        <!-- No Transaksi -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase">No. Transaksi</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light" placeholder="Dari Nomor" name="no_transaksi_start">
                                        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light" placeholder="Sampai Nomor" name="no_transaksi_end">
                                        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pelanggan -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase">Pelanggan</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <select class="form-select bg-light" name="customer_id">
                                        <option value="">-- Semua Pelanggan --</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                     <select class="form-select bg-light" disabled>
                                        <option value="">-- Sampai Pelanggan --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Gudang -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase">Gudang</label>
                                <select class="form-select bg-light" name="gudang">
                                    <option value="">-- Semua Gudang --</option>
                                    <option value="UTM">UTM</option>
                                    <option value="Gudang 2">Gudang 2</option>
                                </select>
                            </div>

                            <!-- User -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase">Sales / User</label>
                                <select class="form-select bg-light" name="user_id">
                                    <option value="">-- Semua User --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="pajak_only" id="pajakOnly">
                            <label class="form-check-label" for="pajakOnly">Hanya tampilkan transaksi dengan Pajak</label>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Report Type & Actions -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Jenis Laporan</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="list-group list-group-radio mb-auto gap-2">
                        <label class="list-group-item d-flex gap-3 rounded-3 border">
                            <input class="form-check-input flex-shrink-0" type="radio" name="report_type" value="rekap" checked>
                            <span>
                                <strong class="d-block">Laporan Penjualan Rekap</strong>
                                <small class="text-muted">Ringkasan penjualan per transaksi</small>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3 rounded-3 border">
                            <input class="form-check-input flex-shrink-0" type="radio" name="report_type" value="detail">
                            <span>
                                <strong class="d-block">Laporan Penjualan Detail</strong>
                                <small class="text-muted">Rincian item setiap transaksi penjualan</small>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3 rounded-3 border">
                            <input class="form-check-input flex-shrink-0" type="radio" name="report_type" value="harian">
                            <span>
                                <strong class="d-block">Laporan Penjualan Harian</strong>
                                <small class="text-muted">Total penjualan per hari</small>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3 rounded-3 border">
                            <input class="form-check-input flex-shrink-0" type="radio" name="report_type" value="per_pelanggan">
                            <span>
                                <strong class="d-block">Laporan Per Pelanggan</strong>
                                <small class="text-muted">Analisa penjualan per pelanggan</small>
                            </span>
                        </label>
                         <label class="list-group-item d-flex gap-3 rounded-3 border">
                            <input class="form-check-input flex-shrink-0" type="radio" name="report_type" value="per_sales">
                            <span>
                                <strong class="d-block">Laporan Per Sales</strong>
                                <small class="text-muted">Analisa penjualan per sales</small>
                            </span>
                        </label>
                    </div>

                    <div class="mt-4 d-grid gap-2">
                        <button type="submit" form="filterForm" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                            <i class="bi bi-eye me-2"></i> Preview Laporan
                        </button>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-dark w-100 rounded-pill" onclick="window.print()">
                                    <i class="bi bi-printer me-2"></i> Print
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success w-100 rounded-pill" onclick="exportTableToExcel('tablePenjualan', 'Laporan_Penjualan')">
                                    <i class="bi bi-file-excel me-2"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section -->
    @if(isset($data) && $data->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white py-3 border-0 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-table me-2"></i>Hasil Laporan</h5>
            <span class="badge bg-primary rounded-pill">{{ $data->count() }} Transaksi Ditemukan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" id="tablePenjualan">
                    <thead class="bg-light text-uppercase small fw-bold text-muted">
                        <tr>
                            <th class="px-4 py-3">No. Invoice</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Gudang</th>
                            <th class="py-3">Status</th>
                            <th class="text-end px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td class="px-4 fw-medium text-primary">{{ $item->no_invoice ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light rounded-circle text-primary me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    {{ $item->customer->nama ?? 'Umum' }}
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $item->gudang ?? 'UTM' }}</span></td>
                            <td>
                                @if($item->status_pembayaran == 'lunas')
                                    <span class="badge bg-success-subtle text-success">Lunas</span>
                                @elseif($item->status_pembayaran == 'piutang')
                                    <span class="badge bg-danger-subtle text-danger">Belum Lunas</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($item->status_pembayaran) }}</span>
                                @endif
                            </td>
                            <td class="text-end px-4 fw-bold">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end py-3 text-uppercase text-muted small">Grand Total</td>
                            <td class="text-end px-4 py-3 text-primary fs-6">Rp {{ number_format($data->sum('total'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @elseif(request()->has('start_date'))
    <div class="alert alert-info border-0 shadow-sm rounded-4 mt-4 d-flex align-items-center">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Tidak ada data ditemukan.</strong> Silakan coba filter dengan periode atau kriteria lain.
        </div>
    </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = ''){
        // Get the table element
        var originalTable = document.getElementById(tableID);
        
        if (!originalTable) {
            alert('Tidak ada data laporan untuk diexport. Silakan tampilkan laporan terlebih dahulu.');
            return;
        }

        var tableClone = originalTable.cloneNode(true);

        // Get Period Text from the print header if available
        var periodText = '';
        var periodElement = document.querySelector('.print-header .small.mt-1');
        if(periodElement && periodElement.textContent.includes('Periode:')){
            periodText = periodElement.textContent.trim();
        }

        // Create a header row
        var headerRow1 = document.createElement('tr');
        var headerCell1 = document.createElement('th');
        headerCell1.colSpan = originalTable.rows[0].cells.length;
        headerCell1.style.textAlign = 'center';
        headerCell1.style.fontSize = '18pt';
        headerCell1.style.fontWeight = 'bold';
        headerCell1.innerHTML = 'CV. KARYA JAYA HOSEINDO';
        headerRow1.appendChild(headerCell1);

        var headerRow2 = document.createElement('tr');
        var headerCell2 = document.createElement('th');
        headerCell2.colSpan = originalTable.rows[0].cells.length;
        headerCell2.style.textAlign = 'center';
        headerCell2.style.fontSize = '12pt';
        headerCell2.innerHTML = 'Specialist Hose Hydraulic & Industrial';
        headerRow2.appendChild(headerCell2);

        var headerRow3 = document.createElement('tr');
        var headerCell3 = document.createElement('th');
        headerCell3.colSpan = originalTable.rows[0].cells.length;
        headerCell3.style.textAlign = 'center';
        headerCell3.style.fontSize = '14pt';
        headerCell3.style.fontWeight = 'bold';
        headerCell3.innerHTML = filename.replace(/_/g, ' ').toUpperCase() + (periodText ? '<br>' + periodText : '');
        headerRow3.appendChild(headerCell3);
        
        // Add empty row for spacing
        var emptyRow = document.createElement('tr');
        var emptyCell = document.createElement('th');
        emptyCell.colSpan = originalTable.rows[0].cells.length;
        emptyRow.appendChild(emptyCell);

        // Insert at the beginning of the table
        if(tableClone.tHead){
            tableClone.tHead.insertBefore(emptyRow, tableClone.tHead.firstChild);
            tableClone.tHead.insertBefore(headerRow3, tableClone.tHead.firstChild);
            tableClone.tHead.insertBefore(headerRow2, tableClone.tHead.firstChild);
            tableClone.tHead.insertBefore(headerRow1, tableClone.tHead.firstChild);
        } else {
            var tHead = tableClone.createTHead();
            tHead.appendChild(headerRow1);
            tHead.appendChild(headerRow2);
            tHead.appendChild(headerRow3);
            tHead.appendChild(emptyRow);
        }
        
        // Convert table to worksheet
        var wb = XLSX.utils.table_to_book(tableClone, {sheet: "Sheet1"});
        
        // Generate filename
        filename = filename ? filename + '.xlsx' : 'Export.xlsx';
        
        // Save file
        XLSX.writeFile(wb, filename);
    }
</script>

<style>
    .list-group-radio .list-group-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .list-group-radio .list-group-item:hover {
        background-color: #f8f9fa;
        border-color: var(--primary-color);
    }
    .list-group-radio .form-check-input:checked + span {
        color: var(--primary-color);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(14, 165, 164, 0.25);
    }
    @media print {
        body {
            background-color: white !important;
        }
        .navbar, .sidebar, .btn-toolbar, .btn, footer, .d-print-none, .no-print, nav {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            display: none !important;
        }
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .table th, .table td {
            color: #000 !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
    }
    .print-header {
        display: none;
    }
</style>
@endsection