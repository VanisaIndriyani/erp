<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPiutang;
use App\Models\PembayaranPiutangDetail;
use App\Models\Penjualan;
use App\Models\Customer;
use App\Models\AkuntansiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranPiutangController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranPiutang::with(['customer', 'user', 'akun', 'details.penjualan']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_bayar', 'like', "%{$search}%")
                  ->orWhere('no_ref', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $pembayarans = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('penjualan.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $customers = Customer::all();
        // Get Asset accounts (Cash/Bank)
        $akuns = AkuntansiAkun::where('tipe', 'asset')->get(); 
        
        // Generate Auto Number
        $today = date('Ymd');
        $last = PembayaranPiutang::whereDate('created_at', today())->latest()->first();
        $nextNr = $last ? (int)substr($last->no_bayar, -4) + 1 : 1;
        $no_bayar = 'PP-' . $today . '-' . sprintf('%04d', $nextNr);

        return view('penjualan.pembayaran.create', compact('customers', 'akuns', 'no_bayar'));
    }

    public function getUnpaidInvoices($customer_id)
    {
        $invoices = Penjualan::where('customer_id', $customer_id)
            ->where('status_pembayaran', '!=', 'lunas')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'no_invoice' => $inv->no_transaksi,
                    'no_po' => $inv->no_po ?? '-',
                    'tanggal' => date('d/m/Y', strtotime($inv->tanggal)),
                    'jatuh_tempo' => date('d/m/Y', strtotime($inv->tanggal . ' + 30 days')), 
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
            'no_bayar' => 'required|unique:pembayaran_piutang,no_bayar',
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'akun_id' => 'nullable|exists:akuntansi_akun,id',
            'cara_bayar' => 'required',
            'no_ref' => 'nullable|string',
            'details' => 'required|array',
            'details.*.penjualan_id' => 'required|exists:penjualan,id',
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
            $pembayaran = PembayaranPiutang::create([
                'no_bayar' => $request->no_bayar,
                'tanggal' => $request->tanggal,
                'customer_id' => $request->customer_id,
                'cara_bayar' => $request->cara_bayar,
                'no_ref' => $request->no_ref,
                'akun_id' => $request->akun_id,
                'total_bayar' => $total_bayar,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id() ?? 1,
            ]);

            // Process Details
            foreach ($request->details as $item) {
                if ($item['jumlah_bayar'] > 0) {
                    PembayaranPiutangDetail::create([
                        'pembayaran_piutang_id' => $pembayaran->id,
                        'penjualan_id' => $item['penjualan_id'],
                        'jumlah_bayar' => $item['jumlah_bayar'],
                        'potongan' => $item['potongan'] ?? 0,
                    ]);

                    // Update Penjualan Status
                    $penjualan = Penjualan::find($item['penjualan_id']);
                    $penjualan->jumlah_terbayar += $item['jumlah_bayar'];
                    
                    // Tolerance for floating point calculation
                    if ($penjualan->jumlah_terbayar >= $penjualan->total - 1) {
                        $penjualan->status_pembayaran = 'lunas';
                    } else {
                        $penjualan->status_pembayaran = 'partial';
                    }
                    $penjualan->save();
                }
            }

            DB::commit();

            return redirect()->route('penjualan.pembayaran.index')->with('success', 'Pembayaran piutang berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
