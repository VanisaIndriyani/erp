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
        Schema::create('item_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal');
            $table->string('gudang_asal');
            $table->string('gudang_tujuan');
            $table->string('no_sj')->nullable();
            $table->string('pic')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('item_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_transfer_id')->constrained('item_transfers')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('qty', 10, 2);
            $table->string('satuan')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_transfer_details');
        Schema::dropIfExists('item_transfers');
    }
};
