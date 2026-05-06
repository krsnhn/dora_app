<?php

namespace App\Services;

use App\Mail\AccountSuspendedMail;
use App\Mail\AgencyApprovalMail;
use App\Mail\AgencyRejectionMail;
use App\Mail\InquiryConfirmationMail;
use App\Mail\InquiryNotificationMail;
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

            Mail::to($agency->email, $agency->business_name ?? $agency->name)
                ->send(new InquiryNotificationMail($inquiry));

            return true;
        } catch (\Exception $e) {
            Log::error('Email notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendInquiryConfirmation(Inquiry $inquiry): bool
    {
        try {
            Mail::to($inquiry->contact_email, $inquiry->contact_name)
                ->send(new InquiryConfirmationMail($inquiry));

            return true;
        } catch (\Exception $e) {
            Log::error('Confirmation email failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendAgencyApprovalEmail(User $user): bool
    {
        try {
            Mail::to($user->email, $user->name)
                ->send(new AgencyApprovalMail($user));

            return true;
        } catch (\Exception $e) {
            Log::error('Agency approval email failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendAgencyRejectionEmail(User $user, ?string $notes = null): bool
    {
        try {
            Mail::to($user->email, $user->name)
                ->send(new AgencyRejectionMail($user, $notes));

            return true;
        } catch (\Exception $e) {
            Log::error('Agency rejection email failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendAccountSuspendedEmail(User $user): bool
    {
        try {
            Mail::to($user->email, $user->name)
                ->send(new AccountSuspendedMail($user));

            return true;
        } catch (\Exception $e) {
            Log::error('Account suspended email failed: ' . $e->getMessage());
            return false;
        }
    }
}
