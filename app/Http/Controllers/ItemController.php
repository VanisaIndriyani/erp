<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\KartuStok;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%")
                  ->orWhere('kode_item', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        $items = $query->get();
        $akun = \App\Models\AkuntansiAkun::all(); // Fetch all accounts for dropdowns
        return view('master.item.index', compact('items', 'akun'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_item' => 'required|unique:items,kode_item',
            'nama_item' => 'required',
            'jenis' => 'nullable',
            'merk' => 'nullable',
            'satuan' => 'nullable',
            'harga_pokok' => 'required|numeric',
            'up_persen' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok_minimum' => 'nullable|numeric',
            'tipe_item' => 'required|in:inventory,jasa,rakitan,non-inventory',
            'rak' => 'nullable|string',
            'hpp_system' => 'required|in:FIFO,LIFO,AVERAGE',
            'barcode' => 'nullable|string',
            'pilihan_harga' => 'required|in:satu_harga,satuan,level,jumlah',
            'poin_dasar' => 'nullable|integer',
            'komisi_sales' => 'nullable|numeric',
            'akun_hpp_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_penjualan_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_persediaan_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_biaya_non_inventory_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_persediaan_dalam_proses_id' => 'nullable|exists:akuntansi_akun,id',
        ]);

        // Checkbox handling
        $validated['pajak_include'] = $request->has('pajak_include');
        $validated['status_jual'] = $request->has('status_jual'); // Assuming checkbox for "Masih dijual" (checked) vs "Discontinue" (unchecked) or vice versa. 
        // Wait, the image shows "Status Jual: Masih dijual (radio) / Tidak dijual (radio)". 
        // So request will send 'status_jual' = '1' or '0' if using radio with values.
        // Let's assume radio returns boolean-like value.
        $validated['status_jual'] = $request->input('status_jual', 1); // Default active

        Item::create($validated);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'kode_item' => 'required|unique:items,kode_item,' . $item->id,
            'nama_item' => 'required',
            'jenis' => 'nullable',
            'merk' => 'nullable',
            'satuan' => 'nullable',
            'harga_pokok' => 'required|numeric',
            'up_persen' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok_minimum' => 'nullable|numeric',
            'tipe_item' => 'required|in:inventory,jasa,rakitan,non-inventory',
            'rak' => 'nullable|string',
            'hpp_system' => 'required|in:FIFO,LIFO,AVERAGE',
            'barcode' => 'nullable|string',
            'pilihan_harga' => 'required|in:satu_harga,satuan,level,jumlah',
            'poin_dasar' => 'nullable|integer',
            'komisi_sales' => 'nullable|numeric',
            'akun_hpp_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_penjualan_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_persediaan_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_biaya_non_inventory_id' => 'nullable|exists:akuntansi_akun,id',
            'akun_persediaan_dalam_proses_id' => 'nullable|exists:akuntansi_akun,id',
        ]);

        $validated['pajak_include'] = $request->has('pajak_include');
        $validated['status_jual'] = $request->input('status_jual', 1);

        $item->update($validated);

        return redirect()->back()->with('success', 'Item berhasil diperbarui');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }

    public function history(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $query = KartuStok::where('item_id', $id)->with(['supplier', 'customer']);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $history = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();
        
        $totalMasuk = $history->sum('masuk');
        $totalKeluar = $history->sum('keluar');

        return view('master.item.history', [
            'item' => $item,
            'history' => $history,
            'total_masuk' => $totalMasuk,
            'total_keluar' => $totalKeluar
        ]);
    }
}
