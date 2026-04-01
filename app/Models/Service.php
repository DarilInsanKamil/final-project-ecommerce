<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'duration_minutes',
        'image_url',
        'is_active',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
