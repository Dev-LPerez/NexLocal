<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'local_business_id',
        'total_amount',
        'status',
        'payment_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Relación: El pedido pertenece a un Turista (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: El pedido va dirigido a un Emprendimiento Local
    public function localBusiness()
    {
        return $this->belongsTo(LocalBusiness::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}