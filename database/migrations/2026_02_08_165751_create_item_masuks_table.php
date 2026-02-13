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
        Schema::create('item_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal');
            $table->string('gudang_tujuan')->default('UTAMA');
            $table->text('keterangan')->nullable();
            $table->foreignId('akun_id')->nullable()->constrained('akuntansi_akun');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_masuks');
    }
};
