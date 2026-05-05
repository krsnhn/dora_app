<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Inquiry;
use App\Models\Feedback;
use App\Models\DestinationRequest;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
        $destinationRequests = $user->destinationRequests()->latest()->take(5)->get();
        $pendingDestinationRequests = $user->destinationRequests()->where('status', 'pending')->count();

        return view('agency.dashboard', compact(
            'totalPackages', 'totalInquiries', 'pendingInquiries',
            'confirmedInquiries', 'avgRating', 'recentInquiries',
            'destinationRequests', 'pendingDestinationRequests'
        ));
    }

    public function pending()
    {
        return view('agency.pending');
    }

    public function createDestinationRequest()
    {
        return view('agency.destinations.create');
    }

    public function storeDestinationRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'tags' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'weather_location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            try {
                $validated['image_url'] = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            } catch (\Exception $e) {
                \Log::error('Destination request image upload failed: ' . $e->getMessage());
            }
        }

        unset($validated['image']);

        DestinationRequest::create([
            ...$validated,
            'agency_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('agency.dashboard')
            ->with('success', 'Destination submitted. Admin will review it before it appears publicly.');
    }
}
