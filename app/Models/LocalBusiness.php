<?php

// app/Models/LocalBusiness.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'business_type',
        'category',
        'price_range',
        'capacity',
        'services',
        'address',
        'lat',
        'lng',
        'phone',
        'email',
        'cover_image_path',
        'gallery_images',
        'status',
        'banner_image_path',
        'theme_colors',
        'social_links',
        'operating_hours',
        'payment_methods',
        'welcome_message'
    ];

    protected $casts = [
        'services' => 'array',
        'gallery_images' => 'array',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'theme_colors' => 'array',
        'social_links' => 'array',
        'operating_hours' => 'array',
        'payment_methods' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
