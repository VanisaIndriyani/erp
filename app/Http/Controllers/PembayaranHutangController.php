<?php

namespace App\Http\Controllers;

use App\Models\PembayaranHutang;
use App\Models\PembayaranHutangDetail;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\AkuntansiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranHutangController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranHutang::with(['supplier', 'user', 'akun', 'details.pembelian']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_bayar', 'like', "%{$search}%")
                  ->orWhere('no_referensi', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $pembayarans = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('pembelian.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        // Get Asset accounts (Cash/Bank)
        $akuns = AkuntansiAkun::where('tipe', 'asset')->get(); 
        
        // Generate Auto Number
        $today = date('Ymd');
        $last = PembayaranHutang::whereDate('created_at', today())->latest()->first();
        $nextNr = $last ? (int)substr($last->no_bayar, -4) + 1 : 1;
        $no_bayar = 'PH-' . $today . '-' . sprintf('%04d', $nextNr);

        return view('pembelian.pembayaran.create', compact('suppliers', 'akuns', 'no_bayar'));
    }

    public function getUnpaidInvoices($supplier_id)
    {
        $invoices = Pembelian::where('supplier_id', $supplier_id)
            ->where('status_pembayaran', '!=', 'lunas')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'no_invoice' => $inv->no_invoice,
                    'tanggal' => date('d/m/Y', strtotime($inv->tanggal)),
                    'jatuh_tempo' => date('d/m/Y', strtotime($inv->tanggal . ' + 30 days')), // Assuming 30 days default or fetch from supplier
                    'total' => $inv->total,
                    'terbayar' => $inv->jumlah_terbayar,
                    'sisa' => $inv->total - $inv->jumlah_terbayar,
                ];
            });

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bayar' => 'required|unique:pembayaran_hutang,no_bayar',
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'akun_id' => 'nullable|exists:akuntansi_akun,id',
            'cara_bayar' => 'required',
            'no_referensi' => 'nullable|string',
            'details' => 'required|array',
            'details.*.pembelian_id' => 'required|exists:pembelian,id',
            'details.*.jumlah_bayar' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total paid
            $total_bayar = 0;
            foreach ($request->details as $detail) {
                $total_bayar += $detail['jumlah_bayar'];
            }

            if ($total_bayar <= 0) {
                 throw new \Exception("Total pembayaran harus lebih dari 0");
            }

            // Create Header
            $pembayaran = PembayaranHutang::create([
                'no_bayar' => $request->no_bayar,
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'cara_bayar' => $request->cara_bayar,
                'no_referensi' => $request->no_referensi,
                'akun_id' => $request->akun_id,
                'total_bayar' => $total_bayar,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1, // Fallback if no auth yet
            ]);

            // Process Details
            foreach ($request->details as $item) {
                if ($item['jumlah_bayar'] > 0) {
                    PembayaranHutangDetail::create([
                        'pembayaran_hutang_id' => $pembayaran->id,
                        'pembelian_id' => $item['pembelian_id'],
                        'jumlah_bayar' => $item['jumlah_bayar'],
                        'potongan' => $item['potongan'] ?? 0,
                    ]);

                    // Update Invoice Status
                    $pembelian = Pembelian::find($item['pembelian_id']);
                    $pembelian->jumlah_terbayar += $item['jumlah_bayar'];
                    
                    if ($pembelian->jumlah_terbayar >= $pembelian->total - 1) { // Tolerance for float
                        $pembelian->status_pembayaran = 'lunas';
                    } else {
                        $pembelian->status_pembayaran = 'partial';
                    }
                    $pembelian->save();
                }
            }

            DB::commit();

            return redirect()->route('pembelian.pembayaran.index')->with('success', 'Pembayaran berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
