@extends('layouts.app')
@section('content')
@push('styles')
<style>
    .agency-layout { display:grid; grid-template-columns:240px 1fr; min-height:calc(100vh - 68px); }
    .agency-sidebar { background:var(--deep-earth); padding:2rem 0; }
    .sidebar-section { margin-bottom:2rem; }
    .sidebar-label { font-size:0.65rem; font-weight:700; letter-spacing:2.5px; text-transform:uppercase; color:rgba(232,220,192,0.35); padding:0 1.5rem; margin-bottom:0.5rem; }
    .sidebar-link { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:rgba(232,220,192,0.65); text-decoration:none; font-size:0.875rem; transition:all 0.2s; }
    .sidebar-link:hover, .sidebar-link.active { color:var(--platinum-beige); background:rgba(255,127,79,0.12); border-left:3px solid var(--sunset-orange); padding-left:calc(1.5rem - 3px); }
    .sidebar-link svg { flex-shrink:0; }
    .agency-main { background:var(--off-white); padding:2.5rem; overflow:auto; }
    .page-title { font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:600; color:var(--deep-earth); margin-bottom:0.375rem; }
    .page-sub { color:var(--text-muted); font-size:0.9rem; margin-bottom:2rem; }
    .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem; }
    .kpi-card { background:white; border-radius:var(--radius); padding:1.5rem; border:1px solid rgba(44,24,16,0.06); }
    .kpi-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.125rem; margin-bottom:0.875rem; }
    .kpi-num { font-family:'Cormorant Garamond',serif; font-size:2.25rem; font-weight:600; color:var(--deep-earth); line-height:1; }
    .kpi-label { font-size:0.78rem; color:var(--text-muted); font-weight:500; margin-top:0.25rem; }
    .section-box { background:white; border-radius:var(--radius); border:1px solid rgba(44,24,16,0.06); margin-bottom:1.5rem; }
    .section-box-header { display:flex; justify-content:space-between; align-items:center; padding:1.25rem 1.5rem; border-bottom:1px solid rgba(44,24,16,0.06); }
    .section-box-title { font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:600; color:var(--deep-earth); }
    @media(max-width:900px) { .agency-layout { grid-template-columns:1fr; } .agency-sidebar { display:none; } .grid-4 { grid-template-columns:1fr 1fr; } }
</style>
@endpush

    

    <!-- Main -->
    <main class="agency-main">
        <h1 class="page-title">Agency Dashboard</h1>
        <p class="page-sub">Welcome back, {{ auth()->user()->business_name ?? auth()->user()->name }}! Here's your overview.</p>

        <!-- KPIs -->
        <div class="grid-4">
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(44,95,45,0.1);">📦</div>
                <div class="kpi-num">{{ $totalPackages }}</div>
                <div class="kpi-label">Active Packages</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(30,74,109,0.1);">📩</div>
                <div class="kpi-num">{{ $totalInquiries }}</div>
                <div class="kpi-label">Total Inquiries</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(255,127,79,0.1);">⏳</div>
                <div class="kpi-num">{{ $pendingInquiries }}</div>
                <div class="kpi-label">Pending Inquiries</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon" style="background:rgba(245,158,11,0.1);">⭐</div>
                <div class="kpi-num">{{ $avgRating ? number_format($avgRating, 1) : '—' }}</div>
                <div class="kpi-label">Avg Rating</div>
            </div>
        </div>

        <!-- Quick actions -->
        <div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;">
            <a href="{{ route('agency.packages.create') }}" class="btn btn-primary">+ New Package</a>
            <a href="{{ route('agency.inquiries.index') }}" class="btn btn-secondary">View Inquiries</a>
        </div>

        <!-- Recent Inquiries -->
        <div class="section-box">
            <div class="section-box-header">
                <span class="section-box-title">Recent Inquiries</span>
                <a href="{{ route('agency.inquiries.index') }}" style="font-size:0.8rem;color:var(--sunset-orange);text-decoration:none;">View all →</a>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Traveler</th><th>Package</th><th>Pax</th><th>Date</th><th>Status</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInquiries as $inquiry)
                        <tr>
                            <td>
                                <div style="font-weight:500;">{{ $inquiry->contact_name }}</div>
                                <div style="font-size:0.78rem;color:var(--text-muted);">{{ $inquiry->contact_email }}</div>
                            </td>
                            <td>{{ $inquiry->package->name ?? 'N/A' }}</td>
                            <td>{{ $inquiry->pax }}</td>
                            <td>{{ $inquiry->created_at->format('M j, Y') }}</td>
                            <td>
                                <span class="badge {{ match($inquiry->status) { 'pending' => 'badge-orange', 'contacted' => 'badge-blue', 'confirmed' => 'badge-green', 'cancelled' => 'badge-red', default => 'badge-gray' } }}">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="mailto:{{ $inquiry->contact_email }}" class="btn btn-ghost btn-sm">Reply</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No inquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
