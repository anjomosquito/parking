<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingBooking;
use App\Models\ParkingStatistic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Get all users with their bookings and additional information
        $activeUsers = User::with(['parkingBookings' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($user) {
            $totalBookings = $user->parkingBookings->count();
            $approvedBookings = $user->parkingBookings->where('status', 'approved')->count();
            $latestBooking = $user->parkingBookings->first();
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'phone' => $user->phone ?? 'N/A',
                'total_bookings' => $totalBookings,
                'approved_bookings' => $approvedBookings,
                'booking_rate' => $totalBookings > 0 ? round(($approvedBookings / $totalBookings) * 100) : 0,
                'latest_booking' => $latestBooking ? [
                    'id' => $latestBooking->id,
                    'status' => $latestBooking->status,
                    'start_time' => $latestBooking->start_time,
                    'end_time' => $latestBooking->end_time,
                    'car_type' => $latestBooking->car_type,
                    'created_at' => $latestBooking->created_at
                ] : null,
                'is_active' => $latestBooking && 
                    $latestBooking->status === 'approved' && 
                    Carbon::parse($latestBooking->start_time)->lte(now()) && 
                    Carbon::parse($latestBooking->end_time)->gte(now())
            ];
        });

        // Get parking slots occupancy
        $parkingSlots = ParkingBooking::where('status', 'approved')
            ->whereDate('start_time', '<=', now())
            ->whereDate('end_time', '>=', now())
            ->with('parkingSlot')
            ->get()
            ->pluck('parking_slot.slot_number');

        // Get today's statistics
        $todayStats = [
            'total_spaces' => 50, // You can make this configurable
            'occupied_spaces' => ParkingBooking::where('status', 'approved')
                ->whereDate('start_time', '<=', now())
                ->whereDate('end_time', '>=', now())
                ->count(),
            'total_bookings_today' => ParkingBooking::whereDate('created_at', $today)->count(),
            'active_users' => User::whereHas('parkingBookings', function($query) {
                $query->where('status', 'approved');
            })->count(),
            
        ];

        // Get weekly statistics
        $weeklyStats = ParkingBooking::whereBetween('created_at', [
            $today->copy()->startOfWeek(),
            $today->copy()->endOfWeek()
        ])
        ->selectRaw('DATE(created_at) as date, COUNT(*) as total_bookings')
        ->groupBy('date')
        ->get();

        // Store today's statistics
        ParkingStatistic::updateOrCreate(
            ['date' => $today],
            $todayStats
        );

        return Inertia::render('Admin/Overview/Index', [
            'todayStats' => $todayStats,
            'weeklyStats' => $weeklyStats,
            'activeUsers' => $activeUsers,
            'parkingSlots' => $parkingSlots,
        ]);
    }
}
