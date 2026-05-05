@extends('layouts.app')

@section('title', 'Feedback Moderation')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <h1 class="page-title" style="color:white;">Feedback Moderation</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Review and moderate traveler feedback</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    {{-- Filter --}}
    <div style="background:white;border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;width:100%;">
            <select name="status" class="form-control" style="width:auto;min-width:150px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status','pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <select name="rating" class="form-control" style="width:auto;min-width:120px;" onchange="this.form.submit()">
                <option value="">All Ratings</option>
                @for($i=5;$i>=1;$i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                @endfor
            </select>
            @if(request()->hasAny(['status','rating']))
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    @if($feedbacks->count())
    <div style="display:grid;gap:1rem;">
        @foreach($feedbacks as $fb)
        <div style="background:white;border-radius:14px;box-shadow:var(--shadow-sm);padding:1.5rem;border-left:4px solid
            @if($fb->status === 'approved') var(--forest)
            @elseif($fb->status === 'rejected') #dc2626
            @else var(--sunset) @endif;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1.5rem;flex-wrap:wrap;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;flex-wrap:wrap;">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--forest);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($fb->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--earth);">{{ $fb->user->name ?? 'Anonymous' }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted);">{{ $fb->created_at->format('M d, Y') }}</div>
                        </div>
                        <div style="display:flex;gap:2px;">
                            @for($i=1;$i<=5;$i++)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $fb->rating ? '#f59e0b' : 'none' }}" stroke="#f59e0b" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            @endfor
                        </div>
                        @if($fb->status === 'approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif($fb->status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </div>

                    <div style="display:flex;gap:1rem;margin-bottom:.75rem;font-size:.85rem;color:var(--text-muted);">
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.2rem;"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $fb->destination->name ?? 'Unknown' }}
                        </span>
                        @if($fb->tourPackage)
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.2rem;"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            {{ $fb->tourPackage->name }}
                        </span>
                        @endif
                    </div>

                    @if($fb->comment)
                    <p style="color:var(--earth);font-size:.9rem;background:#f9fafb;border-radius:8px;padding:.75rem 1rem;margin:0;font-style:italic;">
                        "{{ $fb->comment }}"
                    </p>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;min-width:120px;">
                    @if($fb->status !== 'approved')
                    <form method="POST" action="{{ route('admin.feedback.update', $fb) }}" data-confirm="Approve this feedback?" data-confirm-title="Approve Feedback" data-confirm-text="Approve">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-sm" style="background:#dcfce7;color:var(--forest);border:none;cursor:pointer;width:100%;">
                            ✓ Approve
                        </button>
                    </form>
                    @endif
                    @if($fb->status !== 'rejected')
                    <form method="POST" action="{{ route('admin.feedback.update', $fb) }}" data-confirm="Reject this feedback?" data-confirm-title="Reject Feedback" data-confirm-text="Reject" data-confirm-danger="true">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;cursor:pointer;width:100%;">
                            ✕ Reject
                        </button>
                    </form>
                    @endif
                    @if($fb->status !== 'pending')
                    <form method="POST" action="{{ route('admin.feedback.update', $fb) }}" data-confirm="Reset this feedback to pending?" data-confirm-title="Reset Feedback" data-confirm-text="Reset">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;cursor:pointer;width:100%;">
                            ↺ Reset
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:1.5rem;">
        {{ $feedbacks->appends(request()->query())->links('vendor.pagination.dora') }}
    </div>

    @else
    <div style="text-align:center;padding:5rem 1rem;background:white;border-radius:16px;box-shadow:var(--shadow-md);">
        <div style="width:80px;height:80px;background:var(--beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="36" height="36" fill="none" stroke="var(--earth)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <h3 style="color:var(--earth);margin-bottom:.5rem;">No feedback found</h3>
        <p style="color:var(--text-muted);">Feedback from travelers will appear here for moderation.</p>
    </div>
    @endif
</div>
@endsection
