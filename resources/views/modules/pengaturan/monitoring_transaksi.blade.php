@extends('layouts.app')

@section('title', 'Monitoring Transaksi')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-print-none">
        <h1 class="h2">Monitoring Transaksi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak / PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportTableToExcel('tableMonitoringTransaksi', 'Monitoring_Transaksi_Sparepart_Hose')">
                <i class="bi bi-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="height: 80px; margin-right: 20px;">
            <div class="text-start">
                <h2 class="m-0 fw-bold text-uppercase" style="font-size: 24px;">CV. KARYA JAYA HOSEINDO</h2>
                <p class="mb-0" style="font-size: 14px;">Specialist Hose Hydraulic & Industrial</p>
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">MONITORING TRANSAKSI PENJUALAN</p>
                <p class="mb-0 small mt-1">Kategori: {{ implode(', ', $kategori) }}</p>
                @if(isset($start_date) && isset($end_date))
                <p class="mb-0 small mt-1">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Monitoring</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('pengaturan.monitoring_transaksi') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $end_date }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Kategori Item</label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kategori[]" value="Sparepart" id="catSparepart" {{ in_array('Sparepart', $kategori) ? 'checked' : '' }}>
                            <label class="form-check-label" for="catSparepart">Sparepart</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kategori[]" value="Hose" id="catHose" {{ in_array('Hose', $kategori) ? 'checked' : '' }}>
                            <label class="form-check-label" for="catHose">Hose</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" id="tableMonitoringTransaksi">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-3 py-3">Tanggal</th>
                            <th class="py-3">No Transaksi</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Item Detail</th>
                            <th class="text-center py-3">Qty</th>
                            <th class="text-end py-3">Harga Jual</th>
                            <th class="text-end px-3 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @forelse($data as $tr)
                            @foreach($tr->details as $detail)
                                @php 
                                    $grandTotal += $detail->subtotal;
                                @endphp
                                <tr>
                                    <td class="px-3">{{ \Carbon\Carbon::parse($tr->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $tr->no_transaksi }}</div>
                                    </td>
                                    <td>{{ $tr->customer->nama ?? '-' }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $detail->item->nama_item }}</div>
                                        <span class="badge bg-light text-dark border">{{ $detail->item->jenis }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $detail->qty }} {{ $detail->item->satuan }}</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="text-end px-3 fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data penjualan Sparepart/Hose pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="6" class="text-end py-3">GRAND TOTAL</td>
                            <td class="text-end px-3 py-3 text-primary fs-6">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = ''){
        // Get the table element
        var originalTable = document.getElementById(tableID);
        var tableClone = originalTable.cloneNode(true);

        // Get additional info (Period, Date, Category) from print header
        var periodText = '';
        var periodElements = document.querySelectorAll('.print-header .small.mt-1');
        periodElements.forEach(function(el) {
            if (periodText) periodText += '<br>';
            periodText += el.textContent.trim();
        });

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
    @media print {
        @page { size: landscape; margin: 1cm; }
        .navbar, .sidebar, .btn-toolbar, .btn, footer, .d-print-none, .no-print, nav {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card { border: none !important; box-shadow: none !important; background: none !important; }
        .card-header, .card-footer { display: none !important; }
        body { background-color: white !important; -webkit-print-color-adjust: exact; }
        
        /* Table Styles */
        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 6px 8px !important; color: #000 !important; }
        
        .badge { border: 1px solid #000 !important; color: #000 !important; }
        .bg-light { background-color: #f0f0f0 !important; }
        
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
    }
    
    .print-header {
        display: none;
    }
</style>
@endsection