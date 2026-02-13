@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold text-dark">Dashboard Overview</h1>
        <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name ?? 'Administrator' }}! Berikut ringkasan aktivitas bisnis Anda.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer me-1"></i> Print</button>
            <button type="button" class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i> Export</button>
        </div>
    </div>
</div>

<!-- Financial Stats Row -->
<div class="row g-3 mb-4">
    <!-- Total Penjualan -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Total Penjualan</h6>
                </div>
                <h3 class="mb-0 fw-bold text-dark">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                <div class="mt-2 mb-0 text-muted small">
                    <span class="text-success fw-bold"><i class="bi bi-arrow-up"></i> +12%</span> dari bulan lalu
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pembelian -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-bag fs-4"></i>
                    </div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Total Pembelian</h6>
                </div>
                <h3 class="mb-0 fw-bold text-dark">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h3>
                <div class="mt-2 mb-0 text-muted small">
                    <span class="text-danger fw-bold"><i class="bi bi-arrow-down"></i> -5%</span> pengeluaran
                </div>
            </div>
        </div>
    </div>

    <!-- Piutang (Receivables) -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Piutang Beredar</h6>
                </div>
                <h3 class="mb-0 fw-bold text-dark">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
                <div class="mt-2 mb-0">
                    <a href="{{ route('laporan.piutang') }}" class="text-decoration-none small text-primary fw-semibold">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hutang (Payables) -->
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
            <div class="card-body position-relative">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-3 p-3 me-3">
                        <i class="bi bi-credit-card fs-4"></i>
                    </div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Hutang Beredar</h6>
                </div>
                <h3 class="mb-0 fw-bold text-dark">Rp {{ number_format($totalHutang, 0, ',', '.') }}</h3>
                <div class="mt-2 mb-0">
                    <a href="{{ route('laporan.hutang') }}" class="text-decoration-none small text-danger fw-semibold">Lihat Detail <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Operational Stats & Chart Row -->
<div class="row g-4 mb-4">
    <!-- Left Column: Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">Analisis Transaksi</h5>
                <select class="form-select form-select-sm w-auto border-0 bg-light">
                    <option>12 Bulan Terakhir</option>
                    <option>6 Bulan Terakhir</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="card-body">
                <div style="height: 350px; position: relative;">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Operational Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-bold">Ringkasan Data</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <!-- Total Items -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="icon-sm bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Total Barang</h6>
                                <small class="text-muted">{{ number_format($totalStok) }} Unit dalam Stok</small>
                            </div>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill fs-6 px-3">{{ number_format($totalItems) }}</span>
                    </div>

                    <!-- Total Suppliers -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="icon-sm bg-secondary bg-opacity-10 text-secondary rounded-circle p-2 me-3">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Supplier</h6>
                                <small class="text-muted">Mitra Aktif</small>
                            </div>
                        </div>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill fs-6 px-3">{{ number_format($totalSuppliers) }}</span>
                    </div>

                    <!-- Total Customers -->
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-0">
                        <div class="d-flex align-items-center">
                            <div class="icon-sm bg-purple bg-opacity-10 text-purple rounded-circle p-2 me-3" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Pelanggan</h6>
                                <small class="text-muted">Terdaftar</small>
                            </div>
                        </div>
                        <span class="badge bg-purple bg-opacity-10 text-purple rounded-pill fs-6 px-3" style="color: #6f42c1; background-color: rgba(111, 66, 193, 0.1);">{{ number_format($totalCustomers) }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3 text-center">
                <a href="{{ route('items.index') }}" class="btn btn-light w-100 fw-semibold text-primary">Kelola Master Data</a>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .icon-sm {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('transactionChart').getContext('2d');
        
        // Gradient for Penjualan
        let gradientPenjualan = ctx.createLinearGradient(0, 0, 0, 400);
        gradientPenjualan.addColorStop(0, 'rgba(14, 165, 164, 0.5)'); // Primary color (Tosca)
        gradientPenjualan.addColorStop(1, 'rgba(14, 165, 164, 0.0)');

        // Gradient for Pembelian
        let gradientPembelian = ctx.createLinearGradient(0, 0, 0, 400);
        gradientPembelian.addColorStop(0, 'rgba(239, 68, 68, 0.5)'); // Accent color (Red)
        gradientPembelian.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        new Chart(ctx, {
            type: 'line', // Changed to line for better trend visualization
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Penjualan',
                        data: @json($chartPenjualan),
                        backgroundColor: gradientPenjualan,
                        borderColor: '#0ea5a4', // Tosca
                        borderWidth: 2,
                        tension: 0.4, // Smooth curves
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0ea5a4',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Pembelian',
                        data: @json($chartPembelian),
                        backgroundColor: gradientPembelian,
                        borderColor: '#ef4444', // Red
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 1000000, // Agar grafik tidak terlihat datar/rusak saat data kosong
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false,
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            color: '#64748b',
                            padding: 10,
                            callback: function(value) {
                                if (value === 0) return '0';
                                if (value >= 1000000000) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + ' M';
                                }
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                }
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            color: '#64748b'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection