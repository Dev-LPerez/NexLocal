<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para SQLite, necesitamos recrear la tabla con las nuevas columnas
        // SQLite no soporta ALTER COLUMN ni ENUM directamente

        Schema::table('bookings', function (Blueprint $table) {
            // Agregar las nuevas columnas booleanas para el sistema de finalización de dos pasos
            $table->boolean('tourist_confirmed_completed')->default(false)->after('status');
            $table->boolean('guide_confirmed_completed')->default(false)->after('tourist_confirmed_completed');
        });

        // En SQLite, la columna 'status' ya permite cualquier string
        // Solo necesitamos asegurarnos de que los valores sean válidos
        // Los valores permitidos serán: 'pending', 'confirmed', 'in_progress', 'completed', 'cancelled'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['tourist_confirmed_completed', 'guide_confirmed_completed']);
        });
    }
};

