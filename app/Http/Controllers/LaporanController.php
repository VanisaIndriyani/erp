<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PembayaranHutang;
use App\Models\PembayaranPiutang;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User;
use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiAkun;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('modules.laporan.index');
    }

    public function pembelian(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $suppliers = Supplier::orderBy('nama')->get();
        $users = User::orderBy('name')->get();

        $data = Pembelian::whereBetween('tanggal', [$start_date, $end_date])
            ->when($request->supplier_id, function($q) use ($request) {
                return $q->where('supplier_id', $request->supplier_id);
            })
            ->with('supplier')
            ->orderBy('tanggal')
            ->get();

        return view('modules.laporan.pembelian', compact('data', 'start_date', 'end_date', 'suppliers', 'users'));
    }

    public function penjualan(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $customers = Customer::orderBy('nama')->get();
        $users = User::orderBy('name')->get();

        $data = Penjualan::whereBetween('tanggal', [$start_date, $end_date])
            ->when($request->customer_id, function($q) use ($request) {
                return $q->where('customer_id', $request->customer_id);
            })
            ->with('customer')
            ->orderBy('tanggal')
            ->get();

        return view('modules.laporan.penjualan', compact('data', 'start_date', 'end_date', 'customers', 'users'));
    }

    public function hutang(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $suppliers = Supplier::orderBy('nama')->get();

        // Hutang Beredar Logic (Simplified)
        $data = Pembelian::with('supplier', 'pembayaran_details')
            ->when($request->supplier_id, function($q) use ($request) {
                return $q->where('supplier_id', $request->supplier_id);
            })
            ->get()
            ->filter(function($p) {
                return $p->sisa_tagihan > 0;
            });

        return view('modules.laporan.hutang', compact('data', 'start_date', 'end_date', 'suppliers'));
    }

    public function piutang(Request $request)
    {
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');
        $customers = Customer::orderBy('nama')->get();

        // Piutang Beredar Logic
        $data = Penjualan::with('customer', 'pembayaran_details')
            ->when($request->customer_id, function($q) use ($request) {
                return $q->where('customer_id', $request->customer_id);
            })
            // Optional: Filter by date if user wants to see invoices within a range, 
            // but usually Piutang Beredar is "all outstanding", regardless of date.
            // However, consistent with other reports, we might want to filter by transaction date.
            // Let's filter by transaction date for consistency with the filter form.
            ->whereBetween('tanggal', [$start_date, $end_date]) 
            ->get()
            ->filter(function($p) {
                return $p->sisa_tagihan > 0;
            });

        return view('modules.laporan.piutang', compact('data', 'start_date', 'end_date', 'customers'));
    }

    public function persediaan(Request $request)
    {
        $data = Item::orderBy('nama_item')->get();
        return view('modules.laporan.persediaan', compact('data'));
    }

    public function bukuKas(Request $request)
    {
        // Find Cash/Bank accounts
        $kas_accounts = AkuntansiAkun::where('nama_akun', 'like', '%Kas%')
            ->orWhere('nama_akun', 'like', '%Bank%')
            ->pluck('id');

        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');

        $data = AkuntansiJurnalDetail::whereIn('akun_id', $kas_accounts)
            ->whereHas('jurnal', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('tanggal', [$start_date, $end_date]);
            })
            ->with(['jurnal', 'akun'])
            ->get()
            ->sortBy(function($detail) {
                return $detail->jurnal->tanggal . $detail->jurnal->created_at;
            });

        return view('modules.laporan.buku_kas', compact('data', 'start_date', 'end_date'));
    }
}
