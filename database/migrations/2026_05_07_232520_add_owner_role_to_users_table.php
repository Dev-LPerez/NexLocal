<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Eliminamos la restricción actual
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check;');

        // 2. Creamos la nueva restricción incluyendo 'owner'
        // (Asegúrate de que 'tourist', 'guide' y 'admin' coincidan con los roles exactos que ya manejabas)
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['tourist'::text, 'guide'::text, 'admin'::text, 'owner'::text]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si hacemos un rollback, volvemos a dejar solo los 3 roles originales
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check;');
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role::text = ANY (ARRAY['tourist'::text, 'guide'::text, 'admin'::text]))");
    }
};