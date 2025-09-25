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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea para el guía
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('duration');
            $table->decimal('price', 8, 2); // 8 dígitos en total, 2 para decimales
            $table->json('includes')->nullable(); // Guardaremos listas de "qué incluye"
            $table->json('not_includes')->nullable(); // Y "qué no incluye"
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
