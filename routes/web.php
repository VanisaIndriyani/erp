<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\LaporanController::class, 'index'])->name('index');
        Route::get('/pembelian', [App\Http\Controllers\LaporanController::class, 'pembelian'])->name('pembelian');
        Route::get('/penjualan', [App\Http\Controllers\LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/hutang', [App\Http\Controllers\LaporanController::class, 'hutang'])->name('hutang');
        Route::get('/piutang', [App\Http\Controllers\LaporanController::class, 'piutang'])->name('piutang');
        Route::get('/persediaan', [App\Http\Controllers\LaporanController::class, 'persediaan'])->name('persediaan');
        Route::get('/buku-kas', [App\Http\Controllers\LaporanController::class, 'bukuKas'])->name('buku_kas');
    });

    // Pengaturan Routes
    Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
        Route::get('/', [App\Http\Controllers\PengaturanController::class, 'index'])->name('index');
        Route::get('/monitoring-po', [App\Http\Controllers\PengaturanController::class, 'monitoringPO'])->name('monitoring_po');
        Route::get('/monitoring-transaksi', [App\Http\Controllers\PengaturanController::class, 'monitoringTransaksi'])->name('monitoring_transaksi');
    });

    // Master Data Routes
    Route::resource('items', App\Http\Controllers\ItemController::class);
    Route::get('items/{id}/history', [App\Http\Controllers\ItemController::class, 'history'])->name('items.history');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('customers', App\Http\Controllers\CustomerController::class);

    // Transaction Routes
    Route::prefix('pembelian')->name('pembelian.')->group(function () {
        Route::resource('transaksi', App\Http\Controllers\PembelianController::class);
        
        // Pembayaran Hutang Routes (Updated to Multi-Invoice)
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [App\Http\Controllers\PembayaranHutangController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\PembayaranHutangController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PembayaranHutangController::class, 'store'])->name('store');
            Route::get('/unpaid/{supplier_id}', [App\Http\Controllers\PembayaranHutangController::class, 'getUnpaidInvoices'])->name('unpaid');
        });
    });

    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::resource('transaksi', App\Http\Controllers\PenjualanController::class);
        
        // Pembayaran Piutang Routes
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [App\Http\Controllers\PembayaranPiutangController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\PembayaranPiutangController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\PembayaranPiutangController::class, 'store'])->name('store');
            Route::get('/unpaid/{customer_id}', [App\Http\Controllers\PembayaranPiutangController::class, 'getUnpaidInvoices'])->name('unpaid');
        });
    });

    // Persediaan Routes
    Route::get('persediaan/masuk', [App\Http\Controllers\PersediaanController::class, 'masuk'])->name('persediaan.masuk');
    Route::get('persediaan/masuk/create', [App\Http\Controllers\PersediaanController::class, 'createMasuk'])->name('persediaan.createMasuk');
    Route::get('persediaan/masuk/export', [App\Http\Controllers\PersediaanController::class, 'exportMasuk'])->name('persediaan.exportMasuk');
    Route::post('persediaan/masuk', [App\Http\Controllers\PersediaanController::class, 'storeMasuk'])->name('persediaan.storeMasuk');
    Route::get('persediaan/masuk/{id}', [App\Http\Controllers\PersediaanController::class, 'showMasuk'])->name('persediaan.showMasuk');
    Route::get('persediaan/masuk/{id}/print-pdf', [App\Http\Controllers\PersediaanController::class, 'printMasukPdf'])->name('persediaan.printMasukPdf');
    Route::get('persediaan/keluar', [App\Http\Controllers\PersediaanController::class, 'keluar'])->name('persediaan.keluar');
    Route::get('persediaan/keluar/create', [App\Http\Controllers\PersediaanController::class, 'createKeluar'])->name('persediaan.createKeluar');
    Route::get('persediaan/keluar/export', [App\Http\Controllers\PersediaanController::class, 'exportKeluar'])->name('persediaan.exportKeluar');
    Route::post('persediaan/keluar', [App\Http\Controllers\PersediaanController::class, 'storeKeluar'])->name('persediaan.storeKeluar');
    Route::get('persediaan/keluar/{id}', [App\Http\Controllers\PersediaanController::class, 'showKeluar'])->name('persediaan.showKeluar');
    Route::get('persediaan/keluar/{id}/print-pdf', [App\Http\Controllers\PersediaanController::class, 'printKeluarPdf'])->name('persediaan.printKeluarPdf');
    Route::get('persediaan/opname', [App\Http\Controllers\PersediaanController::class, 'opname'])->name('persediaan.opname');
    Route::get('persediaan/opname/export', [App\Http\Controllers\PersediaanController::class, 'exportOpname'])->name('persediaan.exportOpname');
    Route::post('persediaan/opname', [App\Http\Controllers\PersediaanController::class, 'storeOpname'])->name('persediaan.storeOpname');
    Route::get('persediaan/transfer', [App\Http\Controllers\PersediaanController::class, 'transfer'])->name('persediaan.transfer');
    Route::get('persediaan/transfer/create', [App\Http\Controllers\PersediaanController::class, 'createTransfer'])->name('persediaan.createTransfer');
    Route::get('persediaan/transfer/export', [App\Http\Controllers\PersediaanController::class, 'exportTransfer'])->name('persediaan.exportTransfer');
    Route::post('persediaan/transfer', [App\Http\Controllers\PersediaanController::class, 'storeTransfer'])->name('persediaan.storeTransfer');
    Route::get('persediaan/transfer/{id}', [App\Http\Controllers\PersediaanController::class, 'showTransfer'])->name('persediaan.showTransfer');
    Route::get('persediaan/transfer/{id}/print', [App\Http\Controllers\PersediaanController::class, 'printTransfer'])->name('persediaan.printTransfer');
    Route::get('persediaan/transfer/{id}/print-pdf', [App\Http\Controllers\PersediaanController::class, 'printTransferPdf'])->name('persediaan.printTransferPdf');

    // Akuntansi Routes
    Route::get('akuntansi/jurnal', [App\Http\Controllers\AkuntansiController::class, 'indexJurnal'])->name('akuntansi.jurnal.index');
    Route::get('akuntansi/akun', [App\Http\Controllers\AkuntansiController::class, 'indexAkun'])->name('akuntansi.akun.index');
    Route::post('akuntansi/akun', [App\Http\Controllers\AkuntansiController::class, 'storeAkun'])->name('akuntansi.akun.store');
    Route::put('akuntansi/akun/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateAkun'])->name('akuntansi.akun.update');
    Route::delete('akuntansi/akun/{id}', [App\Http\Controllers\AkuntansiController::class, 'destroyAkun'])->name('akuntansi.akun.destroy');

    Route::get('akuntansi/kas-masuk', [App\Http\Controllers\AkuntansiController::class, 'indexKasMasuk'])->name('akuntansi.kas_masuk.index');
    Route::get('akuntansi/kas-masuk/create', [App\Http\Controllers\AkuntansiController::class, 'createKasMasuk'])->name('akuntansi.kas_masuk.create');
    Route::post('akuntansi/kas-masuk', [App\Http\Controllers\AkuntansiController::class, 'storeKasMasuk'])->name('akuntansi.kas_masuk.store');
    Route::get('akuntansi/kas-masuk/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editKasMasuk'])->name('akuntansi.kas_masuk.edit');
    Route::put('akuntansi/kas-masuk/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateKasMasuk'])->name('akuntansi.kas_masuk.update');
    Route::delete('akuntansi/kas-masuk/{id}', [App\Http\Controllers\AkuntansiController::class, 'destroyKasMasuk'])->name('akuntansi.kas_masuk.destroy');

    Route::get('akuntansi/kas-keluar', [App\Http\Controllers\AkuntansiController::class, 'indexKasKeluar'])->name('akuntansi.kas_keluar.index');
    Route::get('akuntansi/kas-keluar/create', [App\Http\Controllers\AkuntansiController::class, 'createKasKeluar'])->name('akuntansi.kas_keluar.create');
    Route::post('akuntansi/kas-keluar', [App\Http\Controllers\AkuntansiController::class, 'storeKasKeluar'])->name('akuntansi.kas_keluar.store');
    Route::get('akuntansi/kas-keluar/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editKasKeluar'])->name('akuntansi.kas_keluar.edit');
    Route::put('akuntansi/kas-keluar/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateKasKeluar'])->name('akuntansi.kas_keluar.update');
    Route::delete('akuntansi/kas-keluar/{id}', [App\Http\Controllers\AkuntansiController::class, 'destroyKasKeluar'])->name('akuntansi.kas_keluar.destroy');

    Route::get('akuntansi/kas-transfer', [App\Http\Controllers\AkuntansiController::class, 'indexKasTransfer'])->name('akuntansi.kas_transfer.index');
    Route::get('akuntansi/kas-transfer/create', [App\Http\Controllers\AkuntansiController::class, 'createKasTransfer'])->name('akuntansi.kas_transfer.create');
    Route::post('akuntansi/kas-transfer', [App\Http\Controllers\AkuntansiController::class, 'storeKasTransfer'])->name('akuntansi.kas_transfer.store');
    Route::get('akuntansi/kas-transfer/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editKasTransfer'])->name('akuntansi.kas_transfer.edit');
    Route::put('akuntansi/kas-transfer/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateKasTransfer'])->name('akuntansi.kas_transfer.update');
    Route::delete('akuntansi/kas-transfer/{id}', [App\Http\Controllers\AkuntansiController::class, 'destroyKasTransfer'])->name('akuntansi.kas_transfer.destroy');

    Route::get('akuntansi/saldo-awal', [App\Http\Controllers\AkuntansiController::class, 'indexSaldoAwal'])->name('akuntansi.saldo_awal.index');
    Route::post('akuntansi/saldo-awal/akun', [App\Http\Controllers\AkuntansiController::class, 'storeSaldoAwalPerkiraan'])->name('akuntansi.saldo_awal.store_akun');
    Route::get('akuntansi/saldo-awal/akun/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editSaldoAwalAkun'])->name('akuntansi.saldo_awal.edit_akun');
    Route::put('akuntansi/saldo-awal/akun/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateSaldoAwalAkun'])->name('akuntansi.saldo_awal.update_akun');

    Route::post('akuntansi/saldo-awal/hutang', [App\Http\Controllers\AkuntansiController::class, 'storeSaldoAwalHutang'])->name('akuntansi.saldo_awal.store_hutang');
    Route::get('akuntansi/saldo-awal/hutang/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editSaldoAwalHutang'])->name('akuntansi.saldo_awal.edit_hutang');
    Route::put('akuntansi/saldo-awal/hutang/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateSaldoAwalHutang'])->name('akuntansi.saldo_awal.update_hutang');

    Route::post('akuntansi/saldo-awal/piutang', [App\Http\Controllers\AkuntansiController::class, 'storeSaldoAwalPiutang'])->name('akuntansi.saldo_awal.store_piutang');
    Route::get('akuntansi/saldo-awal/piutang/{id}/edit', [App\Http\Controllers\AkuntansiController::class, 'editSaldoAwalPiutang'])->name('akuntansi.saldo_awal.edit_piutang');
    Route::put('akuntansi/saldo-awal/piutang/{id}', [App\Http\Controllers\AkuntansiController::class, 'updateSaldoAwalPiutang'])->name('akuntansi.saldo_awal.update_piutang');

    Route::delete('akuntansi/saldo-awal/{id}', [App\Http\Controllers\AkuntansiController::class, 'destroySaldoAwal'])->name('akuntansi.saldo_awal.destroy');

    Route::get('akuntansi/buku-besar', [App\Http\Controllers\AkuntansiController::class, 'indexBukuBesar'])->name('akuntansi.buku_besar.index');
});
