<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ParkingBookingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OverviewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/parkingPlan', [ParkingBookingController::class, 'index'])->name('parkingPlan.index');
    Route::post('/parkingPlan', [ParkingBookingController::class, 'store'])->name('parkingPlan.store');
    Route::get('/about-us', function () {
        return Inertia::render('Aboutus');
    })->name('about.us');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
        
        // Admin Parking Booking Routes
        Route::get('/parking-bookings', [ParkingBookingController::class, 'adminIndex'])->name('parking-bookings.index');
        Route::patch('/parking-bookings/{booking}/status', [ParkingBookingController::class, 'updateStatus'])->name('parking-bookings.update-status');
        Route::delete('/parking-bookings/{booking}', [ParkingBookingController::class, 'destroy'])->name('parking-bookings.destroy');
        Route::get('/parking-bookings/{booking}/edit', [ParkingBookingController::class, 'edit'])->name('parking-bookings.edit');
        Route::patch('/parking-bookings/{booking}', [ParkingBookingController::class, 'update'])->name('parking-bookings.update');

        // Overview Route
        Route::get('/overview', [OverviewController::class, 'index'])->name('overview');

        // Users Management
        Route::get('/users', [UsersController::class, 'index'])->name('users');
    });
    Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

Route::get('/api/users/{user}/bookings', function (App\Models\User $user) {
    $bookings = $user->parkingBookings()
        ->with('parkingSlot')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($booking) {
            $booking->car_type = $booking->car_type ?? json_decode($booking->notes, true)['carType'] ?? 'N/A';
            return $booking;
        });
    return $bookings;
})->name('api.user.bookings');

require __DIR__.'/auth.php';
