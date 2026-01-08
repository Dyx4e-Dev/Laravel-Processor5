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
        // First, make the column nullable to avoid enum constraint during update
        Schema::table('laptops', function (Blueprint $table) {
            $table->string('app_usage')->nullable()->change();
        });

        // Update existing data to match new enum values
        DB::table('laptops')->where('app_usage', 'single-thread')->update(['app_usage' => 'single-threaded']);
        DB::table('laptops')->where('app_usage', 'multi-thread')->update(['app_usage' => 'multi-threaded']);

        // Now change back to enum with new values
        Schema::table('laptops', function (Blueprint $table) {
            $table->enum('app_usage', ['single-threaded', 'multi-threaded'])->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->enum('app_usage', ['single-thread', 'multi-thread'])->change();
        });
    }
};
