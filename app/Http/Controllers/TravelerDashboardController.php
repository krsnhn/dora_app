<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\TourPackage;

class TravelerDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        $stats = [
            'favorites' => $user->favorites()->count(),
            'inquiries' => $user->inquiries()->count(),
            'memories' => $user->memories()->count(),
            'backpack_open' => $user->backpackItems()->where('is_checked', false)->count(),
        ];

        $recentInquiries = $user->inquiries()
            ->with(['package.destination', 'agency'])
            ->latest()
            ->take(5)
            ->get();

        $featuredDestinations = Destination::approved()
            ->withCount('tourPackages')
            ->latest()
            ->take(4)
            ->get();

        $recommendedPackages = TourPackage::active()
            ->with(['destination', 'agency'])
            ->latest()
            ->take(4)
            ->get();

        return view('traveler.dashboard', compact(
            'stats',
            'recentInquiries',
            'featuredDestinations',
            'recommendedPackages'
        ));
    }
}
