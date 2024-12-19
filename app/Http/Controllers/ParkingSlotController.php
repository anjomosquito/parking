<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    public function generateUniqueSlot()
    {
        $slot = ParkingSlot::findOrCreateAvailableSlot();
        return response()->json($slot);
    }

    public function markSlotOccupied($slotNumber)
    {
        $slot = ParkingSlot::where('slot_number', $slotNumber)->first();
        
        if ($slot) {
            $slot->update(['is_occupied' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
