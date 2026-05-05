<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\Feedback;
use App\Models\Inquiry;
use App\Models\DestinationRequest;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Make sure ALL required keys are defined
        $stats = [
            'total_users' => User::count(),
            'total_destinations' => Destination::count(),
            'total_packages' => TourPackage::count(),
            'pending_agencies' => User::where('role', 'agency')
                ->where(function($query) {
                    $query->where('agency_status', 'pending')
                          ->orWhere('status', 'pending');
                })->count(),
            'total_inquiries' => Inquiry::count(),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
            'pending_destinations' => DestinationRequest::where('status', 'pending')->count(),
        ];

        // Get recent users (excluding admins)
        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->take(5)
            ->get();

        // Get recent feedback
        $recentFeedback = Feedback::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $pendingDestinationRequests = DestinationRequest::with('agency')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Pass ALL variables to the view
        return view('admin.dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentFeedback' => $recentFeedback,
            'pendingDestinationRequests' => $pendingDestinationRequests,
        ]);
    }
}
