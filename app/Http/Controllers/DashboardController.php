<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\PembayaranHutang;
use App\Models\PembayaranPiutang;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();
        $totalStok = Item::sum('stok');
        
        // Financial Stats
        $totalPembelian = Pembelian::sum('total');
        $totalPenjualan = Penjualan::sum('total');
        
        $totalBayarHutang = PembayaranHutang::sum('total_bayar');
        $totalBayarPiutang = PembayaranPiutang::sum('total_bayar');

        $totalHutang = $totalPembelian - $totalBayarHutang;
        $totalPiutang = $totalPenjualan - $totalBayarPiutang;

        // Chart Data (Last 12 Months)
        $chartLabels = [];
        $chartPenjualan = [];
        $chartPembelian = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('m');
            $year = $date->format('Y');
            $chartLabels[] = $date->format('M Y');

            $chartPenjualan[] = Penjualan::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('total');
            
            $chartPembelian[] = Pembelian::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->sum('total');
        }

        return view('dashboard.index', compact(
            'totalItems', 
            'totalSuppliers', 
            'totalCustomers', 
            'totalStok',
            'totalPenjualan',
            'totalPembelian',
            'totalHutang',
            'totalPiutang',
            'chartLabels',
            'chartPenjualan',
            'chartPembelian'
        ));
    }
}
