@extends('layouts.app')

@section('title', 'Request Destination')

@section('content')
<div class="container" style="padding-top:2rem;padding-bottom:4rem;max-width:900px;">
    <div style="margin-bottom:1.5rem;">
        <a href="{{ route('agency.dashboard') }}" style="color:var(--text-muted);text-decoration:none;font-weight:700;font-size:.9rem;">Back to dashboard</a>
        <h1 class="page-title" style="color:var(--text-dark);margin-top:.4rem;">Request a New Destination</h1>
        <p class="page-sub">Submit destination details for admin approval. Once approved, you can attach packages to it.</p>
    </div>

    <div class="card" style="padding:1.5rem;">
        <form method="POST" action="{{ route('agency.destinations.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Destination name</label>
                    <input name="name" value="{{ old('name') }}" class="form-input" required placeholder="e.g. Lake Sebu">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input name="country" value="{{ old('country', 'Philippines') }}" class="form-input" required>
                    @error('country')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Location</label>
                <input name="location" value="{{ old('location') }}" class="form-input" required placeholder="City, province, or region">
                @error('location')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-input" required placeholder="What makes this destination worth visiting?">{{ old('description') }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Destination Photo</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">JPG, PNG, GIF, or WEBP up to 5MB</div>
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input name="tags" value="{{ old('tags') }}" class="form-input" placeholder="beach, culture, adventure">
                    @error('tags')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Weather location</label>
                    <input name="weather_location" value="{{ old('weather_location') }}" class="form-input" placeholder="OpenWeather city query">
                    @error('weather_location')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input name="latitude" value="{{ old('latitude') }}" class="form-input" type="number" step="any">
                    @error('latitude')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input name="longitude" value="{{ old('longitude') }}" class="form-input" type="number" step="any">
                    @error('longitude')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.5rem;">
                <button class="btn btn-primary" type="submit">Submit for approval</button>
                <a href="{{ route('agency.dashboard') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
