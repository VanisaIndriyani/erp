@extends('layouts.app')

@section('title', 'Laporan Piutang')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-print-none">
        <h1 class="h2">Laporan Piutang</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-file-pdf"></i> PDF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportTableToExcel('tablePiutang', 'Laporan_Piutang')">
                    <i class="bi bi-file-excel"></i> Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="height: 80px; margin-right: 20px;">
            <div class="text-start">
                <h2 class="m-0 fw-bold text-uppercase" style="font-size: 24px;">CV. KARYA JAYA HOSEINDO</h2>
                <p class="mb-0" style="font-size: 14px;">Specialist Hose Hydraulic & Industrial</p>
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">LAPORAN PIUTANG BEREDAR</p>
                @if(isset($start_date) && isset($end_date))
                <p class="mb-0 small mt-1">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row d-print-none">
        <!-- Filter Section -->
        <div class="col-md-9 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.piutang') }}" method="GET" id="filterForm">
                        <input type="hidden" name="type" id="reportTypeInput" value="{{ request('type', 'beredar') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Periode Tanggal</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="start_date" value="{{ $start_date }}">
                                    <span class="input-group-text bg-light">s/d</span>
                                    <input type="date" class="form-control" name="end_date" value="{{ $end_date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Customer</label>
                                <select class="form-select" name="customer_id">
                                    <option value="">Semua Customer</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="d-grid gap-2 w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i> Tampilkan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('laporan.piutang') }}'">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Report Type Selection -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-file-text me-2"></i>Jenis Laporan</h6>
                </div>
                <div class="list-group list-group-flush rounded-bottom-4">
                    <label class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer {{ request('type', 'beredar') == 'beredar' ? 'active-type' : '' }}">
                        <input class="form-check-input me-3" type="radio" name="report_type_selector" value="beredar" {{ request('type', 'beredar') == 'beredar' ? 'checked' : '' }} onchange="updateReportType('beredar')">
                        <div>
                            <div class="fw-bold">Piutang Beredar</div>
                            <div class="small text-muted">Sisa tagihan per customer</div>
                        </div>
                    </label>
                    <label class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer {{ request('type') == 'pembayaran' ? 'active-type' : '' }}">
                        <input class="form-check-input me-3" type="radio" name="report_type_selector" value="pembayaran" {{ request('type') == 'pembayaran' ? 'checked' : '' }} onchange="updateReportType('pembayaran')">
                        <div>
                            <div class="fw-bold">Riwayat Pembayaran</div>
                            <div class="small text-muted">History pembayaran piutang</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    @if(isset($data) && $data->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle" id="tablePiutang">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">No Transaksi</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Jatuh Tempo</th>
                            <th class="py-3 text-end">Total Tagihan</th>
                            <th class="py-3 text-end">Sudah Dibayar</th>
                            <th class="py-3 text-end px-4">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grandTotal = 0; 
                            $grandPaid = 0;
                            $grandSisa = 0;
                        @endphp
                        @foreach($data as $key => $item)
                        @php
                            // Calculate values safely
                            $total = $item->total ?? 0;
                            $sisa = $item->sisa_tagihan ?? 0;
                            $paid = $total - $sisa;
                            
                            $grandTotal += $total;
                            $grandPaid += $paid;
                            $grandSisa += $sisa;

                            // Calculate Jatuh Tempo
                            $jatuhTempo = $item->customer && $item->customer->jatuh_tempo ? 
                                \Carbon\Carbon::parse($item->tanggal)->addDays($item->customer->jatuh_tempo)->format('d/m/Y') : '-';
                        @endphp
                        <tr>
                            <td class="px-4">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $item->customer->nama ?? '-' }}</td>
                            <td>{{ $item->no_transaksi }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $jatuhTempo }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td class="text-end text-success">Rp {{ number_format($paid, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-danger px-4">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="bg-light fw-bold fs-6">
                            <td colspan="5" class="text-end px-4 py-3">GRAND TOTAL</td>
                            <td class="text-end py-3">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td class="text-end py-3 text-success">Rp {{ number_format($grandPaid, 0, ',', '.') }}</td>
                            <td class="text-end py-3 text-danger px-4">Rp {{ number_format($grandSisa, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @elseif(isset($data))
    <div class="alert alert-info text-center rounded-4 shadow-sm">
        <i class="bi bi-info-circle me-2"></i> Tidak ada data piutang beredar untuk periode/filter ini.
    </div>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function updateReportType(type) {
        document.getElementById('reportTypeInput').value = type;
        // Optional: Submit form immediately or let user click Tampilkan
        // document.getElementById('filterForm').submit();
        
        // Update visual selection
        document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active-type'));
        event.target.closest('.list-group-item').classList.add('active-type');
    }

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
    .active-type {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd !important;
    }
    .cursor-pointer {
        cursor: pointer;
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
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        .table th, .table td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
        }
        /* Ensure background colors print */
        .bg-light {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    }
    .print-header {
        display: none;
    }
</style>
@endsection
