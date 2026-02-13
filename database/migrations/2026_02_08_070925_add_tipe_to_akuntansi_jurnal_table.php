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
        Schema::table('akuntansi_jurnal', function (Blueprint $table) {
            $table->enum('tipe', ['umum', 'kas_masuk', 'kas_keluar', 'kas_transfer', 'saldo_awal'])->default('umum')->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akuntansi_jurnal', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
