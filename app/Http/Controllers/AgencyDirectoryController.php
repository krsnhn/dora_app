<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgencyDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $agencies = User::query()
            ->where('role', 'agency')
            ->where('agency_status', 'approved')
            ->where('status', 'active')
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($agencyQuery) use ($search) {
                    $agencyQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->withCount([
                'tourPackages as listed_packages_count' => fn ($query) => $query->publiclyListed(),
            ])
            ->withAvg(['agencyFeedback as avg_rating' => fn ($q) => $q], 'rating')
            ->orderByRaw('COALESCE(business_name, name) asc')
            ->paginate(12)
            ->withQueryString();

        return view('agencies.index', compact('agencies'));
    }

    public function show(User $user)
    {
        abort_unless(
            $user->role === 'agency' && $user->agency_status === 'approved' && $user->status === 'active',
            404
        );

        $packages = $user->tourPackages()
            ->publiclyListed()
            ->with('destination')
            ->latest()
            ->get();

        $feedbacks = $user->agencyFeedback()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $avgRating = $feedbacks->avg('rating');

        return view('agencies.show', compact('user', 'packages', 'feedbacks', 'avgRating'));
    }
}
