<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'experience_id',
        'user_id',
        'booking_id',
        'rating',
        'comment',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * Get the user (tourist) who wrote the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the experience this review is for.
     */
    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    /**
     * Get the booking this review is associated with.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}

