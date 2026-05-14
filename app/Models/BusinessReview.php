<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessReview extends Model
{
    protected $fillable = [
        'local_business_id',
        'user_id',
        'rating',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localBusiness()
    {
        return $this->belongsTo(LocalBusiness::class);
    }
}
