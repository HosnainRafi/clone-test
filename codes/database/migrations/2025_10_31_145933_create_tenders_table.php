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
    Schema::create('tenders', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('tender_number')->nullable(); // e.g., "MBSTU/2025/001"
        $table->text('description')->nullable();
        $table->string('slug')->unique()->nullable()->after('tender_number');
        $table->date('published_at');
        $table->date('closing_at');
        $table->json('attachments')->nullable(); // To store an array of file paths
        $table->boolean('is_active')->default(true);
        $table->integer('sort_order')->default(0);
        $table->foreignId('site_id')->constrained()->onDelete('cascade');
        $table->foreignId('created_by')->nullable()->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
