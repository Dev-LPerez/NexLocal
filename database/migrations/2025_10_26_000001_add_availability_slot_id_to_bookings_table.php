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
        Schema::table('bookings', function (Blueprint $table) {
            // Añadir la clave foránea al slot de disponibilidad
            $table->foreignId('availability_slot_id')
                  ->after('experience_id')
                  ->nullable()
                  ->constrained('availability_slots')
                  ->onDelete('set null');

            // Hacer booking_date nullable
            $table->dateTime('booking_date')->nullable()->change();
        });
        // Si necesitas hacer NOT NULL después de migrar datos, puedes hacerlo con DB::statement
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['availability_slot_id']);
            $table->dropColumn('availability_slot_id');
            $table->dateTime('booking_date')->nullable(false)->change();
        });
    }
};

