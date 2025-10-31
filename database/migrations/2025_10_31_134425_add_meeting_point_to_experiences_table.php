<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_meeting_point_to_experiences_table.php

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
            // Nombre descriptivo del punto de encuentro (Ej: "Entrada principal del museo")
            $table->string('meeting_point_name')->nullable()->after('location');

            // Coordenadas exactas
            $table->decimal('meeting_point_lat', 10, 7)->nullable()->after('meeting_point_name');
            $table->decimal('meeting_point_lng', 10, 7)->nullable()->after('meeting_point_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['meeting_point_name', 'meeting_point_lat', 'meeting_point_lng']);
        });
    }
};
