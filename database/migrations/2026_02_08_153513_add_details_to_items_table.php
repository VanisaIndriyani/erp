<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('tipe_item', ['inventory', 'jasa', 'rakitan', 'non-inventory'])->default('inventory')->after('nama_item');
            $table->string('rak')->nullable()->after('satuan');
            $table->enum('hpp_system', ['FIFO', 'LIFO', 'AVERAGE'])->default('AVERAGE')->after('rak');
            $table->boolean('status_jual')->default(true)->comment('true=Masih dijual, false=Discontinue')->after('stok_minimum');
            $table->string('barcode')->nullable()->after('kode_item');
            $table->enum('pilihan_harga', ['satu_harga', 'satuan', 'level', 'jumlah'])->default('satu_harga')->after('harga_jual');
            $table->integer('poin_dasar')->default(0)->after('pilihan_harga');
            $table->decimal('komisi_sales', 15, 2)->default(0)->after('poin_dasar');
            
            // Accounting Mapping
            $table->foreignId('akun_hpp_id')->nullable()->constrained('akuntansi_akun')->onDelete('set null');
            $table->foreignId('akun_penjualan_id')->nullable()->constrained('akuntansi_akun')->onDelete('set null');
            $table->foreignId('akun_persediaan_id')->nullable()->constrained('akuntansi_akun')->onDelete('set null');
            $table->foreignId('akun_biaya_non_inventory_id')->nullable()->constrained('akuntansi_akun')->onDelete('set null');
            $table->foreignId('akun_persediaan_dalam_proses_id')->nullable()->constrained('akuntansi_akun')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['akun_hpp_id']);
            $table->dropForeign(['akun_penjualan_id']);
            $table->dropForeign(['akun_persediaan_id']);
            $table->dropForeign(['akun_biaya_non_inventory_id']);
            $table->dropForeign(['akun_persediaan_dalam_proses_id']);
            
            $table->dropColumn([
                'tipe_item',
                'rak',
                'hpp_system',
                'status_jual',
                'barcode',
                'pilihan_harga',
                'poin_dasar',
                'komisi_sales',
                'akun_hpp_id',
                'akun_penjualan_id',
                'akun_persediaan_id',
                'akun_biaya_non_inventory_id',
                'akun_persediaan_dalam_proses_id'
            ]);
        });
    }
};
