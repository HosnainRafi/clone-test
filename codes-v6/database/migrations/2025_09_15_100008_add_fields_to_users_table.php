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
        Schema::table('users', function (Blueprint $table) {
            // Add role_id foreign key
            $table->foreignId('role_id')->nullable()->after('password');
            
            // Add profile photo path
            $table->string('profile_photo_path', 2048)->nullable()->after('role_id');
            
            // Add active status flag
            $table->boolean('is_active')->default(true)->after('profile_photo_path');
            
            // Add soft deletes
            $table->softDeletes();
            
            // Add created_by and updated_by fields
            $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('profile_photo_path');
            $table->dropColumn('is_active');
            $table->dropSoftDeletes();
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
};
