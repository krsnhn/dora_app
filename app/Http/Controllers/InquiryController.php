<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\TourPackage;
use App\Services\EmailService;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function index()
    {
        $inquiries = auth()->user()->inquiries()
            ->with(['package.destination', 'agency'])
            ->latest()
            ->paginate(10);
        return view('traveler.inquiries.index', compact('inquiries'));
    }

    public function store(Request $request, TourPackage $package)
    {
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'pax' => 'required|integer|min:1|max:100',
            'travel_date' => 'nullable|date|after:today',
            'message' => 'nullable|string|max:1000',
        ]);

        $inquiry = Inquiry::create([
            ...$validated,
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'agency_id' => $package->agency_id,
            'status' => 'pending',
        ]);

        $this->emailService->sendInquiryNotification($inquiry);
        $this->emailService->sendInquiryConfirmation($inquiry);

        return back()->with('success', 'Your inquiry has been sent! The agency will contact you soon.');
    }
}
