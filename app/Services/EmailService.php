<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendInquiryNotification(Inquiry $inquiry): bool
    {
        try {
            $agency = $inquiry->agency;
            $package = $inquiry->package;

            Mail::send('emails.inquiry', compact('inquiry', 'agency', 'package'), function ($mail) use ($agency, $inquiry) {
                $mail->to($agency->email, $agency->business_name ?? $agency->name)
                     ->subject("New Inquiry: {$inquiry->package->name} - DORA Platform")
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Email notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendInquiryConfirmation(Inquiry $inquiry): bool
    {
        try {
            Mail::send('emails.inquiry_confirmation', compact('inquiry'), function ($mail) use ($inquiry) {
                $mail->to($inquiry->contact_email, $inquiry->contact_name)
                     ->subject("Your Inquiry Has Been Received - DORA")
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Confirmation email failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendAgencyApprovalEmail(User $user): void
{
    \Illuminate\Support\Facades\Mail::send(
        'emails.agency_approved',
        ['user' => $user],
        function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('🎉 Your DORA Agency Account is Approved!');
        }
    );
}
}
