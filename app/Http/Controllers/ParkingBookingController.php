<?php

namespace App\Http\Controllers;

use App\Models\ParkingBooking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParkingBookingController extends Controller
{
    public function index()
    {
        $bookings = ParkingBooking::where('user_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Plan', [
            'bookings' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'carType' => 'required|string',
            'checkIn.date' => 'required|date',
            'checkIn.time' => 'required',
            'checkOut.date' => 'required|date|after:checkIn.date',
            'checkOut.time' => 'required',
            'parking_slot' => 'required|string',
            'promoCode' => 'nullable|string',
        ]);

        $booking = ParkingBooking::create([
            'user_id' => auth()->id(),
            'parking_slot' => $validated['parking_slot'],
            'start_time' => $validated['checkIn']['date'] . ' ' . $validated['checkIn']['time'],
            'end_time' => $validated['checkOut']['date'] . ' ' . $validated['checkOut']['time'],
            'notes' => json_encode([
                'carType' => $validated['carType'],
                'promoCode' => $validated['promoCode']
            ]),
            'status' => 'pending'
        ]);

        return redirect()->route('parkingPlan.index');
    }

    public function adminIndex()
    {
        $bookings = ParkingBooking::with('user')
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/ParkingPlan/Index', [
            'bookings' => $bookings
        ]);
    }

    public function edit(ParkingBooking $booking)
    {
        $booking->load('user');
        $notes = json_decode($booking->notes, true);

        return Inertia::render('Admin/ParkingPlan/Edit', [
            'booking' => array_merge($booking->toArray(), [
                'carType' => $notes['carType'] ?? null,
                'promoCode' => $notes['promoCode'] ?? null,
                'checkIn' => [
                    'date' => date('Y-m-d', strtotime($booking->start_time)),
                    'time' => date('H:i', strtotime($booking->start_time))
                ],
                'checkOut' => [
                    'date' => date('Y-m-d', strtotime($booking->end_time)),
                    'time' => date('H:i', strtotime($booking->end_time))
                ]
            ])
        ]);
    }

    public function update(Request $request, ParkingBooking $booking)
    {
        $validated = $request->validate([
            'carType' => 'required|string',
            'checkIn.date' => 'required|date',
            'checkIn.time' => 'required',
            'checkOut.date' => 'required|date|after:checkIn.date',
            'checkOut.time' => 'required',
            'parking_slot' => 'required|string',
            'promoCode' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $booking->update([
            'parking_slot' => $validated['parking_slot'],
            'start_time' => $validated['checkIn']['date'] . ' ' . $validated['checkIn']['time'],
            'end_time' => $validated['checkOut']['date'] . ' ' . $validated['checkOut']['time'],
            'notes' => json_encode([
                'carType' => $validated['carType'],
                'promoCode' => $validated['promoCode']
            ]),
            'status' => $validated['status']
        ]);

        return redirect()->route('admin.users');
    }

    public function updateStatus(Request $request, ParkingBooking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    public function destroy(ParkingBooking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Booking deleted successfully!');
    }
}
