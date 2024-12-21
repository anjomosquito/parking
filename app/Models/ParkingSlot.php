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
        'is_occupied'
    ];

    protected $casts = [
        'is_occupied' => 'boolean'
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

        preg_match('/([A-Z])(\d+)/', $lastSlot->slot_number, $matches);
        
        $letter = $matches[1];
        $number = intval($matches[2]);
        
        // Increment number or letter
        $number++;
        if ($number > 50) {
            $letter = chr(ord($letter) + 1);
            $number = 1;
        }
        
        return $letter . $number;
    }

    /**
     * Find or create an available parking slot
     */
    public static function findOrCreateAvailableSlot()
    {
        // First, try to find an unoccupied existing slot
        $existingSlot = static::where('is_occupied', false)->first();
        
        if ($existingSlot) {
            return $existingSlot;
        }

        // If no unoccupied slots, generate a new unique slot
        $newSlotNumber = static::getNextSlotNumber();

        return static::create([
            'slot_number' => $newSlotNumber,
            'is_occupied' => false
        ]);
    }

    /**
     * Get the bookings for this parking slot
     */
    public function bookings()
    {
        return $this->hasMany(ParkingBooking::class);
    }

    /**
     * Get the current booking for this parking slot
     */
    public function currentBooking()
    {
        return $this->hasOne(ParkingBooking::class)
            ->where('status', 'approved')
            ->whereDate('start_time', '<=', now())
            ->whereDate('end_time', '>=', now());
    }

    /**
     * Check if the parking slot is currently occupied
     */
    public function isCurrentlyOccupied()
    {
        return $this->currentBooking()->exists();
    }
}
