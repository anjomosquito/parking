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
            'daily_revenue' => ParkingBooking::whereDate('created_at', $today)
                ->where('status', 'approved')
                ->sum('amount') // Assuming you have an amount column
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

        return Inertia::render('Admin/Overview', [
            'todayStats' => $todayStats,
            'weeklyStats' => $weeklyStats
        ]);
    }
}
