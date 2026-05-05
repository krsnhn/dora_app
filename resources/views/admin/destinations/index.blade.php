@extends('layouts.app')

@section('title', 'Manage Destinations')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 class="page-title" style="color:white;">Destinations</h1>
                <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Manage all platform destinations</p>
            </div>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;"><path d="M12 5v14M5 12h14"/></svg>
                Add Destination
            </a>
        </div>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">

    {{-- Pending Requests --}}
    @if($pendingRequests->count())
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:2rem;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--beige);background:linear-gradient(135deg,#fff7ed,#fef3c7);">
            <h3 style="color:#92400e;margin:0;font-size:1rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;vertical-align:middle;"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Pending Destination Requests ({{ $pendingRequests->count() }})
            </h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Submitted By</th>
                        <th>Country</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $req)
                    <tr>
                        <td>
                            <div style="font-weight:600;color:var(--earth);">{{ $req->name }}</div>
                            <div style="font-size:.8rem;color:var(--text-muted);">{{ Str::limit($req->description, 60) }}</div>
                        </td>
                        <td style="font-size:.85rem;color:var(--text-muted);">{{ $req->agency->name ?? '—' }}</td>
                        <td>{{ $req->country }}</td>
                        <td style="font-size:.85rem;color:var(--text-muted);">{{ $req->created_at->diffForHumans() }}</td>
                        <td>
                            <div style="display:flex;gap:.4rem;">
                                <form method="POST" action="{{ route('admin.destination-requests.review', $req) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-sm" style="background:#dcfce7;color:var(--forest);border:none;cursor:pointer;">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.destination-requests.review', $req) }}">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;cursor:pointer;">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Destinations Table --}}
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--beige);">
            <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;">
                <input type="text" name="search" class="form-control" style="flex:2;min-width:200px;"
                       placeholder="Search destinations..." value="{{ request('search') }}">
                <select name="approved" class="form-control" style="width:auto;min-width:140px;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="1" {{ request('approved') === '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('approved') === '0' ? 'selected' : '' }}>Unapproved</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request()->hasAny(['search','approved']))
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Country</th>
                        <th>Tags</th>
                        <th>Packages</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $destination)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                @if($destination->image_url)
                                <img src="{{ $destination->image_url }}" alt="" style="width:44px;height:44px;border-radius:8px;object-fit:cover;">
                                @else
                                <div style="width:44px;height:44px;border-radius:8px;background:var(--beige);color:var(--text-muted);display:flex;align-items:center;justify-content:center;text-align:center;font-size:.5rem;font-weight:800;line-height:1.1;text-transform:uppercase;">No Photo</div>
                                @endif
                                <div>
                                    <div style="font-weight:600;color:var(--earth);">{{ $destination->name }}</div>
                                    <div style="font-size:.78rem;color:var(--text-muted);">Added {{ $destination->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $destination->country }}</td>
                        <td>
                            @if($destination->tags)
                            @foreach(array_slice($destination->tags_array, 0, 2) as $tag)
                            <span style="background:var(--beige);color:var(--earth);font-size:.75rem;padding:.2rem .5rem;border-radius:20px;margin:.1rem;display:inline-block;">{{ $tag }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td style="font-weight:600;color:var(--ocean);">{{ $destination->tourPackages()->count() }}</td>
                        <td>
                            @if($destination->is_approved)
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.destinations.toggle', $destination) }}" data-confirm="{{ $destination->is_approved ? 'Unapprove this destination?' : 'Approve this destination?' }}" data-confirm-title="{{ $destination->is_approved ? 'Unapprove Destination' : 'Approve Destination' }}" data-confirm-text="{{ $destination->is_approved ? 'Unapprove' : 'Approve' }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:{{ $destination->is_approved ? '#fee2e2' : '#dcfce7' }};color:{{ $destination->is_approved ? '#dc2626' : 'var(--forest)' }};border:none;cursor:pointer;">
                                        {{ $destination->is_approved ? 'Unapprove' : 'Approve' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:3rem;">No destinations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $destinations->appends(request()->query())->links('vendor.pagination.dora') }}
    </div>
</div>
@endsection
