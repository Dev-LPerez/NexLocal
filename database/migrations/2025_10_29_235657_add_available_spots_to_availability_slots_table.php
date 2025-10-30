<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AvailabilitySlot; // Importar el modelo para actualizar datos existentes

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ya no es necesario, la columna se crea desde el inicio en la migración principal
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ya no es necesario, la columna se elimina en la migración principal
    }
};
