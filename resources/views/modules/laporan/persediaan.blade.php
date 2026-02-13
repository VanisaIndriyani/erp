@extends('layouts.app')

@section('title', 'Laporan Persediaan')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-3">
        <h2 class="mb-0 fw-bold text-dark">Laporan Persediaan</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Persediaan</li>
            </ol>
        </nav>
    </div>

    @php
        $totalItems = $data->count();
        $totalValue = $data->sum(function($item) { return $item->stok * $item->harga_pokok; });
        $lowStockCount = $data->filter(function($item) { return $item->stok <= $item->stok_minimum; })->count();
    @endphp

    <!-- Summary Cards -->
    <div class="row g-4 mb-4 d-print-none">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white-50 fw-medium">Total Barang</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($totalItems, 0, ',', '.') }} <span class="fs-6 fw-normal">Item</span></h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-box-seam fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white-50 fw-medium">Total Nilai Persediaan</p>
                            <h3 class="mb-0 fw-bold">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-currency-dollar fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 {{ $lowStockCount > 0 ? 'bg-danger' : 'bg-info' }} text-white h-100 position-relative overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1 text-white-50 fw-medium">Stok Menipis / Habis</p>
                            <h3 class="mb-0 fw-bold">{{ $lowStockCount }} <span class="fs-6 fw-normal">Item</span></h3>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-exclamation-triangle fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
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
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">LAPORAN PERSEDIAAN BARANG</p>
                <p class="mb-0 small mt-1">Per Tanggal: {{ date('d F Y H:i') }}</p>
            </div>
        </div>
        
        <!-- Print Summary -->
        <div class="row mb-3 mt-4 border-bottom pb-3">
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="fw-bold" width="150">Total Item</td>
                        <td>: {{ number_format($totalItems, 0, ',', '.') }} Item</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Nilai Aset</td>
                        <td>: Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6 text-end align-self-end">
                <small class="text-muted">Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</small>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-table me-2 text-primary"></i>Data Persediaan Barang</h5>
            <div class="d-flex gap-2 d-print-none">
                <button type="button" class="btn btn-outline-primary rounded-pill px-3" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-3" onclick="exportTableToExcel('stockTable', 'Laporan_Stock')">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="stockTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary text-uppercase small fw-bold border-0 rounded-start-2">Kode Item</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold border-0">Nama Item</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold border-0">Kategori</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold border-0">Merk</th>
                            <th class="py-3 text-center text-secondary text-uppercase small fw-bold border-0">Satuan</th>
                            <th class="py-3 text-end text-secondary text-uppercase small fw-bold border-0">Stok Fisik</th>
                            <th class="px-4 py-3 text-end text-secondary text-uppercase small fw-bold border-0 rounded-end-2">Nilai Aset</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($data as $item)
                            @php 
                                $nilai = $item->stok * $item->harga_pokok;
                                $isLowStock = $item->stok <= $item->stok_minimum;
                            @endphp
                            <tr class="{{ $isLowStock ? 'table-danger bg-opacity-10' : '' }}">
                                <td class="px-4 fw-medium text-secondary">{{ $item->kode_item }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->nama_item }}</div>
                                    @if($isLowStock)
                                        <span class="badge bg-danger rounded-pill mt-1" style="font-size: 0.7rem;">Stok Menipis (Min: {{ $item->stok_minimum }})</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $item->jenis }}</td>
                                <td class="text-muted">{{ $item->merk }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $item->satuan }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($item->stok, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 text-end fw-medium text-dark">
                                    Rp {{ number_format($nilai, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    Tidak ada data barang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="6" class="text-end fw-bold py-3 text-uppercase text-secondary">Total Nilai Aset</td>
                            <td class="px-4 text-end fw-bold py-3 text-primary fs-6">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3 rounded-bottom-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i> 
                Menampilkan total {{ $totalItems }} item barang. Data diperbarui realtime.
            </small>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableID, filename = ''){
        // Get the table element
        var originalTable = document.getElementById(tableID);
        
        if (!originalTable) {
            alert('Tidak ada data laporan untuk diexport.');
            return;
        }

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
        #stockTable { width: 100% !important; border-collapse: collapse !important; }
        #stockTable th, #stockTable td { border: 1px solid #000 !important; padding: 6px 8px !important; }
        
        /* Remove borders from summary table */
        .table-borderless td, .table-borderless th { border: none !important; }
        
        .badge { border: 1px solid #000 !important; color: #000 !important; }
        .text-primary { color: #000 !important; }
        .text-secondary { color: #000 !important; }
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
