<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add indexes to improve dashboard query performance
     */
    public function up(): void
    {
        // Index on quiz_results for fast counting and date range queries
        // Only add composite index which may not exist
        Schema::table('quiz_results', function (Blueprint $table) {
            // Check if the composite index exists before adding
            $indexes = DB::select("SHOW INDEX FROM quiz_results WHERE Key_name = 'quiz_results_team_id_created_at_index'");
            if (empty($indexes)) {
                $table->index(['team_id', 'created_at']);
            }
        });

        // Add optional status indexes for benchmarks and quizzes if columns exist
        if (Schema::hasColumn('benchmarks', 'status')) {
            Schema::table('benchmarks', function (Blueprint $table) {
                $indexes = DB::select("SHOW INDEX FROM benchmarks WHERE Key_name = 'benchmarks_status_index'");
                if (empty($indexes)) {
                    $table->index('status');
                }
            });
        }

        if (Schema::hasColumn('quizzes', 'status')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $indexes = DB::select("SHOW INDEX FROM quizzes WHERE Key_name = 'quizzes_status_index'");
                if (empty($indexes)) {
                    $table->index('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            try {
                $table->dropIndex('quiz_results_team_id_created_at_index');
            } catch (\Exception $e) {
                // Index might not exist
            }
        });

        if (Schema::hasColumn('benchmarks', 'status')) {
            Schema::table('benchmarks', function (Blueprint $table) {
                try {
                    $table->dropIndex('benchmarks_status_index');
                } catch (\Exception $e) {}
            });
        }

        if (Schema::hasColumn('quizzes', 'status')) {
            Schema::table('quizzes', function (Blueprint $table) {
                try {
                    $table->dropIndex('quizzes_status_index');
                } catch (\Exception $e) {}
            });
        }
    }
};
