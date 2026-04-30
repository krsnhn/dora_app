@extends('layouts.app')

@section('title', 'Edit Destination')

@section('content')
<div class="page-header">
    <div class="container">
        <a href="{{ route('admin.destinations.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--text-muted);text-decoration:none;font-size:.9rem;margin-bottom:.75rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Back to Destinations
        </a>
        <h1 class="page-title">Edit Destination</h1>
        <p class="page-subtitle">Updating: <strong>{{ $destination->name }}</strong></p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;max-width:860px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;">
        <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div style="display:grid;gap:1.5rem;">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Destination Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $destination->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                               value="{{ old('country', $destination->country) }}" required>
                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                              rows="4" required>{{ old('description', $destination->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude', $destination->latitude) }}" step="any">
                        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude', $destination->longitude) }}" step="any">
                        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
                           value="{{ old('tags', $destination->tags) }}" placeholder="beach, adventure, nature (comma-separated)">
                    @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Current Image --}}
                @if($destination->image_url)
                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    <div style="position:relative;display:inline-block;margin-bottom:.5rem;">
                        <img src="{{ $destination->image_url }}" style="width:100%;max-width:400px;height:200px;object-fit:cover;border-radius:10px;display:block;">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:.85rem;">Replace Image</label>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="form-control" style="padding:.5rem;">
                        <div id="newPreview" style="display:none;margin-top:.75rem;">
                            <img id="previewImg" style="width:100%;max-width:400px;height:200px;object-fit:cover;border-radius:10px;">
                            <p style="font-size:.8rem;color:var(--forest);margin-top:.3rem;">New image selected</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <label class="form-label">Destination Image</label>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="form-control" style="padding:.5rem;">
                    <div id="newPreview" style="display:none;margin-top:.75rem;">
                        <img id="previewImg" style="width:100%;max-width:400px;height:200px;object-fit:cover;border-radius:10px;">
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Approval Status</label>
                    <div style="display:flex;gap:1.5rem;">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="radio" name="is_approved" value="1" {{ old('is_approved', $destination->is_approved ? '1' : '0') === '1' ? 'checked' : '' }}>
                            <span>Approved</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="radio" name="is_approved" value="0" {{ old('is_approved', $destination->is_approved ? '1' : '0') === '0' ? 'checked' : '' }}>
                            <span>Pending</span>
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:1rem;padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const input = document.getElementById('imageInput');
const newPreview = document.getElementById('newPreview');
const previewImg = document.getElementById('previewImg');
if (input) {
    input.addEventListener('change', () => {
        if (input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { previewImg.src = e.target.result; newPreview.style.display = 'block'; };
            reader.readAsDataURL(input.files[0]);
        }
    });
}
</script>
@endsection
