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
        Schema::create('best_cpus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('benchmark_id')->constrained()->onDelete('cascade');
            $table->string('cpu_name');  // "Ryzen 5 7600X"
            $table->string('description'); // "High IPC for gaming"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_cpus');
    }
};
