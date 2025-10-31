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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('ID del turista que deja la reseña');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->comment('ID de la reserva asociada');
            $table->unsignedTinyInteger('rating')->comment('Calificación de 1 a 5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Opcional: Evitar reseñas duplicadas por reserva
            $table->unique(['booking_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

