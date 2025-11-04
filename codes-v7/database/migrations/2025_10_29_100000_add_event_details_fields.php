<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('venue')->nullable();
            $table->string('category')->nullable();
            $table->string('registration')->nullable();
            $table->string('fee')->nullable();
            $table->string('organizer')->nullable();
            $table->string('participants')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'venue',
                'category',
                'registration',
                'fee',
                'organizer',
                'participants',
            ]);
        });
    }
};