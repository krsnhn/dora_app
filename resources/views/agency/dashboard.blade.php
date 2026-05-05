@extends('layouts.app')

@section('title', 'Agency Dashboard')

@section('content')

<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container" style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
        <div>
            <h1 class="page-title" style="color:var(--text-light);">Agency Dashboard</h1>
            <p class="page-sub">Welcome back, {{ auth()->user()->business_name ?? auth()->user()->name }}. Manage packages, inquiries, and destination requests.</p>
        </div>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <a href="{{ route('agency.packages.create') }}" class="btn btn-primary">Review destinations</a>
            <a href="{{ route('agency.destinations.create') }}" class="btn btn-outline" style="color:var(--text-light);">Manage users</a>
        </div>
    </div>
    </div>
<div class="container" style="padding-top:2rem;padding-bottom:4rem;">

    <div class="grid-4" style="margin-bottom:1.5rem;">
        <div class="kpi-card"><div class="kpi-icon">PK</div><div class="kpi-num">{{ $totalPackages }}</div><div class="kpi-label">Active Packages</div></div>
        <div class="kpi-card"><div class="kpi-icon">IN</div><div class="kpi-num">{{ $totalInquiries }}</div><div class="kpi-label">Total Inquiries</div></div>
        <div class="kpi-card"><div class="kpi-icon">PN</div><div class="kpi-num">{{ $pendingInquiries }}</div><div class="kpi-label">Pending Inquiries</div></div>
        <div class="kpi-card"><div class="kpi-icon">RT</div><div class="kpi-num">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</div><div class="kpi-label">Average Rating</div></div>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1.35fr) minmax(320px,.9fr);gap:1.25rem;align-items:start;">
        <section class="section-box">
            <div class="section-box-header">
                <span class="section-box-title">Recent Inquiries</span>
                <a href="{{ route('agency.inquiries.index') }}" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr><th>Traveler</th><th>Package</th><th>Pax</th><th>Date</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentInquiries as $inquiry)
                            <tr>
                                <td>
                                    <div style="font-weight:700;">{{ $inquiry->contact_name }}</div>
                                    <div style="font-size:.78rem;color:var(--text-muted);">{{ $inquiry->contact_email }}</div>
                                </td>
                                <td>{{ $inquiry->package->name ?? 'N/A' }}</td>
                                <td>{{ $inquiry->pax }}</td>
                                <td>{{ $inquiry->created_at->format('M j, Y') }}</td>
                                <td><span class="badge {{ match($inquiry->status) { 'pending' => 'badge-orange', 'contacted' => 'badge-blue', 'confirmed' => 'badge-green', 'cancelled' => 'badge-red', default => 'badge-gray' } }}">{{ ucfirst($inquiry->status) }}</span></td>
                                <td><a href="mailto:{{ $inquiry->contact_email }}" class="btn btn-outline btn-sm">Reply</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No inquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section-box">
            <div class="section-box-header">
                <span class="section-box-title">Destination Requests</span>
                <a href="{{ route('agency.destinations.create') }}" class="btn btn-ghost btn-sm">New</a>
            </div>
            <div style="padding:.5rem 1rem 1rem;">
                @forelse($destinationRequests as $request)
                    <div style="padding:.85rem 0;border-bottom:1px solid var(--line);">
                        <div style="display:flex;justify-content:space-between;gap:.75rem;">
                            <strong>{{ $request->name }}</strong>
                            <span class="badge {{ match($request->status) { 'approved' => 'badge-green', 'rejected' => 'badge-red', default => 'badge-orange' } }}">{{ ucfirst($request->status) }}</span>
                        </div>
                        <p style="color:var(--text-muted);font-size:.84rem;margin-top:.25rem;">{{ $request->location }}, {{ $request->country }} - {{ $request->created_at->format('M j, Y') }}</p>
                    </div>
                @empty
                    <p style="color:var(--text-muted);padding:1rem 0;">No destination requests yet. Submit one when your package needs a new place.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
