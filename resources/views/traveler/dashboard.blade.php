@extends('layouts.app')

@section('title', 'Traveler Dashboard')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container" style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
        <div>
            <h1 class="page-title" style="color:white;">Traveler Dashboard</h1>
            <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Plan trips, track inquiries, and keep your travel checklist in one place.</p>
        </div>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
            <a href="{{ route('destinations.index') }}" class="btn btn-primary">Find destinations</a>
            <a href="{{ route('backpack.index') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.45);color:white;">Open backpack</a>
        </div>
    </div>
</div>

<div class="container" style="padding-top:2rem;padding-bottom:4rem;">
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card"><div class="stat-number">{{ $stats['favorites'] }}</div><div class="stat-label">Favorites</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['inquiries'] }}</div><div class="stat-label">Inquiries Sent</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['memories'] }}</div><div class="stat-label">Memories</div></div>
        <div class="stat-card"><div class="stat-number">{{ $stats['backpack_open'] }}</div><div class="stat-label">Checklist Items Left</div></div>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1.35fr) minmax(320px,.9fr);gap:1.25rem;align-items:start;">
        <section class="section-box">
            <div class="section-box-header">
                <h2 class="section-box-title">Recommended Packages</h2>
                <a href="{{ route('destinations.index') }}" class="btn btn-ghost btn-sm">Browse all</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;padding:1rem;">
                @forelse($recommendedPackages as $package)
                    <article class="card" style="overflow:hidden;">
                        @if($package->display_image)
                            <img src="{{ $package->display_image }}" alt="{{ $package->name }}" style="width:100%;height:130px;object-fit:cover;">
                        @else
                            <div style="width:100%;height:130px;background:linear-gradient(135deg,var(--ocean-blue),var(--forest-green));color:white;display:flex;align-items:center;justify-content:center;text-align:center;font-size:.78rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;">No Photo Available</div>
                        @endif
                        <div style="padding:1rem;">
                            <h3 style="font-family:Inter,system-ui,sans-serif;font-size:1rem;font-weight:800;margin-bottom:.35rem;">{{ $package->name }}</h3>
                            <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:.75rem;">{{ $package->destination->name ?? 'Destination' }} by {{ $package->agency->business_name ?? $package->agency->name ?? 'Agency' }}</p>
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:.75rem;">
                                <strong>PHP {{ number_format($package->price, 2) }}</strong>
                                @if($package->destination)
                                    <a href="{{ route('destinations.show', $package->destination) }}" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p style="color:var(--text-muted);padding:1rem;">No packages available yet.</p>
                @endforelse
            </div>
        </section>

        <section class="section-box">
            <div class="section-box-header">
                <h2 class="section-box-title">Recent Inquiries</h2>
                <a href="{{ route('inquiries.index') }}" class="btn btn-ghost btn-sm">View all</a>
            </div>
            <div style="padding:.5rem 1rem 1rem;">
                @forelse($recentInquiries as $inquiry)
                    <div style="padding:.8rem 0;border-bottom:1px solid var(--line);">
                        <div style="display:flex;justify-content:space-between;gap:.75rem;">
                            <strong>{{ $inquiry->package->name ?? 'Package' }}</strong>
                            <span class="badge {{ match($inquiry->status) { 'pending' => 'badge-orange', 'contacted' => 'badge-blue', 'confirmed' => 'badge-green', 'cancelled' => 'badge-red', default => 'badge-gray' } }}">{{ ucfirst($inquiry->status) }}</span>
                        </div>
                        <p style="font-size:.84rem;color:var(--text-muted);margin-top:.25rem;">{{ $inquiry->agency->business_name ?? $inquiry->agency->name ?? 'Agency' }} - {{ $inquiry->created_at->format('M j, Y') }}</p>
                    </div>
                @empty
                    <p style="color:var(--text-muted);padding:1rem 0;">No inquiries yet. Pick a package and connect with an agency.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="section-box" style="margin-top:1.25rem;">
        <div class="section-box-header">
            <h2 class="section-box-title">Fresh Destinations</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;padding:1rem;">
            @foreach($featuredDestinations as $destination)
                <a href="{{ route('destinations.show', $destination) }}" class="card" style="text-decoration:none;overflow:hidden;">
                    @if($destination->image_url)
                        <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" style="width:100%;height:145px;object-fit:cover;">
                    @else
                        <div style="width:100%;height:145px;background:linear-gradient(135deg,var(--ocean-blue),var(--forest-green));color:white;display:flex;align-items:center;justify-content:center;text-align:center;font-size:.78rem;font-weight:800;letter-spacing:.04em;text-transform:uppercase;">No Photo Available</div>
                    @endif
                    <div style="padding:1rem;">
                        <strong>{{ $destination->name }}</strong>
                        <p style="font-size:.85rem;color:var(--text-muted);">{{ $destination->country }} - {{ $destination->tour_packages_count }} packages</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection
