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
        // Modify Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'gudang', 'kasir', 'keuangan'])->default('admin')->after('email');
            }
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_item')->unique();
            $table->string('nama_item');
            $table->string('jenis')->nullable();
            $table->string('merk')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_pokok', 15, 2)->default(0);
            $table->decimal('up_persen', 5, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->boolean('pajak_include')->default(false);
            $table->timestamps();
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->integer('jatuh_tempo')->default(30); // hari
            $table->decimal('nilai_pajak', 5, 2)->default(0);
            $table->string('menggunakan_pajak')->default('non'); // non, include, exclude
            $table->timestamps();
        });

        // Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->integer('jatuh_tempo')->default(30); // hari
            $table->timestamps();
        });

        // Pembelian Header
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('no_po')->nullable();
            $table->date('tanggal');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('gudang')->nullable(); // Masuk ke
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Pembelian Detail
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // Penjualan Header
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->string('no_po')->nullable();
            $table->date('tanggal');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->string('unit')->nullable(); // info tambahan
            $table->text('keterangan')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });

        // Penjualan Detail
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // Kartu Stok
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['masuk', 'keluar', 'opname', 'transfer']);
            $table->string('no_referensi');
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('saldo');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Akuntansi Akun
        Schema::create('akuntansi_akun', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique();
            $table->string('nama_akun');
            $table->enum('tipe', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('saldo_normal', ['debit', 'kredit']);
            $table->timestamps();
        });

        // Akuntansi Jurnal Header
        Schema::create('akuntansi_jurnal', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_ref')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Akuntansi Jurnal Detail
        Schema::create('akuntansi_jurnal_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_id')->constrained('akuntansi_jurnal')->onDelete('cascade');
            $table->foreignId('akun_id')->constrained('akuntansi_akun');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akuntansi_jurnal_detail');
        Schema::dropIfExists('akuntansi_jurnal');
        Schema::dropIfExists('akuntansi_akun');
        Schema::dropIfExists('kartu_stok');
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('items');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role']);
        });
    }
};
