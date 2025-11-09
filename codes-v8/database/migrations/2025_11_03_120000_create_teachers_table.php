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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('cascade');

            $table->string('slug');
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('department')->nullable();

            $table->string('profile_image')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('office_location')->nullable();
            $table->string('website_url')->nullable();

            $table->text('about_me')->nullable();

            // Structured profile data
            $table->json('research_interests')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->json('publications')->nullable();
            $table->json('projects')->nullable();
            $table->json('courses_taught')->nullable();
            $table->json('awards')->nullable();
            $table->json('social_links')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['site_id', 'slug']);
            $table->index(['site_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
