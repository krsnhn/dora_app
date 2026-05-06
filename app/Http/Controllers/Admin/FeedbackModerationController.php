<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackModerationController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }*/

    public function index()
    {
        $feedbacks = Feedback::with(['user', 'agency', 'package.destination'])
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status), function ($query) {
                $query->where('status', 'pending');
            })
            ->when(request('rating'), fn ($query, $rating) => $query->where('rating', $rating))
            ->latest()
            ->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $feedback->update($validated);
        return back()->with('success', 'Feedback status updated.');
    }
}
