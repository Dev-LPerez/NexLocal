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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('ID del turista que reserva'); // FK a users
            $table->foreignId('experience_id')->constrained()->onDelete('cascade')->comment('ID de la experiencia reservada'); // FK a experiences
            $table->foreignId('availability_slot_id')->nullable()->constrained('availability_slots')->onDelete('set null');
            $table->dateTime('booking_date')->nullable()->comment('Fecha y hora seleccionada para la experiencia');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->comment('Estado de la reserva');
            $table->decimal('total_amount', 10, 2)->default(0)->comment('Precio pagado por la reserva');
            // Podríamos añadir más campos como número de personas, precio total si varía, etc.
            // Por ahora, lo mantenemos simple.
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
