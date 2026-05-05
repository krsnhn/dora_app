@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container" style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
        <div>
            <h1 class="page-title" style="color:var(--text-light);">Admin Dashboard</h1>
            <p class="page-sub">Platform overview for agencies, travelers, destinations, inquiries, and moderation.</p>
        </div>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-primary">Review destinations</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="color:var(--text-light);">Manage users</a>
        </div>
    </div>
    </div>
<div class="container" style="padding-top:2rem;padding-bottom:4rem;">

    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['total_users']) }}</div><div class="stat-label">Total Users</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['total_destinations']) }}</div><div class="stat-label">Destinations</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['total_packages']) }}</div><div class="stat-label">Packages</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['pending_agencies']) }}</div><div class="stat-label">Pending Agencies</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['pending_destinations']) }}</div><div class="stat-label">Destination Requests</div></div>
        <div class="stat-card"><div class="stat-number">{{ number_format($stats['pending_feedback']) }}</div><div class="stat-label">Pending Feedback</div></div>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:1.25rem;align-items:start;">
        <section class="section-box">
            <div class="section-box-header">
                <span class="section-box-title">Pending Destination Requests</span>
                <a href="{{ route('admin.destinations.index') }}" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div style="padding:.5rem 1rem 1rem;">
                @forelse($pendingDestinationRequests as $request)
                    <div style="padding:.85rem 0;border-bottom:1px solid var(--line);">
                        <div style="display:flex;justify-content:space-between;gap:.75rem;">
                            <strong>{{ $request->name }}</strong>
                            <span class="badge badge-orange">Pending</span>
                        </div>
                        <p style="font-size:.84rem;color:var(--text-muted);margin-top:.25rem;">{{ $request->agency->business_name ?? $request->agency->name ?? 'Agency' }} - {{ $request->country }}</p>
                    </div>
                @empty
                    <p style="color:var(--text-muted);padding:1rem 0;">No pending destination requests.</p>
                @endforelse
            </div>
        </section>

        <section class="section-box">
            <div class="section-box-header">
                <span class="section-box-title">Recent Users</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div style="padding:.5rem 1rem 1rem;">
                @foreach($recentUsers as $user)
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:.85rem 0;border-bottom:1px solid var(--line);">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <p style="font-size:.84rem;color:var(--text-muted);">{{ ucfirst($user->role) }} - {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="badge {{ $user->status === 'active' ? 'badge-green' : ($user->status === 'pending' ? 'badge-orange' : 'badge-red') }}">{{ ucfirst($user->status) }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="section-box" style="margin-top:1.25rem;">
        <div class="section-box-header">
            <span class="section-box-title">Pending Feedback</span>
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-ghost btn-sm">Moderate</a>
        </div>
        <div style="padding:.5rem 1rem 1rem;">
            @forelse($recentFeedback as $feedback)
                <div style="padding:.85rem 0;border-bottom:1px solid var(--line);">
                    <strong>{{ $feedback->user->name ?? 'Traveler' }} rated {{ $feedback->rating }}/5</strong>
                    <p style="font-size:.88rem;color:var(--text-muted);margin-top:.25rem;">{{ Str::limit($feedback->comment, 120) }}</p>
                </div>
            @empty
                <p style="color:var(--text-muted);padding:1rem 0;">No pending feedback.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
