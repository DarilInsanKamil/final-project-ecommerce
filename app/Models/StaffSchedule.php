<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_off',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
