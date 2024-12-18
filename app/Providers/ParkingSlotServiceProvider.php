<?php

namespace App\Providers;

use App\Models\ParkingSlot;
use App\Models\ParkingBooking;
use Illuminate\Support\ServiceProvider;

class ParkingSlotServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        ParkingBooking::creating(function ($booking) {
            // If no parking slot is specified, create a new one
            if (!$booking->parking_slot_id) {
                $slot = ParkingSlot::createNextSlot();
                $booking->parking_slot_id = $slot->id;
            }
        });

        ParkingBooking::created(function ($booking) {
            // Mark the slot as occupied
            $booking->parkingSlot->update(['is_occupied' => true]);
        });

        ParkingBooking::updated(function ($booking) {
            if ($booking->status === 'completed') {
                // Free up the slot when booking is completed
                $booking->parkingSlot->update(['is_occupied' => false]);
            }
        });
    }
}
