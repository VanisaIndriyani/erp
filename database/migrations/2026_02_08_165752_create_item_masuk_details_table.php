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
        Schema::create('item_masuk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_masuk_id')->constrained('item_masuks')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('qty', 10, 2);
            $table->string('satuan')->nullable();
            $table->decimal('harga', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_masuk_details');
    }
};
