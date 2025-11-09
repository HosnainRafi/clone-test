<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->text('content')->nullable();
            $table->string('authors');
            $table->string('publication_type')->default('article');

            // Journal & Conference Details
            $table->string('journal')->nullable();
            $table->string('journal_rank')->nullable();
            $table->decimal('impact_factor', 8, 2)->nullable();
            $table->string('conference')->nullable();

            // Publication Specifics
            $table->string('publisher')->nullable();
            $table->string('year');
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('pages')->nullable();
            $table->string('doi')->nullable(); // Digital Object Identifier

            // Citation and Metrics
            $table->string('citation')->nullable();
            $table->integer('citation_count')->nullable();
            $table->integer('download_count')->nullable();

            // Flags and Display Options
            $table->boolean('is_active')->default(true);
            $table->boolean('is_open_access')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('fallback_gradient')->nullable();

            // URLs, Attachments and Metadata
            $table->string('url')->nullable(); // External link to publication
            $table->string('link')->nullable(); // Internal slug-based link
            $table->string('image')->nullable();
            $table->json('keywords')->nullable();
            $table->json('attachments')->nullable();

            // Timestamps and User IDs
            $table->integer('sort_order')->default(0);
            $table->date('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
