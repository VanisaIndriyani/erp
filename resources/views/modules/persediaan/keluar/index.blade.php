@extends('layouts.app')

@section('title', 'Daftar Item Keluar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Item Keluar</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('persediaan.createKeluar') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
          
        </div>
    </div>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('persediaan.keluar') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari No Transaksi / Keterangan..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>No Transaksi</th>
                        <th>Dari</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>User Buat</th>
                        <th>User Ubah</th>
                        <th>Pos</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->no_transaksi }}</td>
                        <td>{{ $item->gudang_asal }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>-</td> <!-- User Ubah not tracked yet -->
                        <td>LOKAL</td>
                        <td>
                            <a href="{{ route('persediaan.showKeluar', $item->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">Tidak ada data item keluar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
