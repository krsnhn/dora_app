@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .hero {
        background: linear-gradient(160deg, var(--deep-earth) 0%, #3d1f12 35%, var(--ocean-blue) 100%);
        min-height: 90vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 5rem 1.5rem;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 800 800'%3E%3Cg fill='none'%3E%3Ccircle r='400' cx='400' cy='400' fill='%23E8DCC0' fill-opacity='0.02'/%3E%3Ccircle r='300' cx='400' cy='400' fill='%23E8DCC0' fill-opacity='0.02'/%3E%3Ccircle r='200' cx='400' cy='400' fill='%23E8DCC0' fill-opacity='0.02'/%3E%3C/g%3E%3C/svg%3E") center right no-repeat;
        background-size: 60%;
    }

    .hero-inner { max-width: 1280px; margin: 0 auto; position: relative; width: 100%; }

    .hero-content { max-width: 680px; }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,127,79,0.15);
        border: 1px solid rgba(255,127,79,0.3);
        color: var(--sunset-orange-light);
        padding: 0.375rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }

    .hero h1 {
        font-size: clamp(3rem, 7vw, 5.5rem);
        font-weight: 300;
        color: var(--platinum-beige);
        line-height: 1.05;
        margin-bottom: 1.5rem;
    }

    .hero h1 em {
        font-style: italic;
        color: var(--sunset-orange);
    }

    .hero-sub {
        color: rgba(232,220,192,0.7);
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 2.5rem;
        max-width: 520px;
    }

    .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 4rem; }

    .hero-stats {
        display: flex;
        gap: 3rem;
        padding-top: 2.5rem;
        border-top: 1px solid rgba(232,220,192,0.15);
    }

    .hero-stat-num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.25rem;
        font-weight: 600;
        color: var(--platinum-beige);
        line-height: 1;
    }

    .hero-stat-label {
        font-size: 0.75rem;
        color: rgba(232,220,192,0.55);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.25rem;
    }

    /* Search bar */
    .hero-search {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: var(--radius);
        padding: 0.5rem;
        display: flex;
        max-width: 560px;
        margin-bottom: 2rem;
    }

    .hero-search input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        padding: 0.625rem 1rem;
        color: var(--platinum-beige);
        font-family: 'Jost', sans-serif;
        font-size: 0.9rem;
    }

    .hero-search input::placeholder { color: rgba(232,220,192,0.4); }

    .hero-search button {
        background: var(--sunset-orange);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Jost', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        transition: background 0.2s;
    }

    .hero-search button:hover { background: #e66d3d; }

    /* Section styles */
    .section-header { text-align: center; margin-bottom: 3.5rem; }
    .section-eyebrow {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--forest-green);
        display: block;
        margin-bottom: 0.75rem;
    }
    .section-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 300;
        color: var(--deep-earth);
    }
    .section-title strong { font-weight: 600; }
    .section-sub {
        color: var(--text-muted);
        font-size: 1rem;
        max-width: 540px;
        margin: 0.75rem auto 0;
        line-height: 1.7;
    }

    /* Destination cards */
    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .dest-card {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all 0.3s;
        border: 1px solid rgba(44,24,16,0.06);
        cursor: pointer;
    }

    .dest-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }

    .dest-card-img {
        aspect-ratio: 4/3;
        overflow: hidden;
        position: relative;
    }

    .dest-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .dest-card:hover .dest-card-img img { transform: scale(1.06); }

    .dest-card-img-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--deep-earth), var(--ocean-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
    }

    .dest-card-country {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(44,24,16,0.75);
        backdrop-filter: blur(8px);
        color: var(--platinum-beige);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .dest-card-body { padding: 1.5rem; }
    .dest-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin-bottom: 0.375rem;
    }
    .dest-card-location {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.875rem;
    }
    .dest-card-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dest-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid var(--platinum-beige-dark);
    }
    .dest-card-packages {
        font-size: 0.8rem;
        color: var(--forest-green);
        font-weight: 600;
    }

    .dest-card-tags {
        display: flex;
        gap: 0.375rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .tag {
        background: var(--off-white);
        color: var(--text-muted);
        padding: 0.2rem 0.625rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid var(--platinum-beige-dark);
    }

    /* Why DORA section */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 2.25rem;
        border: 1px solid rgba(44,24,16,0.06);
        transition: all 0.3s;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--sunset-orange);
    }

    .feature-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, rgba(255,127,79,0.15), rgba(44,95,45,0.1));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.5rem;
    }

    .feature-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin-bottom: 0.625rem;
    }

    .feature-desc {
        color: var(--text-muted);
        font-size: 0.875rem;
        line-height: 1.7;
    }

    /* CTA section */
    .cta-section {
        background: linear-gradient(135deg, var(--forest-green) 0%, #1a3d1b 100%);
        padding: 6rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, rgba(255,127,79,0.12) 0%, transparent 70%);
    }

    .cta-section h2 {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 300;
        color: var(--platinum-beige);
        position: relative;
        margin-bottom: 1.25rem;
    }
    .cta-section h2 strong { color: var(--sunset-orange); font-weight: 600; }
    .cta-section p {
        color: rgba(232,220,192,0.7);
        font-size: 1.05rem;
        max-width: 500px;
        margin: 0 auto 2.5rem;
        position: relative;
    }
    .cta-section .btn-primary { background: var(--sunset-orange); font-size: 1rem; padding: 1rem 2.5rem; }
    .cta-section .btn-outline {
        border-color: rgba(232,220,192,0.4);
        color: var(--platinum-beige);
        font-size: 1rem;
        padding: 1rem 2rem;
    }
    .cta-section .btn-outline:hover { background: rgba(232,220,192,0.1); border-color: var(--platinum-beige); }

    @media (max-width: 768px) {
        .hero-stats { gap: 1.5rem; flex-wrap: wrap; }
        .destinations-grid { grid-template-columns: 1fr; }
        .features-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

<!-- Hero -->
<section class="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <span class="hero-eyebrow">✦ Digital Tourism Connector</span>
            <h1>Discover the<br><em>World's</em> Hidden<br>Treasures</h1>
            <p class="hero-sub">DORA connects passionate travelers with trusted local agencies. Explore stunning destinations, find perfect tour packages, and create memories that last a lifetime.</p>

            <form action="{{ route('destinations.index') }}" method="GET" class="hero-search">
                <input type="text" name="search" placeholder="Search destinations, countries, or experiences..." value="{{ request('search') }}">
                <button type="submit">Explore →</button>
            </form>

            <div class="hero-actions">
                <a href="{{ route('destinations.index') }}" class="btn btn-primary btn-lg">Browse Destinations</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline btn-lg" style="border-color:rgba(232,220,192,0.4);color:var(--platinum-beige);">Join Free</a>
                @endguest
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">{{ $totalDestinations ?? '20' }}+</div>
                    <div class="hero-stat-label">Destinations</div>
                </div>
                <div>
                    <div class="hero-stat-num">{{ $totalPackages ?? '50' }}+</div>
                    <div class="hero-stat-label">Tour Packages</div>
                </div>
                <div>
                    <div class="hero-stat-num">100%</div>
                    <div class="hero-stat-label">Verified Agencies</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Destinations -->
<section class="section" style="background: var(--off-white);">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">✦ Explore</span>
            <h2 class="section-title">Featured <strong>Destinations</strong></h2>
            <p class="section-sub">Handpicked locations offering unforgettable experiences, from pristine beaches to historic mountains.</p>
        </div>

        @if($featuredDestinations->count())
        <div class="destinations-grid">
            @foreach($featuredDestinations as $destination)
            <a href="{{ route('destinations.show', $destination) }}" style="text-decoration:none;">
                <div class="dest-card">
                    <div class="dest-card-img">
                        @if($destination->image_url)
                            <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" loading="lazy">
                        @else
                            <div class="dest-card-img-placeholder">🏝️</div>
                        @endif
                        <span class="dest-card-country">{{ $destination->country }}</span>
                    </div>
                    <div class="dest-card-body">
                        <h3 class="dest-card-title">{{ $destination->name }}</h3>
                        <div class="dest-card-location">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $destination->location }}
                        </div>
                        @if($destination->tags)
                        <div class="dest-card-tags">
                            @foreach(array_slice($destination->tags_array, 0, 3) as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                        @endif
                        <p class="dest-card-desc">{{ $destination->description }}</p>
                        <div class="dest-card-footer">
                            <span class="dest-card-packages">{{ $destination->tour_packages_count }} packages available</span>
                            <span style="color: var(--sunset-orange); font-size:0.8rem; font-weight:600;">View Details →</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:3rem;">
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">View All Destinations</a>
        </div>
        @else
        <div style="text-align:center; padding:4rem; color:var(--text-muted);">
            <div style="font-size:4rem; margin-bottom:1rem;">🌍</div>
            <p>Destinations coming soon. Check back later!</p>
        </div>
        @endif
    </div>
</section>

<!-- Why DORA -->
<section class="section" style="background:white;">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">✦ Why Choose DORA</span>
            <h2 class="section-title">Everything You Need for<br><strong>Perfect Travel</strong></h2>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🗺️</div>
                <h3 class="feature-title">Curated Destinations</h3>
                <p class="feature-desc">Discover 20+ carefully vetted destinations. Every location is reviewed and approved to ensure quality experiences.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🏢</div>
                <h3 class="feature-title">Verified Agencies</h3>
                <p class="feature-desc">All travel agencies on DORA are identity-verified and approved by our admin team before listing packages.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌤️</div>
                <h3 class="feature-title">Live Weather Updates</h3>
                <p class="feature-desc">Get real-time weather information for any destination directly on the details page, powered by OpenWeatherMap.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📸</div>
                <h3 class="feature-title">Travel Memories</h3>
                <p class="feature-desc">Upload and organize your travel photos into personal albums. Create a beautiful digital diary of your adventures.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎒</div>
                <h3 class="feature-title">Smart Backpack</h3>
                <p class="feature-desc">Never forget an essential again. Use our smart packing checklist with categories for every type of traveler.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⭐</div>
                <h3 class="feature-title">Trusted Reviews</h3>
                <p class="feature-desc">Read moderated feedback and ratings from real travelers to make informed decisions about tour packages.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div style="position:relative;">
        <h2>Ready to Start Your<br><strong>Adventure?</strong></h2>
        <p>Join thousands of travelers and agencies already using DORA to discover incredible destinations.</p>
        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary">Create Free Account</a>
                <a href="{{ route('destinations.index') }}" class="btn btn-outline">Explore First</a>
            @else
                <a href="{{ route('destinations.index') }}" class="btn btn-primary">Discover Destinations</a>
            @endguest
        </div>
    </div>
</section>
@endsection
