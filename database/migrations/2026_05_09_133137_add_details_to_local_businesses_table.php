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
            $table->string('business_type')->nullable()->after('description');
            $table->integer('price_range')->nullable()->after('category');
            $table->integer('capacity')->nullable()->after('price_range');
            $table->json('services')->nullable()->after('capacity');
            $table->decimal('lat', 10, 8)->nullable()->after('address');
            $table->decimal('lng', 11, 8)->nullable()->after('lat');
            $table->string('phone')->nullable()->after('lng');
            $table->string('email')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('local_businesses', function (Blueprint $table) {
            $table->dropColumn([
                'business_type',
                'price_range',
                'capacity',
                'services',
                'lat',
                'lng',
                'phone',
                'email'
            ]);
        });
    }
};
