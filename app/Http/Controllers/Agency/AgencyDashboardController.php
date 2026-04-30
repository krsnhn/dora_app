<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Inquiry;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AgencyDashboardController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(['auth', 'agency']);
    } */

    public function index()
    {
        $user = auth()->user();
        $totalPackages = $user->tourPackages()->where('status', 'active')->count();
        $totalInquiries = $user->agencyInquiries()->count();
        $pendingInquiries = $user->agencyInquiries()->where('status', 'pending')->count();
        $confirmedInquiries = $user->agencyInquiries()->where('status', 'confirmed')->count();
        $avgRating = $user->agencyFeedback()->where('status', 'approved')->avg('rating');
        $recentInquiries = $user->agencyInquiries()->with(['package.destination', 'user'])->latest()->take(5)->get();

        return view('agency.dashboard', compact(
            'totalPackages', 'totalInquiries', 'pendingInquiries',
            'confirmedInquiries', 'avgRating', 'recentInquiries'
        ));
    }

    public function pending()
    {
        return view('agency.pending');
    }
}
