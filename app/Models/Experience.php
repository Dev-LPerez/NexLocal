<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'duration',
        'price',
        'image_path', // Si tienes imagen
        'category',
        'includes',
        'not_includes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'includes' => 'array',
            'not_includes' => 'array',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the user (guide) that owns the experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the availability slots for the experience.
     */
    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class)->orderBy('start_time');
    }

    /**
     * Get the bookings for the experience.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
