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
        Schema::table('experiences', function (Blueprint $table) {
            if (!Schema::hasColumn('experiences', 'status')) {
                $table->string('status')->default('draft')->after('not_includes');
            }
            if (!Schema::hasColumn('experiences', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('experiences', 'moderation_note')) {
                $table->text('moderation_note')->nullable()->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            if (Schema::hasColumn('experiences', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('experiences', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('experiences', 'moderation_note')) {
                $table->dropColumn('moderation_note');
            }
        });
    }
};

