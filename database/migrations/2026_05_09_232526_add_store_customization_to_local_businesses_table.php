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
        Schema::table('local_businesses', function (Blueprint $table) {
            $table->string('banner_image_path')->nullable();
            $table->json('theme_colors')->nullable();
            $table->json('social_links')->nullable();
            $table->json('operating_hours')->nullable();
            $table->json('payment_methods')->nullable();
            $table->string('welcome_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('local_businesses', function (Blueprint $table) {
            $table->dropColumn([
                'banner_image_path',
                'theme_colors',
                'social_links',
                'operating_hours',
                'payment_methods',
                'welcome_message'
            ]);
        });
    }
};
