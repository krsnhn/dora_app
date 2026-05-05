@extends('layouts.app')

@section('title', 'Destinations')

@push('styles')
<style>
    .dest-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;padding-bottom:3rem}
    .dest-card{background:var(--surface);border:1px solid var(--line);border-radius:12px;overflow:hidden;box-shadow:var(--shadow-sm)}
    .dest-card:hover{box-shadow:var(--shadow-md);transform:translateY(-2px)}
    .dest-img{height:210px;background:linear-gradient(135deg,var(--ocean-blue),var(--forest-green));position:relative;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;letter-spacing:.04em;text-transform:uppercase;font-size:.82rem;text-align:center}
    .dest-img img{width:100%;height:100%;object-fit:cover}
    .dest-country{position:absolute;top:.8rem;right:.8rem;background:rgba(0,0,0,.68);color:white;border-radius:999px;padding:.25rem .7rem;font-size:.72rem;font-weight:800}
    .dest-body{padding:1rem}
    .dest-name{font-family:Inter,system-ui,sans-serif;font-size:1.08rem;font-weight:800;margin-bottom:.35rem;color:var(--text-dark)}
    .dest-desc{color:var(--text-muted);font-size:.88rem;line-height:1.5;margin:.75rem 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .dest-tags{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.75rem}
    .tag{background:var(--platinum-beige);color:var(--text-dark);border-radius:999px;padding:.18rem .55rem;font-size:.72rem;font-weight:700}
    @media(max-width:1000px){.dest-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:680px){.dest-grid{grid-template-columns:1fr}}

    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .page-header {
        background: linear-gradient(160deg, var(--deep-earth) 0%, var(--forest-green) 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
        color: white;
    }
    
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        color: rgba(255,255,255,0.9);
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Explore Destinations </h1>
        <p>Discover {{ number_format($destinations->total()) }} places and connect with trusted agencies.</p>
    </div>
</div>

<div class="container" style="padding-top:1.5rem;">
    <form action="{{ route('destinations.index') }}" method="GET" class="card" style="padding:1rem;margin-bottom:1rem;display:flex;gap:.75rem;flex-wrap:wrap;align-items:end;">
        <div style="flex:2;min-width:240px;">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Destination, country, tag, or location">
        </div>
        <div style="flex:1;min-width:180px;">
            <label class="form-label">Country</label>
            <select name="country" class="form-input">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Apply</button>
        @if(request()->hasAny(['search','country']))
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">Clear</a>
        @endif
    </form>

    <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;color:var(--text-muted);font-size:.9rem;margin:1rem 0;">
        <span><strong>{{ number_format($destinations->total()) }}</strong> destinations found</span>
        @if(request('search'))<span>Search: <strong>{{ request('search') }}</strong></span>@endif
    </div>

    <div class="dest-grid">
        @forelse($destinations as $destination)
            <article class="dest-card">
                <div class="dest-img">
                    @if($destination->image_url || $destination->image_path)
                        <img src="{{ $destination->image_url ?: $destination->image_path }}" alt="{{ $destination->name }}" loading="lazy">
                    @else
                        <span>No Photo Available</span>
                    @endif
                    <span class="dest-country">{{ $destination->country }}</span>
                </div>
                <div class="dest-body">
                    <h2 class="dest-name">{{ $destination->name }}</h2>
                    <p style="color:var(--text-muted);font-size:.84rem;">{{ $destination->location ?: $destination->country }}</p>
                    @if($destination->tags)
                        <div class="dest-tags">
                            @foreach(array_slice($destination->tags_array, 0, 3) as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                    <p class="dest-desc">{{ $destination->description }}</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:.75rem;border-top:1px solid var(--line);padding-top:.85rem;">
                        <span style="color:var(--text-muted);font-size:.82rem;">{{ $destination->tour_packages_count }} {{ Str::plural('package', $destination->tour_packages_count) }}</span>
                        <a href="{{ route('destinations.show', $destination) }}" class="btn btn-primary btn-sm">Explore</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="card" style="grid-column:1/-1;text-align:center;padding:3rem 1rem;">
                <h2 style="font-size:1.4rem;color:var(--text-dark);">No destinations found</h2>
                <p style="color:var(--text-muted);margin:.5rem 0 1rem;">Try a different search or clear your filters.</p>
                <a href="{{ route('destinations.index') }}" class="btn btn-primary">Clear filters</a>
            </div>
        @endforelse
    </div>

    {{ $destinations->appends(request()->query())->links('vendor.pagination.dora') }}
</div>
@endsection
