@extends('layouts.app')

@section('title', 'Transfer Gudang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Transfer Gudang</h1>
</div>



<div class="card card-dashboard">
    <div class="card-body">
        <form action="{{ route('persediaan.storeTransfer') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">No Surat Jalan (SJ)</label>
                    <input type="text" name="no_sj" class="form-control" value="{{ $no_ref }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic" class="form-control" required placeholder="Nama PIC">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Item</label>
                    <select name="item_id" class="form-select" id="itemSelect" required>
                        <option value="">-- Pilih Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-stok="{{ $item->stok }}">
                                {{ $item->kode_item }} - {{ $item->nama_item }} (Stok: {{ $item->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty Transfer</label>
                    <input type="number" name="qty" id="qtyInput" class="form-control" min="1" required>
                    <small class="text-muted" id="stokInfo"></small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Tujuan transfer / Catatan tambahan">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-2"></i> Proses Transfer
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('itemSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const stok = option.dataset.stok;
        if(stok) {
            document.getElementById('qtyInput').max = stok;
            document.getElementById('stokInfo').textContent = 'Maks: ' + stok;
        } else {
            document.getElementById('stokInfo').textContent = '';
        }
    });
</script>
@endpush
@endsection
