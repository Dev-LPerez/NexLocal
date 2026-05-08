<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relación: Este ítem pertenece a un Pedido específico
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: El ítem hace referencia a un Producto específico
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}