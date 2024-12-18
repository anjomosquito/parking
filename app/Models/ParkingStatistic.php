<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingStatistic extends Model
{
    protected $fillable = [
        'total_spaces',
        'occupied_spaces',
        'total_bookings_today',
        'active_users',
        'daily_revenue',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'daily_revenue' => 'decimal:2'
    ];
}
