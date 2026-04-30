@extends('layouts.app')

@section('title', 'Create Tour Package')

@section('content')
<div class="page-header">
    <div class="container">
        <a href="{{ route('agency.packages.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--text-muted);text-decoration:none;font-size:.9rem;margin-bottom:.75rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Back to Packages
        </a>
        <h1 class="page-title">Create Tour Package</h1>
        <p class="page-subtitle">Add a new travel offering for your agency</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;max-width:800px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;">
        <form method="POST" action="{{ route('agency.packages.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;gap:1.5rem;">
                {{-- Package Name --}}
                <div class="form-group">
                    <label class="form-label">Package Name *</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name') }}" placeholder="e.g. Island Hopping Adventure" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Destination --}}
                <div class="form-group">
                    <label class="form-label">Destination *</label>
                    <select name="destination_id" class="form-input" required>
                        <option value="">Select a destination...</option>
                        @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                            {{ $destination->name }}, {{ $destination->country }}
                        </option>
                        @endforeach
                    </select>
                    @error('destination_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Price & Duration --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Price (₱) *</label>
                        <input type="number" name="price" class="form-input"
                               value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" required>
                        @error('price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (days) *</label>
                        <input type="number" name="duration" class="form-input"
                               value="{{ old('duration') }}" placeholder="3" min="1" required>
                        @error('duration')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-input"
                              rows="4" placeholder="Describe what travelers can expect..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Inclusions --}}
                <div class="form-group">
                    <label class="form-label">Inclusions</label>
                    <textarea name="inclusions" class="form-input"
                              rows="3" placeholder="e.g. Accommodation, Meals, Transportation, Tour guide...">{{ old('inclusions') }}</textarea>
                    <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">List what's included in this package</div>
                    @error('inclusions')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Package Image --}}
                <div class="form-group">
                    <label class="form-label">Package Image</label>
                    <div id="imageDropzone" style="border:2px dashed var(--platinum-beige-dark);border-radius:var(--radius-sm);padding:2rem;text-align:center;cursor:pointer;transition:all .2s;background:white;">
                        <svg width="40" height="40" fill="none" stroke="var(--forest-green)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:.75rem;"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p style="color:var(--text-dark);font-weight:500;margin-bottom:.25rem;">Drop image here or click to browse</p>
                        <p style="color:var(--text-muted);font-size:.85rem;">JPG, PNG up to 5MB</p>
                        <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;">
                    </div>
                    <div id="imagePreview" style="display:none;margin-top:1rem;position:relative;">
                        <img id="previewImg" style="width:100%;max-height:200px;object-fit:cover;border-radius:var(--radius-sm);" />
                        <button type="button" onclick="clearImage()" style="position:absolute;top:.5rem;right:.5rem;background:rgba(0,0,0,.5);color:white;border:none;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:1.1rem;line-height:1;">×</button>
                    </div>
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div style="display:flex;gap:1rem;padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;"><path d="M5 13l4 4L19 7"/></svg>
                        Create Package
                    </button>
                    <a href="{{ route('agency.packages.index') }}" class="btn btn-secondary">Cancel</a>
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
    const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
}

function clearImage() {
    input.value = ''; preview.style.display='none'; dropzone.style.display='block';
}
</script>
@endsection
