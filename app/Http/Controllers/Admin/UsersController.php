<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('latestBooking')
            ->paginate(10)
            ->through(function ($user) {
                if ($user->latestBooking && $user->latestBooking->notes) {
                    $user->latestBooking->notes = json_decode($user->latestBooking->notes);
                }
                return $user;
            });

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }
}
