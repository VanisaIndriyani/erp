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
        Schema::create('pembayaran_hutang', function (Blueprint $table) {
            $table->id();
            $table->string('no_bayar')->unique();
            $table->date('tanggal');
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('pembayaran_piutang', function (Blueprint $table) {
            $table->id();
            $table->string('no_bayar')->unique();
            $table->date('tanggal');
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_hutang');
        Schema::dropIfExists('pembayaran_piutang');
    }
};
