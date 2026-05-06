<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailService;

class UserManagementController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }*/

    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->get('status')) {
            $query->where(function ($q) use ($status) {
                $q->where('status', $status);

                if (in_array($status, ['pending', 'approved', 'rejected'])) {
                    $q->orWhere(function ($agencyQuery) use ($status) {
                        $agencyQuery->where('role', 'agency')
                            ->where('agency_status', $status);
                    });
                }
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function verifyAgency(Request $request, User $user)
    {
        if ($user->role !== 'agency') abort(404);

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $isApproving = $validated['action'] === 'approve';
        
        $user->fill([
            'agency_status' => $isApproving ? 'approved' : 'rejected',
            'status' => $isApproving ? 'active' : 'suspended',
            'verification_notes' => $validated['notes'] ?? null,
        ]);
        $user->save();

        $emailService = app(EmailService::class);
        
        // Send appropriate email based on action
        if ($isApproving) {
            $emailService->sendAgencyApprovalEmail($user);
            $message = 'Agency approved successfully. Notification email sent.';
        } else {
            $emailService->sendAgencyRejectionEmail($user, $validated['notes'] ?? null);
            $message = 'Agency rejected. Notification email sent.';
        }

        return back()->with('success', $message);
    }

    public function toggleStatus(User $user)
    {
        $emailService = app(EmailService::class);

        if ($user->role === 'agency') {
            if ($user->agency_status !== 'approved' || $user->status !== 'active') {
                return back()->with('error', 'Only active approved agencies can be suspended here. Use verification to activate inactive/rejected agencies.');
            }

            $user->update(['status' => 'suspended']);
            $emailService->sendAccountSuspendedEmail($user);
            return back()->with('success', 'Agency suspended successfully. Notification email sent.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        if ($newStatus === 'suspended') {
            $emailService->sendAccountSuspendedEmail($user);
            return back()->with('success', 'User status updated to suspended. Notification email sent.');
        }

        return back()->with('success', "User status updated to {$newStatus}.");
    }

        public function rejectAgency(Request $request, User $user)
    {
        $user->update(['agency_status' => 'rejected']);

        \Illuminate\Support\Facades\Mail::send(
            'emails.agency_rejected',
            ['user' => $user, 'reason' => $request->reason],
            fn($m) => $m->to($user->email)->subject('Update on Your DORA Agency Application')
        );

        return back()->with('success', 'Agency rejected.');
    }
}
