<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgencyDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $agencies = User::query()
            ->where('role', 'agency')
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($agencyQuery) use ($search) {
                    $agencyQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('business_name', 'like', "%{$search}%");
                });
            })
            ->when($request->get('status'), function ($query, $status) {
                $query->where(function ($statusQuery) use ($status) {
                    $statusQuery->where('status', $status)
                        ->orWhere('agency_status', $status);
                });
            })
            ->withCount('tourPackages')
            ->withAvg(['agencyFeedback as avg_rating' => fn ($q) => $q], 'rating')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.agencies.index', compact('agencies'));
    }

    public function show(User $user)
    {
        abort_unless($user->role === 'agency', 404);

        $packages = $user->tourPackages()
            ->with('destination')
            ->latest()
            ->get();

        $feedbacks = $user->agencyFeedback()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $avgRating = $feedbacks->avg('rating');

        return view('admin.agencies.show', compact('user', 'packages', 'feedbacks', 'avgRating'));
    }
}
