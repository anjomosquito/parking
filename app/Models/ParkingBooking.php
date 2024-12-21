<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parking_slot_id',
        'car_type',
        'start_time',
        'end_time',
        'status',
        'notes',
        'amount',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $with = ['parkingSlot'];

    /**
     * Get the user that owns the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parking slot associated with this booking
     */
    public function parkingSlot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    protected static function booted()
    {
        // When a booking is approved, mark the slot as occupied
        static::updated(function ($booking) {
            if ($booking->isDirty('status')) {
                $oldStatus = $booking->getOriginal('status');
                $newStatus = $booking->status;

                if ($oldStatus !== 'approved' && $newStatus === 'approved') {
                    $booking->parkingSlot->update(['is_occupied' => true]);
                } elseif ($oldStatus === 'approved' && $newStatus !== 'approved') {
                    $booking->parkingSlot->update(['is_occupied' => false]);
                }
            }
        });

        // When a booking is deleted, ensure the slot is marked as unoccupied
        static::deleted(function ($booking) {
            if ($booking->status === 'approved') {
                $booking->parkingSlot->update(['is_occupied' => false]);
            }
        });
    }
}
