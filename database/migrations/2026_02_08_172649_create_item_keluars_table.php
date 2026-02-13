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
        Schema::create('item_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal');
            $table->string('gudang_asal')->nullable()->default('UTAMA');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Make nullable to avoid issues if user not logged in or seeded
            $table->timestamps();
        });

        Schema::create('item_keluar_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_keluar_id')->constrained('item_keluars')->onDelete('cascade');
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
        Schema::dropIfExists('item_keluar_details');
        Schema::dropIfExists('item_keluars');
    }
};
