<?php

namespace App\Http\Controllers;

use App\Models\ParkingBooking;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function step1()
    {
        return Inertia::render('Payment/Payment1');
    }

    public function step2(Request $request)
    {
        return Inertia::render('Payment/Payment2', [
            'bookingDetails' => session('bookingDetails')
        ]);
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'bookingDetails' => 'required|array',
            'bookingDetails.carType' => 'required|string',
            'bookingDetails.checkIn' => 'required|array',
            'bookingDetails.checkIn.date' => 'required|date',
            'bookingDetails.checkIn.time' => 'required',
            'bookingDetails.checkOut' => 'required|array',
            'bookingDetails.checkOut.date' => 'required|date|after_or_equal:bookingDetails.checkIn.date',
            'bookingDetails.checkOut.time' => 'required',
            'bookingDetails.planPrice' => 'required|numeric|min:0',
            'paymentMethod' => 'required|in:gcash,paypal',
            'paymentDetails' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // Find an available parking slot
            $parkingSlot = ParkingSlot::where('is_occupied', false)->first();
            if (!$parkingSlot) {
                throw new \Exception('No parking slots available.');
            }

            // Create the booking with payment details
            $booking = ParkingBooking::create([
                'user_id' => auth()->id(),
                'parking_slot_id' => $parkingSlot->id,
                'car_type' => $validated['bookingDetails']['carType'],
                'start_time' => $validated['bookingDetails']['checkIn']['date'] . ' ' . $validated['bookingDetails']['checkIn']['time'],
                'end_time' => $validated['bookingDetails']['checkOut']['date'] . ' ' . $validated['bookingDetails']['checkOut']['time'],
                'amount' => $validated['bookingDetails']['planPrice'],
                'status' => 'pending',
                'notes' => json_encode([
                    'paymentMethod' => $validated['paymentMethod'],
                    'paymentDetails' => $validated['paymentDetails']
                ])
            ]);

            // Mark the parking slot as occupied
            $parkingSlot->update(['is_occupied' => true]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Payment processed and booking confirmed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}