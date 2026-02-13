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
        // Drop existing tables if they exist (to restructure)
        Schema::dropIfExists('pembayaran_piutang');

        // Create Header Table
        Schema::create('pembayaran_piutang', function (Blueprint $table) {
            $table->id();
            $table->string('no_bayar')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('cara_bayar'); // Tunai, Transfer, Cek/Giro
            $table->string('no_ref')->nullable(); // Alun Perkiraan / No Ref Bank
            $table->foreignId('akun_id')->nullable()->constrained('akuntansi_akun'); // Destination of funds (Kas/Bank)
            $table->decimal('total_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Who created it
            $table->timestamps();
        });

        // Create Detail Table
        Schema::create('pembayaran_piutang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_piutang_id')->constrained('pembayaran_piutang')->onDelete('cascade');
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->decimal('jumlah_bayar', 15, 2); // Amount paid for this invoice
            $table->decimal('potongan', 15, 2)->default(0); // Discount if any
            $table->timestamps();
        });

        // Update Penjualan Table
        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'status_pembayaran')) {
                $table->enum('status_pembayaran', ['belum', 'partial', 'lunas'])->default('belum')->after('total');
            }
            if (!Schema::hasColumn('penjualan', 'jumlah_terbayar')) {
                $table->decimal('jumlah_terbayar', 15, 2)->default(0)->after('status_pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutang_detail');
        Schema::dropIfExists('pembayaran_piutang');
        
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'jumlah_terbayar']);
        });
    }
};
