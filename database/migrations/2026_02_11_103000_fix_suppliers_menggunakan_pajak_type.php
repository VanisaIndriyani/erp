<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix column type for menggunakan_pajak which was incorrectly set as integer in database
        // but expected as string ('non', 'include', 'exclude') in application code.
        DB::statement("ALTER TABLE suppliers MODIFY COLUMN menggunakan_pajak VARCHAR(255) DEFAULT 'non'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not want to revert this fix as it breaks the application
    }
};
