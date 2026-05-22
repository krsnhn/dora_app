@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Search')

@push('styles')
<style>
    .search-wrap{padding:2rem 0 4rem}
    .search-summary{display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.25rem}
    .search-title{font-family:Inter,system-ui,sans-serif;font-size:1.45rem;font-weight:850;color:var(--text-dark);margin:0}
    .search-meta{color:var(--text-muted);font-size:.92rem;margin-top:.25rem}
    .search-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}
    .result-section{background:var(--surface);border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow-sm);overflow:hidden}
    .result-head{padding:1rem 1.1rem;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;gap:.75rem}
    .result-head h2{font-family:Inter,system-ui,sans-serif;font-size:.95rem;font-weight:850;color:var(--text-dark);margin:0}
    .result-list{display:grid}
    .result-item{display:block;text-decoration:none;color:inherit;padding:1rem 1.1rem;border-bottom:1px solid var(--line)}
    .result-item:last-child{border-bottom:0}
    .result-item:hover{background:var(--surface-soft)}
    .result-name{font-weight:800;color:var(--text-dark);margin-bottom:.2rem}
    .result-text{color:var(--text-muted);font-size:.84rem;line-height:1.45}
    .result-tags{display:flex;gap:.4rem;flex-wrap:wrap;margin-top:.55rem}
    .result-empty{padding:1.25rem;color:var(--text-muted);font-size:.9rem}
    @media(max-width:1050px){.search-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<div class="container search-wrap">
    <div class="search-summary">
        <div>
            <h1 class="search-title">Global Search</h1>
            @if($query)
                <p class="search-meta">Results for <strong>{{ $query }}</strong></p>
            @else
                <p class="search-meta">Search packages, destinations, and agencies from the header.</p>
            @endif
        </div>
        @if($query)
            <a href="{{ route('global.search') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </div>

    <div class="search-grid">
        <section class="result-section">
            <div class="result-head">
                <h2>Packages</h2>
                <span class="badge badge-blue">{{ $packages->count() }}</span>
            </div>
            <div class="result-list">
                @forelse($packages as $package)
                    @php
                        $packageHref = $isAdmin && $package->agency
                            ? route('admin.agencies.show', $package->agency)
                            : ($package->destination ? route('destinations.show', $package->destination) : route('global.search', ['q' => $query]));
                    @endphp
                    <a href="{{ $packageHref }}" class="result-item">
                        <div class="result-name">{{ $package->name }}</div>
                        <div class="result-text">
                            {{ $package->destination?->name ?? 'No destination' }}
                            @if($package->agency)
                                by {{ $package->agency->business_name ?: $package->agency->name }}
                            @endif
                        </div>
                        <div class="result-tags">
                            <span class="badge badge-earth">{{ $package->duration }}</span>
                            <span class="badge {{ $package->status === 'active' ? 'badge-success' : 'badge-gray' }}">{{ $package->status }}</span>
                        </div>
                    </a>
                @empty
                    <div class="result-empty">{{ $query ? 'No matching packages.' : 'Enter a search term to find packages.' }}</div>
                @endforelse
            </div>
        </section>

        <section class="result-section">
            <div class="result-head">
                <h2>Destinations</h2>
                <span class="badge badge-green">{{ $destinations->count() }}</span>
            </div>
            <div class="result-list">
                @forelse($destinations as $destination)
                    <a href="{{ $isAdmin ? route('admin.destinations.edit', $destination) : route('destinations.show', $destination) }}" class="result-item">
                        <div class="result-name">{{ $destination->name }}</div>
                        <div class="result-text">{{ $destination->location ?: $destination->country }}</div>
                        <div class="result-text">{{ Str::limit($destination->description, 110) }}</div>
                        <div class="result-tags">
                            <span class="badge badge-earth">{{ $destination->country }}</span>
                            <span class="badge badge-blue">{{ $destination->tour_packages_count }} {{ Str::plural('package', $destination->tour_packages_count) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="result-empty">{{ $query ? 'No matching destinations.' : 'Enter a search term to find destinations.' }}</div>
                @endforelse
            </div>
        </section>

        <section class="result-section">
            <div class="result-head">
                <h2>Agencies</h2>
                <span class="badge badge-orange">{{ $agencies->count() }}</span>
            </div>
            <div class="result-list">
                @forelse($agencies as $agency)
                    <a href="{{ $isAdmin ? route('admin.agencies.show', $agency) : route('agencies.show', $agency) }}" class="result-item">
                        <div class="result-name">{{ $agency->business_name ?: $agency->name }}</div>
                        <div class="result-text">{{ $agency->address ?: $agency->email }}</div>
                        <div class="result-tags">
                            <span class="badge badge-earth">{{ $agency->agency_status }}</span>
                            <span class="badge badge-blue">{{ $agency->listed_packages_count }} {{ Str::plural('package', $agency->listed_packages_count) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="result-empty">{{ $query ? 'No matching agencies.' : 'Enter a search term to find agencies.' }}</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
