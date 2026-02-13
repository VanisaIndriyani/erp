<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PembayaranHutang;
use App\Models\PembayaranPiutang;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\AkuntansiAkun;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // --- PEMBAYARAN HUTANG ---

    public function indexHutang()
    {
        $suppliers = Supplier::orderBy('nama')->get();
        $akunKas = AkuntansiAkun::where('nama_akun', 'like', '%Kas%')
            ->orWhere('nama_akun', 'like', '%Bank%')
            ->orderBy('nama_akun')
            ->get();
            
        return view('modules.pembelian.bayar_hutang', compact('suppliers', 'akunKas'));
    }

    public function getHutangBySupplier(Request $request)
    {
        $supplier_id = $request->supplier_id;
        
        // Get Purchases with outstanding balance
        $pembelians = Pembelian::where('supplier_id', $supplier_id)
            ->with('pembayaran_details')
            ->orderBy('tanggal')
            ->get()
            ->filter(function ($pembelian) {
                return $pembelian->sisa_tagihan > 0;
            })
            ->values(); // Reset keys for JSON

        return response()->json($pembelians);
    }

    public function storeHutang(Request $request)
    {
        $request->validate([
            'pembelian_id' => 'required|exists:pembelian,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'akun_kas_id' => 'required|exists:akuntansi_akun,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $pembelian = Pembelian::findOrFail($request->pembelian_id);
            
            if ($request->jumlah_bayar > $pembelian->sisa_tagihan) {
                return back()->with('error', 'Jumlah bayar melebihi sisa tagihan!');
            }

            // Create Payment Record
            $pembayaran = new PembayaranHutang();
            $pembayaran->no_bayar = 'PH-' . date('YmdHis');
            $pembayaran->tanggal = $request->tanggal;
            $pembayaran->pembelian_id = $pembelian->id;
            $pembayaran->jumlah_bayar = $request->jumlah_bayar;
            $pembayaran->keterangan = $request->keterangan;
            $pembayaran->save();

            // Create Journal Entry
            // Debit: Hutang Usaha (Liability Decrease)
            // Credit: Kas/Bank (Asset Decrease)

            // Find Hutang Account (Assume logic or fetch specific account)
            // For simplicity, we search for 'Hutang Usaha' or 'Hutang Dagang'
            $akunHutang = AkuntansiAkun::where('nama_akun', 'like', '%Hutang%')->first();
            if (!$akunHutang) {
                 // Fallback or create? Better to fail safely.
                 // In real app, this should be configured.
                 $akunHutang = AkuntansiAkun::firstOrCreate(
                     ['kode_akun' => '2100', 'nama_akun' => 'Hutang Usaha'],
                     ['tipe' => 'liability', 'saldo_normal' => 'kredit']
                 );
            }

            $jurnal = new AkuntansiJurnal();
            $jurnal->tanggal = $request->tanggal;
            $jurnal->no_ref = $pembayaran->no_bayar;
            $jurnal->keterangan = 'Pembayaran Hutang Invoice ' . $pembelian->no_invoice . ($request->keterangan ? ' - ' . $request->keterangan : '');
            $jurnal->save();

            // Debit Hutang
            AkuntansiJurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'akun_id' => $akunHutang->id,
                'debit' => $request->jumlah_bayar,
                'kredit' => 0
            ]);

            // Credit Kas
            AkuntansiJurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'akun_id' => $request->akun_kas_id,
                'debit' => 0,
                'kredit' => $request->jumlah_bayar
            ]);

            DB::commit();

            return redirect()->route('pembelian.bayar_hutang.index')->with('success', 'Pembayaran hutang berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // --- PEMBAYARAN PIUTANG ---

    public function indexPiutang()
    {
        $customers = Customer::orderBy('nama')->get();
        $akunKas = AkuntansiAkun::where('nama_akun', 'like', '%Kas%')
            ->orWhere('nama_akun', 'like', '%Bank%')
            ->orderBy('nama_akun')
            ->get();
            
        return view('modules.penjualan.bayar_piutang', compact('customers', 'akunKas'));
    }

    public function getPiutangByCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        
        // Get Sales with outstanding balance
        $penjualans = Penjualan::where('customer_id', $customer_id)
            ->with('pembayaran_details')
            ->orderBy('tanggal')
            ->get()
            ->filter(function ($penjualan) {
                return $penjualan->sisa_tagihan > 0;
            })
            ->values();

        return response()->json($penjualans);
    }

    public function storePiutang(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|exists:penjualan,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'akun_kas_id' => 'required|exists:akuntansi_akun,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $penjualan = Penjualan::findOrFail($request->penjualan_id);
            
            if ($request->jumlah_bayar > $penjualan->sisa_tagihan) {
                return back()->with('error', 'Jumlah bayar melebihi sisa tagihan!');
            }

            // Create Payment Record
            $pembayaran = new PembayaranPiutang();
            $pembayaran->no_bayar = 'PP-' . date('YmdHis');
            $pembayaran->tanggal = $request->tanggal;
            $pembayaran->penjualan_id = $penjualan->id;
            $pembayaran->jumlah_bayar = $request->jumlah_bayar;
            $pembayaran->keterangan = $request->keterangan;
            $pembayaran->save();

            // Create Journal Entry
            // Debit: Kas/Bank (Asset Increase)
            // Credit: Piutang Usaha (Asset Decrease)

            // Find Piutang Account
            $akunPiutang = AkuntansiAkun::where('nama_akun', 'like', '%Piutang%')->first();
             if (!$akunPiutang) {
                 $akunPiutang = AkuntansiAkun::firstOrCreate(
                     ['kode_akun' => '1102', 'nama_akun' => 'Piutang Usaha'],
                     ['tipe' => 'asset', 'saldo_normal' => 'debit']
                 );
            }

            $jurnal = new AkuntansiJurnal();
            $jurnal->tanggal = $request->tanggal;
            $jurnal->no_ref = $pembayaran->no_bayar;
            $jurnal->keterangan = 'Pembayaran Piutang Invoice ' . $penjualan->no_transaksi . ($request->keterangan ? ' - ' . $request->keterangan : '');
            $jurnal->save();

            // Debit Kas
            AkuntansiJurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'akun_id' => $request->akun_kas_id,
                'debit' => $request->jumlah_bayar,
                'kredit' => 0
            ]);

            // Credit Piutang
            AkuntansiJurnalDetail::create([
                'jurnal_id' => $jurnal->id,
                'akun_id' => $akunPiutang->id,
                'debit' => 0,
                'kredit' => $request->jumlah_bayar
            ]);

            DB::commit();

            return redirect()->route('penjualan.bayar_piutang.index')->with('success', 'Pembayaran piutang berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
