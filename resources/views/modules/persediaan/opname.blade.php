@extends('layouts.app')

@section('title', 'Stok Opname')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Stok Opname</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.exportOpname') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Input Form -->
    <div class="col-md-5 mb-4">
        <div class="card card-dashboard shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-upc-scan me-2"></i>Input Opname (Scan Mode)</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('persediaan.storeOpname') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Informasi Dasar</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Tanggal</label>
                                <input type="date" class="form-control form-control-sm" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Gudang</label>
                                <select class="form-select form-select-sm" name="gudang">
                                    <option value="UTAMA">GUDANG UTAMA</option>
                                    <option value="GUDANG A">GUDANG A</option>
                                    <option value="GUDANG B">GUDANG B</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="form-label text-muted small">Pilih Item</label>
                        <div class="input-group input-group-sm">
                            <select class="form-select" id="itemSelect" name="item_id" onchange="itemChanged(this)" required>
                                <option value="">-- Cari Item (Kode / Nama) --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" 
                                            data-nama="{{ $item->nama_item }}" 
                                            data-satuan="{{ $item->satuan }}" 
                                            data-stok="{{ $item->stok }}">
                                        {{ $item->kode_item }} - {{ $item->nama_item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded mb-3 border">
                        <div class="row mb-2">
                            <label class="col-4 col-form-label col-form-label-sm text-muted">Nama Item</label>
                            <div class="col-8">
                                <input type="text" class="form-control form-control-sm bg-white border-0 fw-bold" id="namaItem" readonly placeholder="-">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-4 col-form-label col-form-label-sm text-muted">Stok Sistem</label>
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control bg-white border-0 fw-bold text-end" id="stokBuku" readonly placeholder="0">
                                    <span class="input-group-text border-0 bg-transparent" id="satuanDisplay">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-4 col-form-label col-form-label-sm text-muted">Stok Fisik</label>
                            <div class="col-8">
                                <input type="number" class="form-control form-control-sm text-end border-primary" name="stok_fisik" id="stokFisik" step="0.01" oninput="calculateSelisih()" required placeholder="Input jumlah fisik...">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-4 col-form-label col-form-label-sm text-muted">Selisih</label>
                            <div class="col-8">
                                <input type="number" class="form-control form-control-sm bg-white border-0 fw-bold text-end" name="selisih" id="selisih" readonly placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Keterangan</label>
                        <textarea class="form-control form-control-sm" name="keterangan" rows="2" placeholder="Contoh: Barang rusak, selisih tidak diketahui, dll"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Hasil Opname
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: History List -->
    <div class="col-md-7 mb-4">
        <div class="card card-dashboard shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-clock-history me-2"></i>Riwayat Opname Terakhir</h6>
                <span class="badge bg-light text-dark border">{{ $opnames->count() }} Data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">Tanggal</th>
                                <th>Item</th>
                                <th class="text-end">Sistem</th>
                                <th class="text-end">Fisik</th>
                                <th class="text-end pe-3">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($opnames as $opname)
                            <tr>
                                <td class="ps-3 small">{{ \Carbon\Carbon::parse($opname->tanggal)->format('d/m/y') }}</td>
                                <td class="small">
                                    <div class="fw-bold">{{ $opname->item->kode_item }}</div>
                                    <div class="text-muted" style="font-size: 0.8em;">{{ \Illuminate\Support\Str::limit($opname->item->nama_item, 20) }}</div>
                                </td>
                                <td class="text-end small">{{ number_format($opname->stok_sistem, 2) }}</td>
                                <td class="text-end small fw-bold">{{ number_format($opname->stok_fisik, 2) }}</td>
                                <td class="text-end pe-3 small">
                                    @if($opname->selisih > 0)
                                        <span class="text-success fw-bold">+{{ number_format($opname->selisih, 2) }}</span>
                                    @elseif($opname->selisih < 0)
                                        <span class="text-danger fw-bold">{{ number_format($opname->selisih, 2) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data opname.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Select2 if available (Optional improvement)
    // $(document).ready(function() { $('#itemSelect').select2(); });

    function itemChanged(select) {
        const option = select.options[select.selectedIndex];
        
        if (option.value) {
            document.getElementById('namaItem').value = option.dataset.nama || '';
            document.getElementById('satuanDisplay').textContent = option.dataset.satuan || '-';
            document.getElementById('stokBuku').value = option.dataset.stok || 0;
            document.getElementById('stokFisik').value = ''; // Reset physical stock input
            document.getElementById('selisih').value = '';
            
            // Auto focus to physical stock input
            setTimeout(() => document.getElementById('stokFisik').focus(), 100);
        } else {
            document.getElementById('namaItem').value = '';
            document.getElementById('satuanDisplay').textContent = '-';
            document.getElementById('stokBuku').value = '';
            document.getElementById('stokFisik').value = '';
            document.getElementById('selisih').value = '';
        }
    }

    function calculateSelisih() {
        const buku = parseFloat(document.getElementById('stokBuku').value) || 0;
        const fisik = parseFloat(document.getElementById('stokFisik').value) || 0;
        const selisih = fisik - buku;
        
        const selisihInput = document.getElementById('selisih');
        selisihInput.value = selisih.toFixed(2);
        
        if (selisih < 0) {
            selisihInput.classList.add('text-danger');
            selisihInput.classList.remove('text-success');
        } else if (selisih > 0) {
            selisihInput.classList.add('text-success');
            selisihInput.classList.remove('text-danger');
        } else {
            selisihInput.classList.remove('text-danger', 'text-success');
        }
    }
</script>
@endsection
