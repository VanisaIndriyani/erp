<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Customer;
use App\Models\Item;
use App\Helpers\StockHelper;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('customer');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($mq) use ($search) {
                      $mq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $penjualans = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Item::where('stok', '>', 0)->get(); // Only items with stock
        $no_transaksi = 'TRX-' . date('YmdHis');
        return view('penjualan.create', compact('customers', 'items', 'no_transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:penjualan,no_transaksi',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Header
            $penjualan = Penjualan::create([
                'no_transaksi' => $request->no_transaksi,
                'no_po' => $request->no_po,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'unit' => $request->unit,
                'keterangan' => $request->keterangan,
                'total' => 0, // Will update later
            ]);

            $total = 0;

            // 2. Create Details & Update Stock
            foreach ($request->items as $itemData) {
                // Check Stock
                $item = Item::find($itemData['item_id']);
                if ($item->stok < $itemData['qty']) {
                    throw new \Exception("Stok item {$item->nama_item} tidak mencukupi. Stok saat ini: {$item->stok}");
                }

                $subtotal = $itemData['qty'] * $itemData['harga'];
                $total += $subtotal;

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $subtotal,
                ]);

                // Update Stock Item
                $item->stok -= $itemData['qty'];
                $item->save();

                // Record Kartu Stok
                StockHelper::record(
                    $item->id,
                    $request->tanggal,
                    'keluar',
                    $penjualan->no_transaksi,
                    -$itemData['qty'], // Negative for outgoing
                    'Penjualan ke ' . $penjualan->customer->nama
                );
            }

            // Update Total
            $penjualan->update(['total' => $total]);

            DB::commit();

            return redirect()->route('penjualan.transaksi.index')->with('success', 'Transaksi Penjualan Berhasil Disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['customer', 'details.item'])->findOrFail($id);
        return view('penjualan.show', compact('penjualan'));
    }
}
