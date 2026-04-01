<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_name',
        'rating',
        'comment',
        'is_visible',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
