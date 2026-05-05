@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <h1 class="page-title" style="color:white;">User Management</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Manage all registered users and agencies</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    {{-- Filters --}}
    <div style="background:white;border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
            <input type="text" name="search" class="form-control" style="flex:2;min-width:200px;"
                   placeholder="Search name or email..." value="{{ request('search') }}">
            <select name="role" class="form-control" style="width:auto;min-width:140px;" onchange="this.form.submit()">
                <option value="">All Roles</option>
                <option value="traveler" {{ request('role') === 'traveler' ? 'selected' : '' }}>Travelers</option>
                <option value="agency" {{ request('role') === 'agency' ? 'selected' : '' }}>Agencies</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
            </select>
            <select name="status" class="form-control" style="width:auto;min-width:140px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search','role','status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Agency Info</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:38px;height:38px;border-radius:50%;background:var(--ocean);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--earth);">{{ $user->name }}</div>
                                    <div style="font-size:.8rem;color:var(--text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-info' : ($user->role === 'agency' ? 'badge-success' : '') }}"
                                  style="{{ $user->role === 'traveler' ? 'background:var(--beige);color:var(--earth);' : '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($user->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($user->status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge" style="background:#fee2e2;color:#dc2626;">{{ ucfirst($user->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'agency')
                            <div style="font-size:.85rem;">
                                <div style="font-weight:500;color:var(--earth);">{{ $user->business_name ?? '—' }}</div>
                                @if($user->valid_id_path)
                                <a href="{{ $user->valid_id_path }}" target="_blank" style="color:var(--ocean);font-size:.8rem;text-decoration:none;">
                                    View ID →
                                </a>
                                @endif
                            </div>
                            @else
                            <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:.85rem;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                {{-- Agency Verification --}}
                                @if($user->role === 'agency' && in_array($user->status, ['pending','rejected']))
                                <button onclick="openVerifyModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->status }}')"
                                        class="btn btn-sm btn-primary">Verify</button>
                                @endif

                                {{-- Toggle Active/Suspend --}}
                                @if($user->role !== 'admin')
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" data-confirm="{{ $user->status === 'active' ? 'Suspend this user?' : 'Activate this user?' }}" data-confirm-title="{{ $user->status === 'active' ? 'Suspend User' : 'Activate User' }}" data-confirm-text="{{ $user->status === 'active' ? 'Suspend' : 'Activate' }}" data-confirm-danger="{{ $user->status === 'active' ? 'true' : 'false' }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:{{ $user->status === 'active' ? '#fee2e2' : '#dcfce7' }};color:{{ $user->status === 'active' ? '#dc2626' : 'var(--forest)' }};border:none;cursor:pointer;">
                                        {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:3rem;">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $users->appends(request()->query())->links('vendor.pagination.dora') }}
    </div>
</div>

{{-- Verify Modal --}}
<div id="verifyModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:2rem;max-width:500px;width:90%;box-shadow:var(--shadow-lg);">
        <h3 style="color:var(--earth);margin-bottom:1.5rem;" id="modalTitle">Verify Agency</h3>
        <form method="POST" id="verifyForm" data-confirm="Submit this agency verification decision?" data-confirm-title="Verify Agency" data-confirm-text="Submit">
            @csrf @method('PATCH')
            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Decision *</label>
                <select name="agency_status" class="form-control" id="verifyDecision">
                    <option value="approved">✅ Approve Agency</option>
                    <option value="rejected">❌ Reject Agency</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Notes (optional)</label>
                <textarea name="verification_notes" class="form-control" rows="3" placeholder="Add notes about this decision..."></textarea>
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" onclick="closeVerifyModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openVerifyModal(userId, userName, currentStatus) {
    document.getElementById('modalTitle').textContent = 'Verify: ' + userName;
    document.getElementById('verifyForm').action = '/admin/users/' + userId + '/verify-agency';
    document.getElementById('verifyModal').style.display = 'flex';
}
function closeVerifyModal() {
    document.getElementById('verifyModal').style.display = 'none';
}
document.getElementById('verifyModal').addEventListener('click', function(e) {
    if (e.target === this) closeVerifyModal();
});
</script>
@endsection
