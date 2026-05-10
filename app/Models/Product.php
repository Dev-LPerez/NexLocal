<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'local_business_id',
        'name',
        'description',
        'price',
        'image_path',
        'is_available',
        'stock',
        'product_category',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Accessor para que el frontend pueda consultar fácilmente
    protected $appends = ['is_in_stock'];

    public function getIsInStockAttribute()
    {
        if (!$this->is_available) {
            return false;
        }
        if ($this->stock === null) {
            return true; // Disponibilidad infinita
        }
        return $this->stock > 0;
    }

    /**
     * Verifica si hay suficiente stock para una cantidad deseada.
     */
    public function hasStock(int $quantity): bool
    {
        if (!$this->is_available) {
            return false;
        }
        if ($this->stock === null) {
            return true;
        }
        return $this->stock >= $quantity;
    }

    // Relación: Un producto pertenece a un emprendimiento local
    public function localBusiness()
    {
        return $this->belongsTo(LocalBusiness::class);
    }
}