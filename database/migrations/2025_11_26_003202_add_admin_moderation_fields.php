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
        // Campos para suspensión de usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false)->after('verification_status');
            $table->text('suspension_reason')->nullable()->after('is_suspended');
            $table->timestamp('suspended_at')->nullable()->after('suspension_reason');
        });

        // Campos para moderación de experiencias
        Schema::table('experiences', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'hidden', 'rejected'])->default('published')->after('meeting_point');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->text('moderation_note')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'suspension_reason', 'suspended_at']);
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_featured', 'moderation_note']);
        });
    }
};
