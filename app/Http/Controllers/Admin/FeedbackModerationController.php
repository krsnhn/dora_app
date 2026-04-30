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
        $feedback = Feedback::with(['user', 'agency', 'package.destination'])->latest()->paginate(20);
        return view('admin.feedback.index', compact('feedback'));
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
