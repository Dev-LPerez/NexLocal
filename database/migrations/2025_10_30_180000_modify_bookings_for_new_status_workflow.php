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
        Schema::table('bookings', function (Blueprint $table) {
            // Añadir columnas de pago si no existen
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('bookings', 'payment_intent_id')) {
                $table->string('payment_intent_id')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            // Añadir columnas booleanas para el sistema de finalización de dos pasos
            if (!Schema::hasColumn('bookings', 'tourist_confirmed_completed')) {
                $table->boolean('tourist_confirmed_completed')->default(false)->after('status');
            }
            if (!Schema::hasColumn('bookings', 'guide_confirmed_completed')) {
                $table->boolean('guide_confirmed_completed')->default(false)->after('tourist_confirmed_completed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('bookings', 'payment_intent_id')) {
                $table->dropColumn('payment_intent_id');
            }
            if (Schema::hasColumn('bookings', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('bookings', 'tourist_confirmed_completed')) {
                $table->dropColumn('tourist_confirmed_completed');
            }
            if (Schema::hasColumn('bookings', 'guide_confirmed_completed')) {
                $table->dropColumn('guide_confirmed_completed');
            }
        });
    }
};
