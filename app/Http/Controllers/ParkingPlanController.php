<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ParkingPlanController extends Controller
{
    /**
     * Display the parking plan selection page
     */
    public function index(Request $request)
    {
        // Validate incoming booking details
        $validatedData = $request->validate([
            'carType' => 'required|string',
            'checkIn.date' => 'required|date',
            'checkIn.time' => 'required|date_format:H:i',
            'checkOut.date' => 'required|date|after_or_equal:checkIn.date',
            'checkOut.time' => 'required|date_format:H:i',
            'promoCode' => 'nullable|string'
        ]);

        // Calculate duration
        $checkIn = Carbon::parse($validatedData['checkIn']['date'] . ' ' . $validatedData['checkIn']['time']);
        $checkOut = Carbon::parse($validatedData['checkOut']['date'] . ' ' . $validatedData['checkOut']['time']);
        $duration = $checkIn->diffInHours($checkOut);

        // Define parking plans
        $parkingPlans = $this->getParkingPlans($duration);

        // Apply promo code if exists (optional feature)
        $promoDiscount = $this->applyPromoCode($validatedData['promoCode'] ?? null);

        return Inertia::render('ParkingPlan/Index', [
            'bookingDetails' => $validatedData,
            'parkingPlans' => $parkingPlans,
            'duration' => $duration,
            'promoDiscount' => $promoDiscount
        ]);
    }

    /**
     * Get available parking plans based on duration
     */
    protected function getParkingPlans(int $duration): array
    {
        $plans = [
            [
                'id' => 'standard',
                'name' => 'Standard Parking',
                'description' => 'Basic parking in open area',
                'baseRate' => 10, // $10 per hour
                'features' => [
                    'Open parking lot',
                    'Basic security',
                    'No covered parking'
                ],
                'totalCost' => 10 * $duration
            ],
            [
                'id' => 'covered',
                'name' => 'Covered Parking',
                'description' => 'Protected parking under shelters',
                'baseRate' => 15, // $15 per hour
                'features' => [
                    'Sheltered parking',
                    'Enhanced security',
                    'Protection from elements'
                ],
                'totalCost' => 15 * $duration
            ],
            [
                'id' => 'premium',
                'name' => 'Premium Parking',
                'description' => 'VIP underground parking',
                'baseRate' => 25, // $25 per hour
                'features' => [
                    'Underground parking',
                    'Full security',
                    'Charging stations',
                    'Complimentary car wash'
                ],
                'totalCost' => 25 * $duration
            ]
        ];

        // Optional: Filter plans based on duration
        return array_filter($plans, function($plan) use ($duration) {
            // Example: Premium plan only for bookings over 24 hours
            if ($plan['id'] === 'premium') {
                return $duration >= 24;
            }
            return true;
        });
    }

    /**
     * Apply promo code (optional feature)
     */
    protected function applyPromoCode(?string $promoCode): ?array
    {
        if (!$promoCode) {
            return null;
        }

        // Sample promo codes
        $promoCodes = [
            'SAVE10' => [
                'discount' => 0.1, // 10% off
                'description' => '10% off your parking'
            ],
            'FIRSTTIME' => [
                'discount' => 0.15, // 15% off
                'description' => '15% off for first-time users'
            ]
        ];

        return $promoCodes[strtoupper($promoCode)] ?? null;
    }

    /**
     * Handle parking plan selection and proceed to booking
     */
    public function selectPlan(Request $request)
    {
        $validatedData = $request->validate([
            'planId' => 'required|in:standard,covered,premium',
            'bookingDetails' => 'required|array'
        ]);

        // Here you would typically:
        // 1. Create a booking in the database
        // 2. Process payment
        // 3. Generate booking confirmation

        return Inertia::render('Booking/Confirmation', [
            'selectedPlan' => $validatedData['planId'],
            'bookingDetails' => $validatedData['bookingDetails']
        ]);
    }
}