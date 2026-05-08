<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('local_businesses', function (Blueprint $table) {
            $table->id();
            // Relación 1:1 con users. Cascade para que si se borra el usuario, se borre el negocio
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // Ej: Artesanías, Gastronomía
            $table->string('address')->nullable();
            $table->string('cover_image_path', 2048)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_businesses');
    }
};
