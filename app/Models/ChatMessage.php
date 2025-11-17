<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'booking_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
        'hidden_for_sender',
        'hidden_for_receiver',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'hidden_for_sender' => 'boolean',
        'hidden_for_receiver' => 'boolean',
    ];

    /**
     * Relación con la reserva
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relación con el usuario que envía el mensaje
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relación con el usuario que recibe el mensaje
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Marcar el mensaje como leído
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
