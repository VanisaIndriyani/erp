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
        Schema::create('item_opnames', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('gudang')->default('UTAMA');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('stok_sistem', 10, 2);
            $table->decimal('stok_fisik', 10, 2);
            $table->decimal('selisih', 10, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_opnames');
    }
};
