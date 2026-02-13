@extends('layouts.app')

@section('title', 'Kartu Stok - ' . $item->nama_item)

@section('content')
<div class="container-fluid px-4">
    <!-- Print Header -->
    <div class="d-none d-print-block mb-4">
        <div class="d-flex align-items-center justify-content-center mb-4 pb-3 border-bottom">
            <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" style="height: 80px; margin-right: 20px;">
            <div class="text-start">
                <h2 class="m-0 fw-bold text-uppercase" style="font-size: 24px;">CV. KARYA JAYA HOSEINDO</h2>
                <p class="mb-0" style="font-size: 14px;">Specialist Hose Hydraulic & Industrial</p>
                <p class="mb-0 fw-bold" style="font-size: 18px; margin-top: 5px;">KARTU STOK BARANG</p>
            </div>
        </div>
        <div class="row mb-4 border-bottom pb-3">
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="fw-bold" width="100">Nama Item</td>
                        <td class="text-primary fw-bold">: {{ $item->nama_item }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kode Item</td>
                        <td>: {{ $item->kode_item }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Kategori</td>
                        <td>: {{ $item->jenis ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="fw-bold" width="120">Stok Saat Ini</td>
                        <td class="{{ $item->stok <= $item->stok_minimum ? 'text-danger' : 'text-success' }} fw-bold">: {{ number_format($item->stok, 0, ',', '.') }} {{ $item->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Periode</td>
                        <td>: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} - {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Dicetak Tgl</td>
                        <td>: {{ date('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 mt-3 d-print-none">
        <div>
            <h2 class="mb-0 fw-bold text-dark">Kartu Stok</h2>
            <p class="text-muted mb-0">History pergerakan barang</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('items.index') }}" class="text-decoration-none">Item Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kartu Stok</li>
            </ol>
        </nav>
    </div>

    <!-- Item Summary (Screen Only) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold text-primary mb-1">{{ $item->nama_item }}</h4>
                    <div class="d-flex gap-3 text-muted">
                        <span><i class="bi bi-upc-scan me-1"></i> {{ $item->kode_item }}</span>
                        <span><i class="bi bi-tag me-1"></i> {{ $item->jenis ?? '-' }}</span>
                        <span><i class="bi bi-archive me-1"></i> {{ $item->merk ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="bg-light rounded-3 p-3 d-inline-block text-start" style="min-width: 200px;">
                        <small class="text-muted d-block">Stok Saat Ini</small>
                        <h2 class="mb-0 fw-bold {{ $item->stok <= $item->stok_minimum ? 'text-danger' : 'text-success' }}">
                            {{ number_format($item->stok, 0, ',', '.') }}
                            <small class="fs-6 text-muted fw-normal">{{ $item->satuan }}</small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Filter Section -->
        <div class="col-lg-3 d-print-none">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 rounded-top-4">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-funnel me-2"></i>Filter Periode</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('items.history', $item->id) }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Dari Tanggal</label>
                            <input type="date" class="form-control bg-light" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-bold">Sampai Tanggal</label>
                            <input type="date" class="form-control bg-light" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i> Tampilkan
                            </button>
                            <a href="{{ route('items.history', $item->id) }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="summary-stats">
                        <div class="mb-3">
                            <label class="small text-muted text-uppercase fw-bold">Total Masuk (Periode Ini)</label>
                            <div class="fs-5 fw-bold text-success">+ {{ number_format($total_masuk, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <label class="small text-muted text-uppercase fw-bold">Total Keluar (Periode Ini)</label>
                            <div class="fs-5 fw-bold text-danger">- {{ number_format($total_keluar, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-0 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-print-none" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Cetak
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary text-uppercase small fw-bold">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="py-3">No. Referensi</th>
                                <th class="py-3">Keterangan</th>
                                <th class="text-end py-3 text-success">Masuk</th>
                                <th class="text-end py-3 text-danger">Keluar</th>
                                <th class="text-end px-4 py-3">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($history as $row)
                                <tr>
                                    <td class="px-4 text-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $row->no_referensi ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $row->keterangan }}</div>
                                        @if($row->supplier)
                                            <small class="text-muted"><i class="bi bi-truck me-1"></i> {{ $row->supplier->nama }}</small>
                                        @elseif($row->customer)
                                            <small class="text-muted"><i class="bi bi-person me-1"></i> {{ $row->customer->nama }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end fw-medium text-success">
                                        {{ $row->masuk > 0 ? number_format($row->masuk, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end fw-medium text-danger">
                                        {{ $row->keluar > 0 ? number_format($row->keluar, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="text-end px-4 fw-bold text-primary">
                                        {{ number_format($row->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Belum ada riwayat transaksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        @page { size: A4; margin: 2cm; }
        body { background-color: white !important; -webkit-print-color-adjust: exact; }
        
        /* Hide global navigation elements */
        .navbar, .sidebar, .sidebar-overlay, .d-print-none { display: none !important; }
        
        /* Reset main container layout */
        main.col-md-9, main.col-lg-10 { 
            width: 100% !important; 
            margin: 0 !important; 
            padding: 0 !important; 
            flex: 0 0 100% !important; 
            max-width: 100% !important; 
        }
        
        .d-print-block { display: block !important; }
        
        .card { border: none !important; box-shadow: none !important; background: none !important; }
        .card-header { background: none !important; border-bottom: 2px solid #000 !important; padding-left: 0 !important; padding-right: 0 !important; }
        .card-body { padding: 0 !important; }
        
        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th { background-color: #f8f9fa !important; color: #6c757d !important; border-bottom: 2px solid #dee2e6 !important; font-weight: bold !important; text-transform: uppercase !important; font-size: 11px !important; }
        .table td { border-bottom: 1px solid #dee2e6 !important; padding: 6px 8px !important; font-size: 11px !important; }
        
        /* Keep badges and colors for print as per request */
        /* .badge { border: 1px solid #000 !important; } */
        
        /* Layout adjustments */
        .col-lg-9 { width: 100% !important; flex: 0 0 100%; max-width: 100%; }
        
        /* Hide URL/Page info if possible (browser dependent) */
        a[href]:after { content: none !important; }
    }
</style>
@endsection
