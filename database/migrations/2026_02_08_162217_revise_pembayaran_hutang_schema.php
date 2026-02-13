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
        // Drop old tables if they exist (re-structuring)
        Schema::dropIfExists('pembayaran_hutang');
        Schema::dropIfExists('pembayaran_piutang');

        // Re-create Pembayaran Hutang Header
        Schema::create('pembayaran_hutang', function (Blueprint $table) {
            $table->id();
            $table->string('no_bayar')->unique();
            $table->date('tanggal');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('cara_bayar'); // Tunai, Transfer, Cek/Giro
            $table->foreignId('akun_id')->nullable()->constrained('akuntansi_akun'); // Source of funds (Kas/Bank)
            $table->decimal('total_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Who created it
            $table->timestamps();
        });

        // Create Pembayaran Hutang Detail
        Schema::create('pembayaran_hutang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_hutang_id')->constrained('pembayaran_hutang')->onDelete('cascade');
            $table->foreignId('pembelian_id')->constrained('pembelian');
            $table->decimal('jumlah_bayar', 15, 2); // Amount paid for this invoice
            $table->decimal('potongan', 15, 2)->default(0); // Discount if any
            $table->timestamps();
        });

        // Add status columns to Pembelian
        Schema::table('pembelian', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian', 'status_pembayaran')) {
                $table->enum('status_pembayaran', ['belum', 'partial', 'lunas'])->default('belum')->after('total');
            }
            if (!Schema::hasColumn('pembelian', 'jumlah_terbayar')) {
                $table->decimal('jumlah_terbayar', 15, 2)->default(0)->after('status_pembayaran');
            }
            if (!Schema::hasColumn('pembelian', 'gudang')) {
                 $table->string('gudang')->nullable()->after('supplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang_detail');
        Schema::dropIfExists('pembayaran_hutang');
        
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'jumlah_terbayar', 'gudang']);
        });
    }
};
