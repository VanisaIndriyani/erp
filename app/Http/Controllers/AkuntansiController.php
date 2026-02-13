<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkuntansiAkun;
use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class AkuntansiController extends Controller
{
    // --- DAFTAR PERKIRAAN (CHART OF ACCOUNTS) ---

    public function indexAkun(Request $request)
    {
        $query = AkuntansiAkun::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_akun', 'like', "%{$search}%")
                  ->orWhere('nama_akun', 'like', "%{$search}%");
            });
        }

        $akun = $query->orderBy('kode_akun', 'asc')->paginate(10);
        return view('modules.akuntansi.akun.index', compact('akun'));
    }

    public function storeAkun(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|unique:akuntansi_akun,kode_akun',
            'nama_akun' => 'required',
            'tipe' => 'required',
            'saldo_normal' => 'required',
        ]);

        AkuntansiAkun::create($request->all());

        return redirect()->route('akuntansi.akun.index')->with('success', 'Akun berhasil ditambahkan');
    }

    public function updateAkun(Request $request, $id)
    {
        $request->validate([
            'kode_akun' => 'required|unique:akuntansi_akun,kode_akun,' . $id,
            'nama_akun' => 'required',
            'tipe' => 'required',
            'saldo_normal' => 'required',
        ]);

        $akun = AkuntansiAkun::findOrFail($id);
        $akun->update($request->all());

        return redirect()->route('akuntansi.akun.index')->with('success', 'Akun berhasil diperbarui');
    }

    public function destroyAkun($id)
    {
        $akun = AkuntansiAkun::findOrFail($id);
        
        // Cek jika akun sudah dipakai di jurnal
        if ($akun->jurnalDetails()->count() > 0) {
            return back()->with('error', 'Akun tidak bisa dihapus karena sudah ada transaksi.');
        }

        $akun->delete();

        return redirect()->route('akuntansi.akun.index')->with('success', 'Akun berhasil dihapus');
    }

    // --- JURNAL UMUM ---
    public function indexJurnal(Request $request)
    {
        $query = AkuntansiJurnal::with('details.akun');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_ref', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('modules.akuntansi.jurnal.index', compact('jurnal'));
    }

    // --- KAS MASUK ---
    public function indexKasMasuk(Request $request)
    {
        $query = AkuntansiJurnal::where('tipe', 'kas_masuk')->with('details.akun');

        // Filter Date
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_ref', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('modules.akuntansi.kas_masuk.index', compact('jurnal'));
    }

    public function createKasMasuk()
    {
        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        return view('modules.akuntansi.kas_masuk.create', compact('akun'));
    }

    public function storeKasMasuk(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id', // Kas/Bank
            'akun_kredit' => 'required|exists:akuntansi_akun,id', // Source of funds
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $jurnal = AkuntansiJurnal::create([
                    'tanggal' => $request->tanggal,
                    'no_ref' => 'KM-' . date('YmdHis'),
                    'keterangan' => $request->keterangan,
                    'tipe' => 'kas_masuk',
                ]);

                // Debit (Kas Bertambah)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Sumber Dana)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_masuk.index')->with('success', 'Kas Masuk berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyKasTransfer($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                if ($jurnal->tipe !== 'kas_transfer') {
                    throw new \Exception('Transaksi bukan Kas Transfer');
                }
                $jurnal->details()->delete();
                $jurnal->delete();
            });
            return back()->with('success', 'Transaksi Kas Transfer berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function editKasTransfer($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'kas_transfer') {
            return redirect()->route('akuntansi.kas_transfer.index')->with('error', 'Transaksi bukan Kas Transfer');
        }

        $akun = AkuntansiAkun::where('tipe', 'asset')->orderBy('kode_akun')->get();
        
        // Find debit (Tujuan) and credit (Asal) details
        $detailDebit = $jurnal->details->where('debit', '>', 0)->first();
        $detailKredit = $jurnal->details->where('kredit', '>', 0)->first();

        return view('modules.akuntansi.kas_transfer.edit', compact('jurnal', 'akun', 'detailDebit', 'detailKredit'));
    }

    public function updateKasTransfer(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id',
            'akun_kredit' => 'required|exists:akuntansi_akun,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->akun_debit == $request->akun_kredit) {
            return back()->with('error', 'Akun asal dan tujuan tidak boleh sama.')->withInput();
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                ]);

                $jurnal->details()->delete();

                // Debit (Uang Masuk ke Tujuan)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Uang Keluar dari Asal)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_transfer.index')->with('success', 'Transfer Kas berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyKasMasuk($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                if ($jurnal->tipe !== 'kas_masuk') {
                    throw new \Exception('Transaksi bukan Kas Masuk');
                }
                $jurnal->details()->delete();
                $jurnal->delete();
            });
            return back()->with('success', 'Transaksi Kas Masuk berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function editKasMasuk($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'kas_masuk') {
            return redirect()->route('akuntansi.kas_masuk.index')->with('error', 'Transaksi bukan Kas Masuk');
        }

        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        
        // Find debit (Kas) and credit (Source) details
        $detailDebit = $jurnal->details->where('debit', '>', 0)->first();
        $detailKredit = $jurnal->details->where('kredit', '>', 0)->first();

        return view('modules.akuntansi.kas_masuk.edit', compact('jurnal', 'akun', 'detailDebit', 'detailKredit'));
    }

    public function updateKasMasuk(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id',
            'akun_kredit' => 'required|exists:akuntansi_akun,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                ]);

                // Delete old details and recreate (easiest way to ensure correctness)
                $jurnal->details()->delete();

                // Debit (Kas Bertambah)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Sumber Dana)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_masuk.index')->with('success', 'Kas Masuk berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage())->withInput();
        }
    }

    // --- KAS KELUAR ---
    public function indexKasKeluar(Request $request)
    {
        $query = AkuntansiJurnal::where('tipe', 'kas_keluar')->with('details.akun');

        // Filter Date
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_ref', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('modules.akuntansi.kas_keluar.index', compact('jurnal'));
    }

    public function createKasKeluar()
    {
        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        return view('modules.akuntansi.kas_keluar.create', compact('akun'));
    }

    public function storeKasKeluar(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id', // Expense/Asset
            'akun_kredit' => 'required|exists:akuntansi_akun,id', // Kas/Bank
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $jurnal = AkuntansiJurnal::create([
                    'tanggal' => $request->tanggal,
                    'no_ref' => 'KK-' . date('YmdHis'),
                    'keterangan' => $request->keterangan,
                    'tipe' => 'kas_keluar',
                ]);

                // Debit (Pengeluaran)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Kas Berkurang)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_keluar.index')->with('success', 'Kas Keluar berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyKasKeluar($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                if ($jurnal->tipe !== 'kas_keluar') {
                    throw new \Exception('Transaksi bukan Kas Keluar');
                }
                $jurnal->details()->delete();
                $jurnal->delete();
            });
            return back()->with('success', 'Transaksi Kas Keluar berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function editKasKeluar($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'kas_keluar') {
            return redirect()->route('akuntansi.kas_keluar.index')->with('error', 'Transaksi bukan Kas Keluar');
        }

        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        
        // Find debit (Expense) and credit (Kas) details
        $detailDebit = $jurnal->details->where('debit', '>', 0)->first();
        $detailKredit = $jurnal->details->where('kredit', '>', 0)->first();

        return view('modules.akuntansi.kas_keluar.edit', compact('jurnal', 'akun', 'detailDebit', 'detailKredit'));
    }

    public function updateKasKeluar(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id',
            'akun_kredit' => 'required|exists:akuntansi_akun,id',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                ]);

                $jurnal->details()->delete();

                // Debit (Pengeluaran)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Kas Berkurang)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_keluar.index')->with('success', 'Kas Keluar berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage())->withInput();
        }
    }

    // --- KAS TRANSFER ---
    public function indexKasTransfer(Request $request)
    {
        $query = AkuntansiJurnal::where('tipe', 'kas_transfer')->with('details.akun');

        // Filter Date
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_ref', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $jurnal = $query->orderBy('tanggal', 'desc')->paginate(10);
        return view('modules.akuntansi.kas_transfer.index', compact('jurnal'));
    }

    public function createKasTransfer()
    {
        $akun = AkuntansiAkun::where('tipe', 'asset')->orderBy('kode_akun')->get(); // Only Asset accounts make sense for transfer usually
        return view('modules.akuntansi.kas_transfer.create', compact('akun'));
    }

    public function storeKasTransfer(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_debit' => 'required|exists:akuntansi_akun,id', // Tujuan
            'akun_kredit' => 'required|exists:akuntansi_akun,id', // Asal
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->akun_debit == $request->akun_kredit) {
            return back()->with('error', 'Akun asal dan tujuan tidak boleh sama.')->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $jurnal = AkuntansiJurnal::create([
                    'tanggal' => $request->tanggal,
                    'no_ref' => 'KT-' . date('YmdHis'),
                    'keterangan' => $request->keterangan,
                    'tipe' => 'kas_transfer',
                ]);

                // Debit (Uang Masuk ke Tujuan)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_debit,
                    'debit' => $request->jumlah,
                    'kredit' => 0,
                ]);

                // Kredit (Uang Keluar dari Asal)
                AkuntansiJurnalDetail::create([
                    'jurnal_id' => $jurnal->id,
                    'akun_id' => $request->akun_kredit,
                    'debit' => 0,
                    'kredit' => $request->jumlah,
                ]);
            });

            return redirect()->route('akuntansi.kas_transfer.index')->with('success', 'Transfer Kas berhasil disimpan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage())->withInput();
        }
    }

    // --- SALDO AWAL ---
    public function indexSaldoAwal()
    {
        $akunAktiva = AkuntansiAkun::where('tipe', 'asset')->orderBy('kode_akun')->get();
        $akunPasiva = AkuntansiAkun::whereIn('tipe', ['liability', 'equity'])->orderBy('kode_akun')->get();
        
        $suppliers = Supplier::orderBy('nama')->get();
        $customers = Customer::orderBy('nama')->get();
        
        // Fetch existing opening balances
        $saldoAwalMap = [];
        $saldoAwalDetails = AkuntansiJurnalDetail::whereHas('jurnal', function($q) {
            $q->where('tipe', 'saldo_awal')->where('no_ref', 'like', 'SA-AK-%');
        })->get();

        foreach($saldoAwalDetails as $detail) {
            if (!isset($saldoAwalMap[$detail->akun_id])) {
                $saldoAwalMap[$detail->akun_id] = 0;
            }
            // For assets (debit normal), debit adds, kredit subtracts. 
            // Actually for opening balance, we just want the absolute value usually, 
            // but let's stick to the stored debit/credit.
            $saldoAwalMap[$detail->akun_id] += $detail->debit; // Simplified: Assuming we store positive value in debit for assets
            $saldoAwalMap[$detail->akun_id] += $detail->kredit; // And credit for liabilities
        }

        $akun = AkuntansiAkun::orderBy('kode_akun')->get(); // For dropdowns in other tabs

        $saldoAwalHutang = AkuntansiJurnal::where('tipe', 'saldo_awal')->where('no_ref', 'like', 'SA-HUT-%')->orderBy('tanggal', 'desc')->get();
        $saldoAwalPiutang = AkuntansiJurnal::where('tipe', 'saldo_awal')->where('no_ref', 'like', 'SA-PIU-%')->orderBy('tanggal', 'desc')->get();

        return view('modules.akuntansi.saldo_awal.index', compact(
            'akunAktiva', 'akunPasiva', 'saldoAwalMap', 
            'akun', 'suppliers', 'customers', 
            'saldoAwalHutang', 'saldoAwalPiutang'
        ));
    }

    public function storeSaldoAwalPerkiraan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'saldo' => 'array',
            'saldo.*' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Delete all existing "Saldo Awal Akun" (SA-AK-...)
            // This is a full reset of opening balances for accounts to ensure consistency with the batch input
            $oldJurnals = AkuntansiJurnal::where('tipe', 'saldo_awal')->where('no_ref', 'like', 'SA-AK-%')->get();
            foreach($oldJurnals as $j) {
                $j->details()->delete();
                $j->delete();
            }

            // 2. Create new Journal Entry for Opening Balance
            $jurnal = AkuntansiJurnal::create([
                'tanggal' => $request->tanggal,
                'no_ref' => 'SA-AK-' . date('YmdHis'),
                'keterangan' => 'Saldo Awal Perkiraan (Batch)',
                'tipe' => 'saldo_awal',
            ]);

            $totalDebit = 0;
            $totalKredit = 0;

            foreach ($request->saldo as $akunId => $amount) {
                if ($amount > 0) {
                    $akun = AkuntansiAkun::find($akunId);
                    if ($akun) {
                        if ($akun->tipe == 'asset' || $akun->tipe == 'expense') {
                            // Normal Debit
                            AkuntansiJurnalDetail::create([
                                'jurnal_id' => $jurnal->id,
                                'akun_id' => $akunId,
                                'debit' => $amount,
                                'kredit' => 0
                            ]);
                            $totalDebit += $amount;
                        } else {
                            // Normal Kredit
                            AkuntansiJurnalDetail::create([
                                'jurnal_id' => $jurnal->id,
                                'akun_id' => $akunId,
                                'debit' => 0,
                                'kredit' => $amount
                            ]);
                            $totalKredit += $amount;
                        }
                    }
                }
            }

            // 3. Balancing (If needed) - Usually Opening Balance should be balanced manually by the user
            // If Total Debit != Total Credit, we might warn or create a temporary balancing entry.
            // For now, let's just save it. The user sees the totals in the UI.

            DB::commit();
            return redirect()->route('akuntansi.saldo_awal.index')->with('success', 'Saldo awal perkiraan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan saldo awal: ' . $e->getMessage());
        }
    }

    public function storeSaldoAwalHutang(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:1',
            'akun_hutang' => 'required|exists:akuntansi_akun,id',
            'akun_penyeimbang' => 'required|exists:akuntansi_akun,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Create Dummy Purchase
                Pembelian::create([
                    'no_invoice' => 'SA-HUT-' . date('YmdHis'),
                    'no_po' => '-',
                    'tanggal' => $request->tanggal,
                    'supplier_id' => $request->supplier_id,
                    'ppn' => 0,
                    'total' => $request->jumlah,
                    'keterangan' => 'Saldo Awal Hutang',
                ]);

                // 2. Jurnal: Dr Penyeimbang (Equity/Inventory), Cr Hutang
                $jurnal = AkuntansiJurnal::create([
                    'tanggal' => $request->tanggal,
                    'no_ref' => 'SA-HUT-' . date('YmdHis'),
                    'keterangan' => 'Saldo Awal Hutang Supplier',
                    'tipe' => 'saldo_awal',
                ]);

                // Debit Penyeimbang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => $request->jumlah, 'kredit' => 0]);
                // Kredit Hutang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_hutang, 'debit' => 0, 'kredit' => $request->jumlah]);
            });
            return back()->with('success', 'Saldo awal hutang berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function storeSaldoAwalPiutang(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'jumlah' => 'required|numeric|min:1',
            'akun_piutang' => 'required|exists:akuntansi_akun,id',
            'akun_penyeimbang' => 'required|exists:akuntansi_akun,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Create Dummy Sales
                Penjualan::create([
                    'no_transaksi' => 'SA-PIU-' . date('YmdHis'),
                    'no_po' => '-',
                    'tanggal' => $request->tanggal,
                    'customer_id' => $request->customer_id,
                    'unit' => '-',
                    'total' => $request->jumlah,
                    'keterangan' => 'Saldo Awal Piutang',
                ]);

                // 2. Jurnal: Dr Piutang, Cr Penyeimbang
                $jurnal = AkuntansiJurnal::create([
                    'tanggal' => $request->tanggal,
                    'no_ref' => 'SA-PIU-' . date('YmdHis'),
                    'keterangan' => 'Saldo Awal Piutang Customer',
                    'tipe' => 'saldo_awal',
                ]);

                // Debit Piutang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_piutang, 'debit' => $request->jumlah, 'kredit' => 0]);
                // Kredit Penyeimbang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => 0, 'kredit' => $request->jumlah]);
            });
            return back()->with('success', 'Saldo awal piutang berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editSaldoAwalAkun($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'saldo_awal' || !str_contains($jurnal->no_ref, 'SA-AK-')) {
            return redirect()->route('akuntansi.saldo_awal.index')->with('error', 'Transaksi bukan Saldo Awal Akun');
        }

        if ($jurnal->details->count() < 2) {
             return redirect()->route('akuntansi.saldo_awal.index')->with('error', 'Data jurnal tidak valid (detail kurang).');
        }

        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        
        $detail1 = $jurnal->details[0];
        $detail2 = $jurnal->details[1];
        
        return view('modules.akuntansi.saldo_awal.edit_akun', compact('jurnal', 'akun', 'detail1', 'detail2'));
    }

    public function updateSaldoAwalAkun(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'akun_id' => 'required|exists:akuntansi_akun,id',
            'akun_penyeimbang' => 'required|exists:akuntansi_akun,id',
            'jumlah' => 'required|numeric|min:1',
            'posisi' => 'required|in:debit,kredit',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                ]);

                $jurnal->details()->delete();

                if ($request->posisi == 'debit') {
                    AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_id, 'debit' => $request->jumlah, 'kredit' => 0]);
                    AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => 0, 'kredit' => $request->jumlah]);
                } else {
                    AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_id, 'debit' => 0, 'kredit' => $request->jumlah]);
                    AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => $request->jumlah, 'kredit' => 0]);
                }
            });
            return redirect()->route('akuntansi.saldo_awal.index')->with('success', 'Saldo awal akun berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editSaldoAwalHutang($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'saldo_awal' || !str_contains($jurnal->no_ref, 'SA-HUT-')) {
            return redirect()->route('akuntansi.saldo_awal.index')->with('error', 'Transaksi bukan Saldo Awal Hutang');
        }

        $pembelian = Pembelian::where('no_invoice', $jurnal->no_ref)->firstOrFail();
        $suppliers = Supplier::orderBy('nama')->get();
        $akun = AkuntansiAkun::orderBy('kode_akun')->get();

        // Hutang: Dr Penyeimbang, Cr Hutang
        $detailHutang = $jurnal->details->where('kredit', '>', 0)->first();
        $detailPenyeimbang = $jurnal->details->where('debit', '>', 0)->first();

        return view('modules.akuntansi.saldo_awal.edit_hutang', compact('jurnal', 'pembelian', 'suppliers', 'akun', 'detailHutang', 'detailPenyeimbang'));
    }

    public function updateSaldoAwalHutang(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:1',
            'akun_hutang' => 'required|exists:akuntansi_akun,id',
            'akun_penyeimbang' => 'required|exists:akuntansi_akun,id',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                
                // Update Dummy Purchase
                $pembelian = Pembelian::where('no_invoice', $jurnal->no_ref)->first();
                if ($pembelian) {
                    $pembelian->update([
                        'tanggal' => $request->tanggal,
                        'supplier_id' => $request->supplier_id,
                        'total' => $request->jumlah,
                    ]);
                }

                // Update Jurnal
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                ]);

                $jurnal->details()->delete();

                // Debit Penyeimbang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => $request->jumlah, 'kredit' => 0]);
                // Kredit Hutang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_hutang, 'debit' => 0, 'kredit' => $request->jumlah]);
            });
            return redirect()->route('akuntansi.saldo_awal.index')->with('success', 'Saldo awal hutang berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editSaldoAwalPiutang($id)
    {
        $jurnal = AkuntansiJurnal::with('details')->findOrFail($id);
        if ($jurnal->tipe !== 'saldo_awal' || !str_contains($jurnal->no_ref, 'SA-PIU-')) {
            return redirect()->route('akuntansi.saldo_awal.index')->with('error', 'Transaksi bukan Saldo Awal Piutang');
        }

        $penjualan = Penjualan::where('no_transaksi', $jurnal->no_ref)->firstOrFail();
        $customers = Customer::orderBy('nama')->get();
        $akun = AkuntansiAkun::orderBy('kode_akun')->get();

        // Piutang: Dr Piutang, Cr Penyeimbang
        $detailPiutang = $jurnal->details->where('debit', '>', 0)->first();
        $detailPenyeimbang = $jurnal->details->where('kredit', '>', 0)->first();

        return view('modules.akuntansi.saldo_awal.edit_piutang', compact('jurnal', 'penjualan', 'customers', 'akun', 'detailPiutang', 'detailPenyeimbang'));
    }

    public function updateSaldoAwalPiutang(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'jumlah' => 'required|numeric|min:1',
            'akun_piutang' => 'required|exists:akuntansi_akun,id',
            'akun_penyeimbang' => 'required|exists:akuntansi_akun,id',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);

                // Update Dummy Sales
                $penjualan = Penjualan::where('no_transaksi', $jurnal->no_ref)->first();
                if ($penjualan) {
                    $penjualan->update([
                        'tanggal' => $request->tanggal,
                        'customer_id' => $request->customer_id,
                        'total' => $request->jumlah,
                    ]);
                }

                // Update Jurnal
                $jurnal->update([
                    'tanggal' => $request->tanggal,
                ]);

                $jurnal->details()->delete();

                // Debit Piutang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_piutang, 'debit' => $request->jumlah, 'kredit' => 0]);
                // Kredit Penyeimbang
                AkuntansiJurnalDetail::create(['jurnal_id' => $jurnal->id, 'akun_id' => $request->akun_penyeimbang, 'debit' => 0, 'kredit' => $request->jumlah]);
            });
            return redirect()->route('akuntansi.saldo_awal.index')->with('success', 'Saldo awal piutang berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroySaldoAwal($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $jurnal = AkuntansiJurnal::findOrFail($id);
                if ($jurnal->tipe !== 'saldo_awal') {
                    throw new \Exception('Transaksi bukan Saldo Awal');
                }

                // Cek jika ada dummy pembelian/penjualan terkait
                if (strpos($jurnal->no_ref, 'SA-HUT-') !== false) {
                    Pembelian::where('no_invoice', $jurnal->no_ref)->delete();
                } elseif (strpos($jurnal->no_ref, 'SA-PIU-') !== false) {
                    Penjualan::where('no_transaksi', $jurnal->no_ref)->delete();
                }

                $jurnal->details()->delete();
                $jurnal->delete();
            });
            return back()->with('success', 'Saldo awal berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus saldo awal: ' . $e->getMessage());
        }
    }

    // --- BUKU BESAR ---
    public function indexBukuBesar(Request $request)
    {
        $akun = AkuntansiAkun::orderBy('kode_akun')->get();
        $selected_akun = $request->akun_id;
        $start_date = $request->start_date ?? date('Y-m-01');
        $end_date = $request->end_date ?? date('Y-m-d');

        $details = collect();
        $saldo_awal = 0;

        if ($selected_akun) {
            // Calculate Saldo Awal (Transactions before start_date)
            $prev_details = AkuntansiJurnalDetail::where('akun_id', $selected_akun)
                ->whereHas('jurnal', function ($q) use ($start_date) {
                    $q->where('tanggal', '<', $start_date);
                })
                ->get();
            
            $akunModel = AkuntansiAkun::find($selected_akun);
            $debit_prev = $prev_details->sum('debit');
            $kredit_prev = $prev_details->sum('kredit');

            if ($akunModel->saldo_normal == 'debit') {
                $saldo_awal = $debit_prev - $kredit_prev;
            } else {
                $saldo_awal = $kredit_prev - $debit_prev;
            }

            // Get Transactions within period
            $details = AkuntansiJurnalDetail::where('akun_id', $selected_akun)
                ->whereHas('jurnal', function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('tanggal', [$start_date, $end_date]);
                })
                ->with('jurnal')
                ->get()
                ->sortBy(function($detail) {
                    return $detail->jurnal->tanggal . $detail->jurnal->created_at;
                });
        }

        return view('modules.akuntansi.buku_besar.index', compact('akun', 'details', 'selected_akun', 'start_date', 'end_date', 'saldo_awal'));
    }
}
