<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Supplier;
use App\Models\Item;
use App\Helpers\StockHelper;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'details.item']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                  ->orWhere('no_po', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $pembelians = $query->orderBy('tanggal', 'desc')->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        // $no_invoice = 'INV-' . date('YmdHis'); // Don't auto-generate, let user input from physical invoice
        return view('pembelian.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_invoice' => 'required|unique:pembelian,no_invoice',
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create Header
            $pembelian = Pembelian::create([
                'no_invoice' => $request->no_invoice,
                'no_po' => $request->no_po,
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'gudang' => $request->gudang,
                'keterangan' => $request->keterangan,
                'ppn' => $request->ppn ?? 0,
                'total' => 0, // Will update later
            ]);

            $total = 0;

            // 2. Create Details & Update Stock
            foreach ($request->items as $itemData) {
                $subtotal = $itemData['qty'] * $itemData['harga'];
                $total += $subtotal;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $subtotal,
                ]);

                // Update Stock Item
                $item = Item::find($itemData['item_id']);
                $item->stok += $itemData['qty'];
                // Update harga pokok if needed (average or last price) - User didn't specify, so keeping simple for now
                // But updating harga_pokok to latest purchase price is common practice
                $item->harga_pokok = $itemData['harga']; 
                $item->save();

                // Record Kartu Stok
                StockHelper::record(
                    $item->id,
                    $request->tanggal,
                    'masuk',
                    $pembelian->no_invoice,
                    $itemData['qty'],
                    'Pembelian dari ' . $pembelian->supplier->nama
                );
            }

            // Update Total with PPN
            $grandTotal = $total + $pembelian->ppn;
            $pembelian->update(['total' => $grandTotal]);

            DB::commit();

            return redirect()->route('pembelian.transaksi.index')->with('success', 'Transaksi Pembelian Berhasil Disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'details.item'])->findOrFail($id);
        return view('pembelian.show', compact('pembelian'));
    }
}
