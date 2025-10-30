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
        Schema::table('news', function (Blueprint $table) {
            // Only add the column if it doesn't already exist (safe re-run)
            if (!Schema::hasColumn('news', 'site_id')) {
                $table->foreignId('site_id')
                    ->nullable()
                    ->constrained('sites')
                    ->onDelete('cascade')
                    ->after('meta_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Only drop if the column exists
            if (Schema::hasColumn('news', 'site_id')) {
                // dropForeign will fail if constraint name differs; attempt safely
                try {
                    $table->dropForeign(['site_id']);
                } catch (\Throwable $e) {
                    // ignore if foreign doesn't exist
                }
                $table->dropColumn('site_id');
            }
        });
    }
};
