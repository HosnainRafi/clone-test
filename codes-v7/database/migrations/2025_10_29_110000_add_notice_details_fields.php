<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->string('priority')->default('medium');
            $table->string('department')->nullable();
            $table->json('attachments')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->string('category')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn([
                'priority',
                'department',
                'attachments',
                'valid_until',
                'category',
            ]);
        });
    }
};
