<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\Feedback;
use App\Models\Inquiry;

class AdminController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }*/

    public function dashboard()
    {
        $stats = [
            'travelers' => User::where('role', 'traveler')->count(),
            'agencies' => User::where('role', 'agency')->count(),
            'pending_agencies' => User::where('role', 'agency')->where('agency_status', 'pending')->count(),
            'destinations' => Destination::approved()->count(),
            'pending_destinations' => Destination::where('is_approved', false)->count(),
            'packages' => TourPackage::active()->count(),
            'inquiries' => Inquiry::count(),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
        ];

        $recentUsers = User::where('role', '!=', 'admin')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
