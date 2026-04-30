@extends('layouts.app')
@section('content')
@push('styles')
<style>
    .dest-hero { position: relative; height: 480px; overflow: hidden; }
    .dest-hero img { width: 100%; height: 100%; object-fit: cover; }
    .dest-hero-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--deep-earth), var(--ocean-blue)); display: flex; align-items: center; justify-content: center; font-size: 8rem; }
    .dest-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(44,24,16,0.85) 0%, rgba(44,24,16,0.2) 60%, transparent 100%);
    }
    .dest-hero-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 2.5rem 2rem;
        max-width: 1280px; margin: 0 auto;
    }
    .dest-hero-content h1 {
        font-size: clamp(2.5rem, 6vw, 4rem);
        color: white; font-weight: 300; margin-bottom: 0.5rem;
    }
    .dest-hero-content h1 strong { font-weight: 600; }
    .dest-hero-meta { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
    .dest-hero-meta span { color: rgba(255,255,255,0.8); font-size: 0.875rem; display: flex; align-items: center; gap: 0.3rem; }
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2rem;
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem;
    }
    .main-col { display: flex; flex-direction: column; gap: 2rem; }
    .side-col { display: flex; flex-direction: column; gap: 1.5rem; }
    .content-box { background: white; border-radius: var(--radius); padding: 2rem; border: 1px solid rgba(44,24,16,0.06); }
    .content-box-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.4rem; font-weight: 600;
        color: var(--deep-earth); margin-bottom: 1.25rem;
        padding-bottom: 0.875rem; border-bottom: 2px solid var(--platinum-beige-dark);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .desc-text { color: var(--text-muted); line-height: 1.8; font-size: 0.95rem; }
    .tags-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .tag-large {
        background: var(--off-white); color: var(--deep-earth);
        padding: 0.375rem 1rem; border-radius: 20px;
        font-size: 0.8rem; font-weight: 500;
        border: 1px solid var(--platinum-beige-dark);
    }
    /* Weather widget */
    .weather-card {
        background: linear-gradient(135deg, var(--ocean-blue), #0d2e47);
        border-radius: var(--radius); padding: 1.5rem; color: white;
    }
    .weather-title { font-size: 0.7rem; letter-spacing: 2px; text-transform: uppercase; opacity: 0.6; margin-bottom: 1rem; }
    .weather-main { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .weather-temp { font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; font-weight: 600; line-height: 1; }
    .weather-icon { font-size: 3rem; }
    .weather-condition { font-size: 0.875rem; opacity: 0.85; text-transform: capitalize; }
    .weather-details { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .weather-detail { background: rgba(255,255,255,0.1); border-radius: 8px; padding: 0.75rem; }
    .weather-detail-label { font-size: 0.7rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; }
    .weather-detail-val { font-size: 1rem; font-weight: 600; margin-top: 0.2rem; }
    /* Packages */
    .package-card {
        border: 1px solid rgba(44,24,16,0.08);
        border-radius: var(--radius-sm); padding: 1.5rem;
        transition: all 0.2s; cursor: pointer;
    }
    .package-card:hover { border-color: var(--sunset-orange); box-shadow: var(--shadow-sm); }
    .pkg-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
    .pkg-name { font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; font-weight: 600; color: var(--deep-earth); }
    .pkg-agency { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem; }
    .pkg-price { font-size: 1.4rem; font-weight: 700; color: var(--forest-green); white-space: nowrap; }
    .pkg-price span { font-size: 0.75rem; font-weight: 400; color: var(--text-muted); }
    .pkg-desc { font-size: 0.83rem; color: var(--text-muted); line-height: 1.6; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .pkg-meta { display: flex; gap: 1rem; font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1rem; }
    .pkg-meta span { display: flex; align-items: center; gap: 0.3rem; }
    /* Inquiry modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(44,24,16,0.6);
        backdrop-filter: blur(4px); z-index: 2000; align-items: center; justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.open { display: flex; }
    .modal {
        background: white; border-radius: var(--radius-lg); padding: 2.25rem;
        max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto;
        animation: modalIn 0.25s ease;
    }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(20px); } to { opacity: 1; transform: none; } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .modal-title { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 600; color: var(--deep-earth); }
    .modal-close { background: none; border: none; cursor: pointer; font-size: 1.5rem; color: var(--text-muted); line-height: 1; }
    .modal-close:hover { color: var(--deep-earth); }
    /* Map */
    #map { width: 100%; height: 300px; border-radius: var(--radius-sm); }
    /* Memories strip */
    .memories-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
    .memory-thumb { aspect-ratio: 1; border-radius: 8px; overflow: hidden; }
    .memory-thumb img { width: 100%; height: 100%; object-fit: cover; }
    /* Feedback */
    .feedback-item { border-bottom: 1px solid var(--platinum-beige-dark); padding-bottom: 1.25rem; margin-bottom: 1.25rem; }
    .feedback-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .stars { color: #f59e0b; letter-spacing: 2px; }
    @media(max-width:900px) {
        .content-grid { grid-template-columns: 1fr; }
        .dest-hero { height: 320px; }
    }
</style>
@endpush

<!-- Hero Image -->
<div class="dest-hero">
    @if($destination->image_path)
        <img src="{{ $destination->image_path }}" alt="{{ $destination->name }}">
    @else
        <div class="dest-hero-placeholder">🏝️</div>
    @endif
    <div class="dest-hero-overlay"></div>
    <div class="dest-hero-content">
        <div class="breadcrumb" style="margin-bottom:0.875rem;">
            <a href="{{ route('home') }}">Home</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ route('destinations.index') }}">Destinations</a>
            <span class="breadcrumb-sep">›</span>
            <span>{{ $destination->name }}</span>
        </div>
        <h1><strong>{{ $destination->name }}</strong></h1>
        <div class="dest-hero-meta">
            <span>📍 {{ $destination->location }}, {{ $destination->country }}</span>
            <span>📦 {{ $destination->tourPackages->count() }} packages</span>
            @if(auth()->check() && auth()->user()->role === 'traveler')
            <form action="{{ route('favorites.toggle', $destination) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;padding:0.375rem 1rem;border-radius:20px;cursor:pointer;font-size:0.8rem;font-family:'Jost',sans-serif;">
                    {{ $isFavorited ? '❤️ Saved' : '🤍 Save' }}
                </button>
            </form>
            @endauth
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Main column -->
    <div class="main-col">
        <!-- Description -->
        <div class="content-box">
            <h2 class="content-box-title">📖 About This Destination</h2>
            <p class="desc-text">{{ $destination->description }}</p>
            @if($destination->tags)
            <div class="tags-list" style="margin-top:1.5rem;">
                @foreach($destination->tags_array as $tag)
                    <a href="{{ route('destinations.index', ['search' => $tag]) }}" class="tag-large">{{ $tag }}</a>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Map -->
        @if($destination->latitude && $destination->longitude)
        <div class="content-box">
            <h2 class="content-box-title">🗺️ Location on Map</h2>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <div id="map" style="height: 400px; border-radius: 12px; z-index: 0;"></div>

            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                const map = L.map('map').setView([{{ $destination->latitude }}, {{ $destination->longitude }}], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}])
                    .addTo(map)
                    .bindPopup('<strong>{{ $destination->name }}</strong><br>{{ $destination->country }}')
                    .openPopup();
            </script>
        </div>
        @endif

        <!-- Tour Packages -->
        @if($destination->tourPackages->count())
            <div class="content-box">
                <h2 class="content-box-title">🎒 Available Tour Packages</h2>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    @foreach($destination->tourPackages->where('status', 'active') as $package)
                    <div class="package-card">
                        <div class="pkg-header">
                            <div>
                                <div class="pkg-name">{{ $package->name }}</div>
                                <div class="pkg-agency">by {{ $package->agency->business_name ?? $package->agency->name }}</div>
                            </div>
                            <div>
                                <div class="pkg-price">₱{{ number_format($package->price, 2) }} <span>/ pax</span></div>
                            </div>
                        </div>
                        <p class="pkg-desc">{{ $package->description }}</p>
                        <div class="pkg-meta">
                            <span>⏱ {{ $package->duration }}</span>
                            @if($package->inclusions)
                            <span>✅ Inclusions available</span>
                            @endif
                        </div>
                        @if(auth()->check() && auth()->user()->role === 'traveler')
                            <button class="btn btn-primary btn-sm" type="button"
                                onclick="openInquiry({{ $package->id }}, '{{ addslashes($package->name) }}', '{{ addslashes($package->agency->business_name ?? $package->agency->name) }}')">
                                Inquire Now →
                            </button>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @endif
        

        <!-- Feedback -->
        @if($feedback->count())
        <div class="content-box">
            <h2 class="content-box-title">⭐ Traveler Reviews</h2>
            @foreach($feedback as $fb)
            <div class="feedback-item">
                <div style="display:flex;justify-content:space-between;margin-bottom:0.375rem;">
                    <strong style="font-size:0.9rem;">{{ $fb->user->name }}</strong>
                    <span class="stars">{{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}</span>
                </div>
                <p style="font-size:0.875rem;color:var(--text-muted);">{{ $fb->comment }}</p>
                <span style="font-size:0.75rem;color:#aaa;">{{ $fb->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Memories from travelers -->
        @if($destination->memories->count())
        <div class="content-box">
            <h2 class="content-box-title">📸 Traveler Memories</h2>
            <div class="memories-strip">
                @foreach($destination->memories as $memory)
                <div class="memory-thumb">
                    <img src="{{ $memory->image_path }}" alt="{{ $memory->caption ?? 'Travel memory' }}" loading="lazy">
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Side column -->
    <div class="side-col">
        <!-- Weather -->
        @if($weather)
        <div class="weather-card">
            <div class="weather-title">Current Weather · {{ $weather['city'] }}, {{ $weather['country'] }}</div>
            <div class="weather-main">
                <div>
                    <div class="weather-temp">{{ $weather['temperature'] }}°C</div>
                    <div class="weather-condition">{{ $weather['description'] }}</div>
                </div>
                <div class="weather-icon">
                    <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png" alt="{{ $weather['condition'] }}" style="width:80px;height:80px;" onerror="this.style.display='none'">
                </div>
            </div>
            <div class="weather-details">
                <div class="weather-detail">
                    <div class="weather-detail-label">Humidity</div>
                    <div class="weather-detail-val">{{ $weather['humidity'] }}%</div>
                </div>
                <div class="weather-detail">
                    <div class="weather-detail-label">Wind</div>
                    <div class="weather-detail-val">{{ $weather['wind_speed'] }} km/h</div>
                </div>
                <div class="weather-detail">
                    <div class="weather-detail-label">Feels Like</div>
                    <div class="weather-detail-val">{{ $weather['feels_like'] }}°C</div>
                </div>
                <div class="weather-detail">
                    <div class="weather-detail-label">Condition</div>
                    <div class="weather-detail-val">{{ $weather['condition'] }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="content-box">
            <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);margin-bottom:1rem;font-size:1.2rem;">Quick Info</h3>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span style="color:var(--text-muted);">📍 Location</span>
                    <span style="font-weight:500;text-align:right;">{{ $destination->location }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span style="color:var(--text-muted);">🌍 Country</span>
                    <span style="font-weight:500;">{{ $destination->country }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span style="color:var(--text-muted);">📦 Packages</span>
                    <span style="font-weight:500;color:var(--forest-green);">{{ $destination->tourPackages->count() }} available</span>
                </div>
            </div>

            @if(auth()->check() && auth()->user()->role === 'traveler')
            <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--platinum-beige-dark);">
                <form action="{{ route('favorites.toggle', $destination) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $isFavorited ? 'btn-outline' : 'btn-primary' }}" style="width:100%;justify-content:center;">
                        {{ $isFavorited ? '❤️ Saved to Favorites' : '🤍 Save to Favorites' }}
                    </button>
                </form>
            </div>
            @endauth
        </div>

        <!-- Leave Feedback (if user has inquired) -->
        @auth
        @if(auth()->user()->isTraveler())
        <div class="content-box">
            <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);margin-bottom:1.25rem;font-size:1.2rem;">Leave Feedback</h3>
            @if($destination->tourPackages->count())
            <form action="{{ route('feedback.store') }}" method="POST">
                @csrf
                <input type="hidden" name="agency_id" value="{{ $destination->tourPackages->first()->agency_id }}">
                <input type="hidden" name="package_id" value="{{ $destination->tourPackages->first()->id }}">
                <div class="form-group">
                    <label class="form-label">Your Rating</label>
                    <div style="display:flex;gap:0.5rem;font-size:1.75rem;cursor:pointer;" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                        <span onclick="setRating({{ $i }})" data-star="{{ $i }}" style="color:#d4c5a4;transition:color 0.1s;">★</span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-input" placeholder="Share your experience..." rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Submit Feedback</button>
            </form>
            @else
            <p style="color:var(--text-muted);font-size:0.875rem;">No packages to leave feedback on yet.</p>
            @endif
        </div>
        @endif
        @endauth
    </div>
</div>

<!-- Inquiry Modal -->
<div class="modal-overlay" id="inquiryModal">
    <div class="modal">
        <div class="modal-header">
            <h2 class="modal-title">Inquire About Package</h2>
            <button class="modal-close" onclick="closeInquiry()">×</button>
        </div>
        <div id="modalPkgInfo" style="background:var(--off-white);border-radius:var(--radius-sm);padding:1rem;margin-bottom:1.5rem;">
            <div id="modalPkgName" style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:var(--deep-earth);"></div>
            <div id="modalPkgAgency" style="font-size:0.8rem;color:var(--text-muted);margin-top:0.2rem;"></div>
        </div>
        @auth
        <form id="inquiryForm" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="contact_name" class="form-input" value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="contact_email" class="form-input" value="{{ auth()->user()->email }}" required>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="contact_phone" class="form-input" placeholder="+63 9XX XXX XXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">Number of Pax *</label>
                    <input type="number" name="pax" class="form-input" value="1" min="1" max="100" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Preferred Travel Date</label>
                <input type="date" name="travel_date" class="form-input" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-input" placeholder="Tell the agency about your plans, preferences, or questions..." rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Send Inquiry →</button>
        </form>
        @else
        <p style="text-align:center;color:var(--text-muted);margin-bottom:1.5rem;">Please sign in to send an inquiry.</p>
        <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;justify-content:center;">Sign In to Inquire</a>
        @endauth
    </div>
</div>

@push('scripts')
<script>
function openInquiry(pkgId, pkgName, agencyName) {
    document.getElementById('modalPkgName').textContent = pkgName;
    document.getElementById('modalPkgAgency').textContent = 'by ' + agencyName;
    const form = document.getElementById('inquiryForm');
    if (form) form.action = `/inquiries/${pkgId}`;
    document.getElementById('inquiryModal').classList.add('open');
}
function closeInquiry() {
    document.getElementById('inquiryModal').classList.remove('open');
}
document.getElementById('inquiryModal').addEventListener('click', function(e) {
    if (e.target === this) closeInquiry();
});

// Star rating
function setRating(value) {
    document.getElementById('ratingInput').value = value;
    document.querySelectorAll('#starRating span').forEach((s, i) => {
        s.style.color = i < value ? '#f59e0b' : '#d4c5a4';
    });
}

@endpush
@endsection
