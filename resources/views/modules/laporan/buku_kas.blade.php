@extends('layouts.app')

@section('title', 'Laporan Buku Kas')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-print-none">
        <h1 class="h2">Laporan Buku Kas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak / PDF
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportTableToExcel('tableBukuKas', 'Laporan_Buku_Kas')">
                <i class="bi bi-file-excel"></i> Excel
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
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">LAPORAN BUKU KAS & BANK</p>
                @if(isset($start_date) && isset($end_date))
                <p class="mb-0 small mt-1">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.buku_kas') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $end_date }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" id="tableBukuKas">
                    <thead class="bg-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>No Ref</th>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th class="text-end">Masuk (Debit)</th>
                        <th class="text-end">Keluar (Kredit)</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalMasuk = 0;
                        $totalKeluar = 0;
                    @endphp
                    @forelse($data as $d)
                        @php
                            // For Asset accounts (Kas/Bank), Debit is Increase (Masuk), Credit is Decrease (Keluar)
                            $masuk = $d->debit;
                            $keluar = $d->kredit;
                            
                            $totalMasuk += $masuk;
                            $totalKeluar += $keluar;
                        @endphp
                        <tr>
                            <td>{{ $d->jurnal->tanggal }}</td>
                            <td>{{ $d->jurnal->no_ref }}</td>
                            <td>{{ $d->akun->nama_akun }}</td>
                            <td>{{ $d->jurnal->keterangan }}</td>
                            <td class="text-end text-success">{{ $masuk > 0 ? number_format($masuk, 0, ',', '.') : '-' }}</td>
                            <td class="text-end text-danger">{{ $keluar > 0 ? number_format($keluar, 0, ',', '.') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada transaksi kas/bank pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Total Mutasi</th>
                        <th class="text-end text-success">{{ number_format($totalMasuk, 0, ',', '.') }}</th>
                        <th class="text-end text-danger">{{ number_format($totalKeluar, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Surplus / (Defisit) Periode Ini</th>
                        <th colspan="2" class="text-end fw-bold {{ ($totalMasuk - $totalKeluar) >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
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
        .d-print-block { display: block !important; }
        .card { border: none !important; box-shadow: none !important; background: none !important; }
        .card-header, .card-footer { display: none !important; }
        body { background-color: white !important; -webkit-print-color-adjust: exact; }
        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 8px !important; }
        .badge { border: 1px solid #000 !important; color: #000 !important; }
        
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
