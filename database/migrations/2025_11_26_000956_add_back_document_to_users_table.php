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
        Schema::table('users', function (Blueprint $table) {
            // Documento de identidad parte trasera
            $table->string('identity_document_back_path', 2048)->nullable()->after('identity_document_path');

            // Estado de verificación: null (no enviado), 'pending' (en revisión), 'approved' (aprobado), 'rejected' (rechazado)
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->nullable()->after('identity_verified_at');

            // Razón de rechazo (si aplica)
            $table->text('rejection_reason')->nullable()->after('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['identity_document_back_path', 'verification_status', 'rejection_reason']);
        });
    }
};
