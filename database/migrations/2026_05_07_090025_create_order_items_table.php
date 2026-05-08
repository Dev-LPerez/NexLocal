<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::dropIfExists('order_items');
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Relación con el pedido principal
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // Producto específico que se compró
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->integer('quantity');
            // Guardamos el precio unitario en el momento de la compra por si el precio del producto cambia en el futuro
            $table->decimal('unit_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};