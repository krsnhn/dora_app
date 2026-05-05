@extends('layouts.app')

@section('title', 'Add Destination')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <a href="{{ route('admin.destinations.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:rgba(255,255,255,0.8);text-decoration:none;font-size:.9rem;margin-bottom:.75rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Back to Destinations
        </a>
        <h1 class="page-title" style="color:white;">Add New Destination</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Create a new destination for the platform</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;max-width:860px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;">
        <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;gap:1.5rem;">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Destination Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="e.g. Palawan Island" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                               value="{{ old('country') }}" placeholder="e.g. Philippines" required>
                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                              rows="4" placeholder="Describe this destination..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude') }}" step="any" placeholder="e.g. 9.5057">
                        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude') }}" step="any" placeholder="e.g. 118.7350">
                        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tags</label>
                    <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
                           value="{{ old('tags') }}" placeholder="beach, adventure, nature, family, luxury (comma-separated)">
                    <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">Separate tags with commas</div>
                    @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Destination Image</label>
                    <div id="imageDropzone" style="border:2px dashed var(--beige);border-radius:12px;padding:2.5rem;text-align:center;cursor:pointer;transition:border-color .2s;">
                        <svg width="44" height="44" fill="none" stroke="var(--forest)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:.75rem;"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p style="color:var(--earth);font-weight:500;margin-bottom:.25rem;">Drop image here or click to browse</p>
                        <p style="color:var(--text-muted);font-size:.85rem;">JPG, PNG up to 10MB</p>
                        <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;">
                    </div>
                    <div id="imagePreview" style="display:none;margin-top:1rem;position:relative;">
                        <img id="previewImg" style="width:100%;max-height:240px;object-fit:cover;border-radius:10px;">
                        <button type="button" onclick="clearImage()" style="position:absolute;top:.5rem;right:.5rem;background:rgba(0,0,0,.5);color:white;border:none;border-radius:50%;width:30px;height:30px;cursor:pointer;font-size:1.2rem;">×</button>
                    </div>
                    @error('image')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Approval Status</label>
                    <div style="display:flex;gap:1rem;">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="radio" name="is_approved" value="1" {{ old('is_approved','1') === '1' ? 'checked' : '' }}>
                            <span style="font-size:.9rem;">Approved (visible to travelers)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="radio" name="is_approved" value="0" {{ old('is_approved') === '0' ? 'checked' : '' }}>
                            <span style="font-size:.9rem;">Pending review</span>
                        </label>
                    </div>
                </div>

                <div style="display:flex;gap:1rem;padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">Add Destination</button>
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const dropzone = document.getElementById('imageDropzone');
const input = document.getElementById('imageInput');
const preview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');

dropzone.addEventListener('click', () => input.click());
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor='var(--forest)'; });
dropzone.addEventListener('dragleave', () => dropzone.style.borderColor='var(--beige)');
dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.style.borderColor='var(--beige)'; handleFile(e.dataTransfer.files[0]); });
input.addEventListener('change', () => handleFile(input.files[0]));

function handleFile(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { previewImg.src = e.target.result; preview.style.display='block'; dropzone.style.display='none'; };
    reader.readAsDataURL(file);
}
function clearImage() {
    input.value = ''; preview.style.display='none'; dropzone.style.display='block';
}
</script>
@endsection
