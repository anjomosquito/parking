<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ParkingSlot extends Model
{
    protected $fillable = [
        'slot_number',
        'is_occupied',
        'current_number',
        'user_id'
    ];

    protected $casts = [
        'is_occupied' => 'boolean',
        'current_number' => 'integer'
    ];

    /**
     * Get the user that owns the parking slot
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the next available parking slot number
     */
    public static function getNextSlotNumber()
    {
        $lastSlot = static::orderBy('id', 'desc')->first();
        
        if (!$lastSlot) {
            return 'A1';
        }

        $currentNumber = $lastSlot->current_number + 1;
        $letter = chr(ord('A') + floor(($currentNumber - 1) / 10));
        $number = ($currentNumber - 1) % 10 + 1;

        return $letter . $number;
    }

    /**
     * Create a new parking slot with auto-incremented number
     */
    public static function createNextSlot()
    {
        $nextSlotNumber = static::getNextSlotNumber();
        $currentNumber = static::count() + 1;

        return static::create([
            'slot_number' => $nextSlotNumber,
            'is_occupied' => false,
            'current_number' => $currentNumber
        ]);
    }

    /**
     * Get the bookings for this parking slot
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(ParkingBooking::class);
    }
}
