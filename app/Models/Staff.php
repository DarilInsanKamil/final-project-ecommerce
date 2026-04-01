<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo_url',
        'specialization',
        'is_active',
    ];

    public function schedules()
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
