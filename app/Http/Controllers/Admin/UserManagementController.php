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
            $query->where('status', $status);
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

        $user->update([
            'agency_status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'status' => $validated['action'] === 'approve' ? 'active' : 'suspended',
            'verification_notes' => $validated['notes'],
        ]);

        $action = $validated['action'] === 'approve' ? 'approved' : 'rejected';

         // Send approval email
        app(EmailService::class)->sendAgencyApprovalEmail($user);

        return back()->with('success', "Agency {$action} successfully.");
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
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
