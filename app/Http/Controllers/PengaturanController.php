<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Builder;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('modules.pengaturan.index');
    }

    public function monitoringPO(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $kategori = $request->kategori ?? ['Sparepart', 'Hose'];

        // Ensure kategori is array
        if (!is_array($kategori)) {
            $kategori = [$kategori];
        }

        $data = Pembelian::whereBetween('tanggal', [$start_date, $end_date])
            ->whereHas('details.item', function (Builder $query) use ($kategori) {
                $query->whereIn('jenis', $kategori);
            })
            ->with(['supplier', 'details' => function ($query) use ($kategori) {
                $query->whereHas('item', function ($q) use ($kategori) {
                    $q->whereIn('jenis', $kategori);
                })->with('item');
            }])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('modules.pengaturan.monitoring_po', compact('data', 'start_date', 'end_date', 'kategori'));
    }

    public function monitoringTransaksi(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $kategori = $request->kategori ?? ['Sparepart', 'Hose'];

        // Ensure kategori is array
        if (!is_array($kategori)) {
            $kategori = [$kategori];
        }

        $data = Penjualan::whereBetween('tanggal', [$start_date, $end_date])
            ->whereHas('details.item', function (Builder $query) use ($kategori) {
                $query->whereIn('jenis', $kategori);
            })
            ->with(['customer', 'details' => function ($query) use ($kategori) {
                $query->whereHas('item', function ($q) use ($kategori) {
                    $q->whereIn('jenis', $kategori);
                })->with('item');
            }])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('modules.pengaturan.monitoring_transaksi', compact('data', 'start_date', 'end_date', 'kategori'));
    }
}
