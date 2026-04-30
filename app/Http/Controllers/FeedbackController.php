<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:users,id',
            'package_id' => 'nullable|exists:tour_packages,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existing = Feedback::where('user_id', auth()->id())
            ->where('agency_id', $validated['agency_id'])
            ->where('package_id', $validated['package_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted feedback for this package.');
        }

        Feedback::create([
            ...$validated,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Thank you for your feedback! It will be reviewed shortly.');
    }
}
