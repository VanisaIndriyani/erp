<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemMasuk;
use App\Models\ItemMasukDetail;
use App\Models\ItemKeluar;
use App\Models\ItemKeluarDetail;
use App\Models\ItemOpname;
use App\Models\ItemTransfer;
use App\Models\ItemTransferDetail;
use App\Models\KartuStok;
use App\Models\AkuntansiAkun;
use App\Helpers\StockHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemMasukExport;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\ItemKeluarExport;
use App\Exports\ItemOpnameExport;
use App\Exports\ItemTransferExport;

class PersediaanController extends Controller
{
    // Item Masuk (Non-Pembelian)
    public function masuk(Request $request)
    {
        $query = ItemMasuk::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('modules.persediaan.masuk.index', compact('data'));
    }

    public function showMasuk($id)
    {
        $itemMasuk = ItemMasuk::with(['details.item', 'user', 'akun'])->findOrFail($id);
        return view('modules.persediaan.masuk.show', compact('itemMasuk'));
    }

    public function exportMasuk(Request $request)
    {
        return Excel::download(new ItemMasukExport($request->search), 'item_masuk_' . date('Ymd_His') . '.xlsx');
    }

    public function printMasukPdf($id)
    {
        $itemMasuk = ItemMasuk::with(['details.item', 'user', 'akun'])->findOrFail($id);
        $pdf = Pdf::loadView('modules.persediaan.masuk.print_pdf', compact('itemMasuk'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('item_masuk_' . $itemMasuk->no_transaksi . '.pdf');
    }

    public function createMasuk()
    {
        $items = Item::all();
        $akuns = AkuntansiAkun::all(); // Fetch all accounts for selection
        
        // Generate Auto Number
        $today = date('Ymd');
        $last = ItemMasuk::whereDate('created_at', today())->latest()->first();
        $nextNr = $last ? (int)substr($last->no_transaksi, -4) + 1 : 1;
        $no_transaksi = 'IM-' . $today . '-' . sprintf('%04d', $nextNr);

        return view('modules.persediaan.masuk.create', compact('items', 'akuns', 'no_transaksi'));
    }

    // Transfer Item
    public function transfer(Request $request)
    {
        $query = ItemTransfer::with(['user', 'details']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('no_sj', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $transfers = $query->latest()->paginate(10);
        return view('modules.persediaan.transfer.index', compact('transfers'));
    }

    public function exportTransfer(Request $request)
    {
        return Excel::download(new ItemTransferExport($request->search), 'item_transfer_' . date('Ymd_His') . '.xlsx');
    }

    public function createTransfer()
    {
        $items = Item::where('stok', '>', 0)->get();
        $today = date('Ymd');
        $last = ItemTransfer::whereDate('created_at', today())->latest()->first();
        $nextNr = $last ? (int)substr($last->no_transaksi, -4) + 1 : 1;
        $no_transaksi = 'TRF-' . $today . '-' . sprintf('%04d', $nextNr); 

        return view('modules.persediaan.transfer.create', compact('items', 'no_transaksi'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:item_transfers,no_transaksi',
            'tanggal' => 'required|date',
            'gudang_asal' => 'required|string',
            'gudang_tujuan' => 'required|string|different:gudang_asal',
            'details' => 'required|array',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.qty' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Validate Stock (Global Stock Check)
            foreach ($request->details as $detail) {
                $item = Item::find($detail['item_id']);
                if ($item->stok < $detail['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk item: " . $item->nama_item . " (Sisa: " . $item->stok . ")");
                }
            }

            $transfer = ItemTransfer::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal' => $request->tanggal,
                'gudang_asal' => $request->gudang_asal,
                'gudang_tujuan' => $request->gudang_tujuan,
                'no_sj' => $request->no_sj,
                'pic' => $request->pic,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1,
            ]);

            foreach ($request->details as $detail) {
                ItemTransferDetail::create([
                    'item_transfer_id' => $transfer->id,
                    'item_id' => $detail['item_id'],
                    'qty' => $detail['qty'],
                    'satuan' => $detail['satuan'] ?? null,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);

                // Record Transfer OUT from Source
                StockHelper::record(
                    $detail['item_id'],
                    $request->tanggal,
                    'transfer',
                    $transfer->no_transaksi,
                    -$detail['qty'], 
                    "Transfer ke " . $request->gudang_tujuan . " (Ref: " . $request->no_sj . ")"
                );
                
                // Record Transfer IN to Destination
                StockHelper::record(
                    $detail['item_id'],
                    $request->tanggal,
                    'transfer',
                    $transfer->no_transaksi,
                    $detail['qty'],
                    "Transfer dari " . $request->gudang_asal
                );
            }

            DB::commit();
            return redirect()->route('persediaan.transfer')->with('success', 'Transfer item berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function showTransfer($id)
    {
        $transfer = ItemTransfer::with('details.item', 'user')->findOrFail($id);
        return view('modules.persediaan.transfer.show', compact('transfer'));
    }

    public function printTransferPdf($id)
    {
        $transfer = ItemTransfer::with('details.item', 'user')->findOrFail($id);
        $pdf = Pdf::loadView('modules.persediaan.transfer.print_pdf', compact('transfer'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('item_transfer_' . $transfer->no_transaksi . '.pdf');
    }

    public function printTransfer($id)
    {
        $transfer = ItemTransfer::with('details.item', 'user')->findOrFail($id);
        return view('modules.persediaan.transfer.print', compact('transfer'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:item_masuks,no_transaksi',
            'tanggal' => 'required|date',
            'gudang_tujuan' => 'nullable|string',
            'akun_id' => 'nullable|exists:akuntansi_akun,id',
            'details' => 'required|array',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.qty' => 'required|numeric|min:0.01',
            'details.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $total_nilai = 0;
            foreach ($request->details as $detail) {
                $total_nilai += ($detail['qty'] * $detail['harga']);
            }

            $itemMasuk = ItemMasuk::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal' => $request->tanggal,
                'gudang_tujuan' => $request->gudang_tujuan ?? 'UTAMA',
                'keterangan' => $request->keterangan,
                'akun_id' => $request->akun_id,
                'user_id' => Auth::id() ?? 1,
                'total_nilai' => $total_nilai,
            ]);

            foreach ($request->details as $detail) {
                $subtotal = $detail['qty'] * $detail['harga'];

                ItemMasukDetail::create([
                    'item_masuk_id' => $itemMasuk->id,
                    'item_id' => $detail['item_id'],
                    'qty' => $detail['qty'],
                    'satuan' => $detail['satuan'] ?? null,
                    'harga' => $detail['harga'],
                    'total' => $subtotal,
                ]);

                // Update Stock
                $item = Item::find($detail['item_id']);
                $item->stok += $detail['qty'];
                $item->save();

                // Record Stock Card
                StockHelper::record(
                    $item->id,
                    $request->tanggal,
                    'masuk',
                    $itemMasuk->no_transaksi,
                    $detail['qty'],
                    $request->keterangan ?? 'Item Masuk Lainnya'
                );
            }

            DB::commit();
            return redirect()->route('persediaan.masuk')->with('success', 'Item masuk berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Item Keluar (Non-Penjualan)
    public function keluar(Request $request)
    {
        $query = ItemKeluar::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('modules.persediaan.keluar.index', compact('data'));
    }

    public function exportKeluar(Request $request)
    {
        return Excel::download(new ItemKeluarExport($request->search), 'item_keluar_' . date('Ymd_His') . '.xlsx');
    }

    public function createKeluar()
    {
        $items = Item::where('stok', '>', 0)->get();
        
        // Generate Auto Number
        $today = date('Ymd');
        $last = ItemKeluar::whereDate('created_at', today())->latest()->first();
        $nextNr = $last ? (int)substr($last->no_transaksi, -4) + 1 : 1;
        $no_transaksi = 'IK-' . $today . '-' . sprintf('%04d', $nextNr);

        return view('modules.persediaan.keluar.create', compact('items', 'no_transaksi'));
    }

    public function showKeluar($id)
    {
        $data = ItemKeluar::with('details.item', 'user')->findOrFail($id);
        return view('modules.persediaan.keluar.show', compact('data'));
    }

    public function printKeluarPdf($id)
    {
        $data = ItemKeluar::with('details.item', 'user')->findOrFail($id);
        $pdf = Pdf::loadView('modules.persediaan.keluar.print_pdf', compact('data'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('item_keluar_' . $data->no_transaksi . '.pdf');
    }

    public function storeKeluar(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:item_keluars,no_transaksi',
            'tanggal' => 'required|date',
            'gudang_asal' => 'nullable|string',
            'details' => 'required|array',
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.qty' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // Validate Stock Availability First
            foreach ($request->details as $detail) {
                $item = Item::find($detail['item_id']);
                if ($item->stok < $detail['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk item: " . $item->nama_item . " (Sisa: " . $item->stok . ")");
                }
            }

            $itemKeluar = ItemKeluar::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal' => $request->tanggal,
                'gudang_asal' => $request->gudang_asal ?? 'UTAMA',
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1,
            ]);

            foreach ($request->details as $detail) {
                ItemKeluarDetail::create([
                    'item_keluar_id' => $itemKeluar->id,
                    'item_id' => $detail['item_id'],
                    'qty' => $detail['qty'],
                    'satuan' => $detail['satuan'] ?? null,
                    'keterangan' => $detail['keterangan'] ?? null,
                ]);

                // Update Stock
                $item = Item::find($detail['item_id']);
                $item->stok -= $detail['qty'];
                $item->save();

                // Record Stock Card
                StockHelper::record(
                    $item->id,
                    $request->tanggal,
                    'keluar',
                    $itemKeluar->no_transaksi,
                    -$detail['qty'],
                    $request->keterangan ?? 'Item Keluar Lainnya'
                );
            }

            DB::commit();
            return redirect()->route('persediaan.keluar')->with('success', 'Item keluar berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Stok Opname
    public function opname()
    {
        $items = Item::all();
        $akuns = AkuntansiAkun::all();
        $opnames = ItemOpname::with('item', 'user')->latest()->limit(50)->get(); // Show recent opnames
        return view('modules.persediaan.opname', compact('items', 'akuns', 'opnames'));
    }

    public function storeOpname(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'gudang' => 'nullable|string',
            'item_id' => 'required|exists:items,id',
            'stok_fisik' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $item = Item::find($request->item_id);
            $stok_sistem = $item->stok;
            $selisih = $request->stok_fisik - $stok_sistem;

            if ($selisih == 0) {
                 // No change, but maybe we still want to record the opname check?
                 // For now, let's proceed to record it.
            }

            // Create Opname Record
            ItemOpname::create([
                'tanggal' => $request->tanggal,
                'gudang' => $request->gudang ?? 'UTAMA',
                'item_id' => $item->id,
                'stok_sistem' => $stok_sistem,
                'stok_fisik' => $request->stok_fisik,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1,
            ]);

            // Update Item Stock
            $item->stok = $request->stok_fisik;
            $item->save();

            // Record Stock Card
            if ($selisih != 0) {
                StockHelper::record(
                    $item->id,
                    $request->tanggal,
                    'opname',
                    'OPNAME-' . date('YmdHis'),
                    $selisih,
                    $request->keterangan ?? 'Penyesuaian Stok Opname'
                );
            }

            DB::commit();
            return redirect()->route('persediaan.opname')->with('success', 'Stok opname berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function exportOpname()
    {
        return Excel::download(new ItemOpnameExport(), 'stok_opname_' . date('Ymd_His') . '.xlsx');
    }
}
