<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'experience_id',
        'booking_date',
        'status',
        'availability_slot_id',
        'payment_status',
        'payment_method',
        'total_amount',
        'num_travelers', // Asegura que este campo sea asignable
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'datetime',
            'total_amount' => 'decimal:2',
            'tourist_confirmed_completed' => 'boolean',
            'guide_confirmed_completed' => 'boolean',
        ];
    }

    /**
     * Get the user (tourist) that made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the experience that was booked.
     */
    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    /**
     * Get the availability slot for this booking.
     */
    public function availabilitySlot(): BelongsTo
    {
        return $this->belongsTo(AvailabilitySlot::class);
    }

    /**
     * Get the review associated with the booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all chat messages for this booking.
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }
}
