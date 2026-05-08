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
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relación: Un producto pertenece a un emprendimiento local
    public function localBusiness()
    {
        return $this->belongsTo(LocalBusiness::class);
    }
}