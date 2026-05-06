<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(['auth', 'agency']);
    }*/

    public function index(Request $request)
    {
        $query = auth()->user()->agencyInquiries()->with(['package.destination', 'user']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                                $q->where('contact_name', 'like', "%{$search}%")
                                    ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        if ($packageId = $request->get('package_id')) {
            $query->where('package_id', $packageId);
        }

        $inquiries = $query->latest()->paginate(15);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'inquiries' => $inquiries->items(),
                'pagination' => [
                    'current_page' => $inquiries->currentPage(),
                    'last_page' => $inquiries->lastPage(),
                    'per_page' => $inquiries->perPage(),
                    'total' => $inquiries->total()
                ]
            ]);
        }

        return view('agency.inquiries.index', compact('inquiries'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        if ($inquiry->agency_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $inquiry->update($validated);
        return back()->with('success', 'Inquiry status updated.');
    }
}
