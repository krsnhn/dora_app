@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
@push('styles')
<style>
    /* Reset and Base */
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
    
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding-bottom: 4rem;
    }
    
    .favorite-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(44, 24, 16, 0.08);
        display: block;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        position: relative;
    }
    
    .favorite-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    .card-img {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .favorite-card:hover .card-img img {
        transform: scale(1.05);
    }
    
    .card-img-placeholder {
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
    
    .remove-fav-btn {
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
    
    .remove-fav-btn:hover {
        transform: scale(1.1);
        background: #ef4444;
    }
    
    .remove-fav-btn:hover svg {
        fill: white;
        stroke: white;
    }
    
    /* CARD BODY - Details section */
    .card-body {
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
    
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(44,24,16,0.08);
    }
    
    .empty-state {
        text-align: center;
        padding: 5rem 1rem;
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
    }
    
    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--platinum-beige);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    @media (max-width: 768px) {
        .favorites-grid {
            grid-template-columns: 1fr;
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
    }
</style>
@endpush

<div class="page-header">
    <div class="container">
        <h1 class="page-title">My Favorites</h1>
        <p class="page-subtitle">Destinations you've saved for future adventures</p>
    </div>
</div>

<div class="container">
    <!-- Search Filters -->
    <div class="filter-container">
        <form action="{{ route('favorites.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label>🔍 Search Favorites</label>
                <div class="search-input-wrapper">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" class="form-input"
                           placeholder="Search by destination name, country, or tags..."
                           value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="filter-group small">
                <label>🌍 Country</label>
                <select name="country" class="form-input" onchange="this.form.submit()">
                    <option value="">All Countries</option>
                    @foreach($countries ?? [] as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                @if(request()->hasAny(['search', 'country']))
                    <a href="{{ route('favorites.index') }}" class="btn btn-secondary">Clear All</a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Results Meta -->
    @if($favorites->count())
    <div class="results-meta">
        <div>
            ❤️ <strong>{{ $favorites->total() ?? $favorites->count() }}</strong> favorites found
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
    @endif
    
    <!-- Favorites Grid -->
    @if($favorites->count())
    <div class="favorites-grid">
        @foreach($favorites as $favorite)
        @php $dest = $favorite->destination; @endphp
        @if($dest)
        <div class="favorite-card" data-favorite-id="{{ $favorite->id }}">
            <div class="card-img">
                @if($dest->image_url)
                    <img src="{{ $dest->image_url }}" alt="{{ $dest->name }}" loading="lazy">
                @else
                    <div class="card-img-placeholder">No Photo Available</div>
                @endif
                
                <div class="dest-country-badge">{{ $dest->country }}</div>
                
                <button onclick="removeFavorite({{ $dest->id }}, this)"
                        class="remove-fav-btn"
                        title="Remove from favorites">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444" stroke-width="1.5">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
            
            <div class="card-body">
                <div class="dest-name">{{ $dest->name }}</div>
                
                <div class="dest-loc">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $dest->country }}
                </div>
                
                @if($dest->tags)
                <div class="dest-tags">
                    @foreach(array_slice($dest->tags_array, 0, 3) as $tag)
                        <span class="tag">#{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
                
                <div class="dest-desc">{{ Str::limit($dest->description, 100) }}</div>
                
                <div class="card-footer">
                    <span style="font-size:.8rem;color:var(--text-muted);">
                        📦 {{ $dest->tourPackages()->where('status','active')->count() }} packages available
                    </span>
                    <a href="{{ route('destinations.show', $dest) }}" class="btn btn-primary btn-sm">Explore →</a>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($favorites, 'links'))
    <div style="margin-top: 2rem;">
        {{ $favorites->appends(request()->query())->links() }}
    </div>
    @endif
    
    @else
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="48" height="48" fill="none" stroke="var(--earth)" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h2 style="color:var(--earth);margin-bottom:.75rem;">No favorites yet</h2>
        <p style="color:var(--text-muted);max-width:400px;margin:0 auto 2rem;line-height:1.6;">
            @if(request()->hasAny(['search', 'country']))
                No matches found for your search criteria.
            @else
                Start exploring destinations and tap the heart icon to save the ones you love.
            @endif
        </p>
        <a href="{{ route('destinations.index') }}" class="btn btn-primary">Explore Destinations</a>
    </div>
    @endif
</div>

<script>
async function removeFavorite(destId, btn) {
    const confirmed = await window.doraConfirm({
        title: 'Remove Favorite',
        message: 'Remove this destination from your favorites?',
        confirmText: 'Remove',
        danger: true
    });

    if (!confirmed) {
        return;
    }
    
    const card = btn.closest('.favorite-card');
    
    fetch(`/favorites/${destId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.favorited === false) {
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.remove();
                
                // Check if no favorites left
                const remainingCards = document.querySelectorAll('.favorite-card');
                if (remainingCards.length === 0) {
                    location.reload();
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.doraAlert('Failed to remove favorite. Please try again.', 'Favorite Not Updated');
    });
}
</script>
@endsection
