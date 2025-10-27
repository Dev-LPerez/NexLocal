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
            // Añade la columna para la ruta de la imagen después de 'price'
            // Será nullable por si alguna experiencia no tiene imagen al principio
            $table->string('image_path', 2048)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            // Verifica si la columna existe antes de intentar eliminarla
            if (Schema::hasColumn('experiences', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};

