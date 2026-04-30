@extends('layouts.app')

@section('content')
@push('styles')
<style>
    /* Reset and Base */
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    /* Filters Section */
    .filter-container {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 2;
        min-width: 240px;
    }
    
    .filter-group.small {
        flex: 1;
        min-width: 160px;
    }
    
    .filter-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    
    .search-input-wrapper {
        position: relative;
    }
    
    .search-input-wrapper svg {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    
    .search-input-wrapper input {
        padding-left: 2.5rem;
        width: 100%;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid var(--platinum-beige-dark);
        border-radius: var(--radius-sm);
        font-family: 'Jost', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--forest-green);
    }
    
    .btn-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    /* Results Meta */
    .results-meta {
        padding: 1.5rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--text-muted);
        font-size: 0.875rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    /* GRID SYSTEM - 3 COLUMNS */
    .dest-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding-bottom: 4rem;
    }
    
    /* CARD DESIGN - Everything in one card */
    .dest-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(44, 24, 16, 0.08);
        display: block;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .dest-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    /* IMAGE CONTAINER */
    .dest-img {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .dest-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .dest-card:hover .dest-img img {
        transform: scale(1.05);
    }
    
    .dest-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        background: linear-gradient(135deg, var(--deep-earth), var(--ocean-blue));
        color: white;
    }
    
    /* BADGES - Positioned over image */
    .dest-country-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        color: white;
        padding: 0.35rem 0.875rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        z-index: 2;
        text-transform: uppercase;
    }
    
    .dest-fav-btn {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        cursor: pointer;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 1.2rem;
        text-decoration: none;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .dest-fav-btn:hover {
        transform: scale(1.15);
        background: white;
    }
    
    /* CARD BODY - Details section */
    .dest-body {
        padding: 1.25rem;
        background: white;
    }
    
    .dest-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .dest-loc {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }
    
    .dest-loc svg {
        width: 12px;
        height: 12px;
        flex-shrink: 0;
    }
    
    .dest-tags {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .tag {
        background: rgba(230, 126, 34, 0.1);
        color: var(--sunset-orange);
        padding: 0.25rem 0.625rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .dest-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .dest-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.875rem;
        border-top: 1px solid rgba(44, 24, 16, 0.08);
    }
    
    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state .emoji {
        font-size: 4rem;
        margin-bottom: 1.25rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        padding: 1rem 0;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .dest-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .dest-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group,
        .filter-group.small {
            width: 100%;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-group .btn {
            flex: 1;
        }
        
        .results-meta {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .dest-img {
            height: 200px;
        }
    }
</style>
@endpush

<div class="page-header">
    <div class="page-header-content">
        <h1>Explore <strong>Destinations</strong></h1>
        <p>Discover {{ number_format($destinations->total()) }} incredible places waiting to be explored</p>
    </div>
</div>

<div class="container">
    <!-- Filters Section -->
    <div class="filter-container">
        <form action="{{ route('destinations.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label>🔍 Search</label>
                <div class="search-input-wrapper">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" class="form-input"
                           placeholder="Destinations, countries, or tags..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="filter-group small">
                <label>🌍 Country</label>
                <select name="country" class="form-input" onchange="this.form.submit()">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                @if(request()->hasAny(['search', 'country']))
                    <a href="{{ route('destinations.index') }}" class="btn btn-secondary">Clear All</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Results Meta -->
    <div class="results-meta">
        <div>
            📍 <strong>{{ number_format($destinations->total()) }}</strong> destinations found
        </div>
        @if(request('search'))
            <div>
                🔎 Results for: <strong>"{{ request('search') }}"</strong>
            </div>
        @endif
        @if(request('country'))
            <div>
                🌍 Country: <strong>{{ request('country') }}</strong>
            </div>
        @endif
    </div>

    <!-- Destinations Grid -->
    <div class="dest-grid">
        @forelse($destinations as $destination)
        <div class="dest-card">
            <a href="{{ route('destinations.show', $destination) }}" style="text-decoration: none; color: inherit; display: block;">
                <div class="dest-img">
                    @if($destination->image_url)
                        <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" loading="lazy">
                    @else
                        <div class="dest-img-placeholder">🏝️</div>
                    @endif
                    <span class="dest-country-badge">{{ $destination->country }}</span>
                    
                    @if(auth()->check() && auth()->user()->role === 'traveler')
                        <a href="javascript:void(0)" 
                           onclick="event.preventDefault(); toggleFavorite({{ $destination->id }}, this)" 
                           class="dest-fav-btn">
                            {{ $destination->isFavorited() ? '❤️' : '🤍' }}
                        </a>
                    @endif
                </div>
                
                <div class="dest-body">
                    <h2 class="dest-name">{{ $destination->name }}</h2>
                    <div class="dest-loc">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $destination->location }}
                    </div>
                    
                    @if($destination->tags)
                    <div class="dest-tags">
                        @foreach(array_slice($destination->tags_array, 0, 3) as $tag)
                            <span class="tag">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <p class="dest-desc">{{ Str::limit($destination->description, 100) }}</p>
                    
                    <div class="dest-footer">
                        <span style="color: var(--forest-green); font-size: 0.8rem; font-weight: 600;">
                            📦 {{ $destination->tour_packages_count }} {{ Str::plural('Package', $destination->tour_packages_count) }}
                        </span>
                        <span style="color: var(--sunset-orange); font-size: 0.8rem; font-weight: 600;">
                            Explore →
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="empty-state">
            <div class="emoji">🔍</div>
            <h3 style="font-family:'Cormorant Garamond',serif; color: var(--deep-earth); font-size: 1.5rem; margin-bottom: 0.5rem;">
                No destinations found
            </h3>
            <p>Try adjusting your search or clearing the filters</p>
            <a href="{{ route('destinations.index') }}" class="btn btn-primary" style="margin-top: 1.5rem;">
                Clear All Filters
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $destinations->appends(request()->query())->links('vendor.pagination.dora') }}
    </div>
</div>

@push('scripts')
<script>
function toggleFavorite(id, element) {
    event.stopPropagation();
    fetch(`/favorites/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.favorited) {
            element.textContent = '❤️';
            element.style.transform = 'scale(1.2)';
            setTimeout(() => { element.style.transform = ''; }, 200);
        } else {
            element.textContent = '🤍';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Please login to save favorites');
    });
}
</script>
@endpush
@endsection