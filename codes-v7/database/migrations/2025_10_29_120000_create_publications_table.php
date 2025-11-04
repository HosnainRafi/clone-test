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
            $table->string('journal')->nullable();
            $table->string('conference')->nullable();
            $table->string('publication_type')->default('article'); // article, conference, book, etc.
            $table->string('doi')->nullable();
            $table->string('url')->nullable();
            $table->string('publisher')->nullable();
            $table->string('year');
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('pages')->nullable();
            $table->string('citation')->nullable();
            $table->json('keywords')->nullable();
            $table->json('attachments')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->date('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('journal_rank')->nullable()->after('journal');
            $table->decimal('impact_factor', 8, 2)->nullable()->after('journal_rank');
            $table->integer('citation_count')->nullable()->after('citation');
            $table->integer('download_count')->nullable()->after('citation_count');
            $table->boolean('is_open_access')->default(false)->after('download_count');
            $table->boolean('is_featured')->default(false)->after('is_open_access');
            $table->string('fallback_gradient')->nullable()->after('is_featured');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
