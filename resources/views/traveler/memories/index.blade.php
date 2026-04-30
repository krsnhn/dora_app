@extends('layouts.app')

@section('title', 'My Travel Memories')

@section('content')
@push('styles')
<style>
    /* Reset and Base */
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .memories-header {
        background: linear-gradient(135deg, var(--deep-earth) 0%, var(--forest-green) 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
        color: white;
    }
    
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--deep-earth);
        font-size: 0.875rem;
    }

    .form-input, .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid var(--platinum-beige-dark);
        border-radius: var(--radius-sm);
        font-family: 'Jost', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: white;
    }

    .form-input:focus, .form-control:focus {
        outline: none;
        border-color: var(--forest-green);
    }

    .form-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    /* Filter Container */
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
    
    /* Memories Grid */
    .memories-grid {
        columns: 3;
        column-gap: 1.5rem;
    }
    
    .memory-card {
        break-inside: avoid;
        margin-bottom: 1.5rem;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(44, 24, 16, 0.08);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .memory-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }
    
    .memory-image {
        width: 100%;
        cursor: pointer;
        transition: transform 0.3s ease;
        display: block;
    }
    
    .memory-content {
        padding: 1rem 1.25rem 1.25rem;
    }
    
    .memory-caption {
        color: var(--deep-earth);
        font-size: 0.9rem;
        margin: 0 0 0.5rem;
        font-weight: 500;
        line-height: 1.4;
    }
    
    .memory-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
        color: var(--text-muted);
    }
    
    .memory-location {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .delete-memory-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.25rem;
        transition: color 0.2s;
        border-radius: 4px;
    }
    
    .delete-memory-btn:hover {
        color: #ef4444;
    }
    
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-container {
        background: white;
        border-radius: var(--radius-lg);
        max-width: 560px;
        width: 100%;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(44,24,16,0.06);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--platinum-beige-dark);
    }

    .modal-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.75rem;
        color: var(--text-muted);
        transition: color 0.2s;
        line-height: 1;
    }

    .modal-close:hover {
        color: var(--deep-earth);
    }

    .modal-body {
        padding: 1.75rem;
    }

    /* Dropzone */
    .dropzone-area {
        border: 2px dashed var(--platinum-beige-dark);
        border-radius: var(--radius-sm);
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: var(--off-white);
    }

    .dropzone-area:hover {
        border-color: var(--forest-green);
        background: rgba(44,95,45,0.02);
    }

    .dropzone-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 0.75rem;
        color: var(--forest-green);
    }

    .dropzone-title {
        color: var(--deep-earth);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .dropzone-hint {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .image-preview {
        display: none;
        margin-top: 1rem;
    }

    .image-preview img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: var(--radius-sm);
    }

    /* Form Row */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* Empty State */
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
    
    .empty-title {
        color: var(--deep-earth);
        margin-bottom: 0.75rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
    }
    
    .empty-text {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    /* Lightbox */
    .lightbox-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
        cursor: pointer;
    }
    
    .lightbox-image {
        max-width: 90vw;
        max-height: 85vh;
        border-radius: 8px;
        object-fit: contain;
    }
    
    .lightbox-caption {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        text-align: center;
        max-width: 80vw;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        padding: 1rem 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .memories-grid {
            columns: 2;
            column-gap: 1.25rem;
        }
    }
    
    @media (max-width: 768px) {
        .memories-grid {
            columns: 2;
            column-gap: 1rem;
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
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .page-title {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .memories-grid {
            columns: 1;
        }
        
        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-header {
            padding: 1.25rem;
        }
    }
</style>
@endpush

<div class="memories-header">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 class="page-title">My Travel Memories</h1>
                <p class="page-subtitle">Your personal travel photo album</p>
            </div>
            <button onclick="openUploadModal()" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:0.5rem;">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add Memory
            </button>
        </div>
    </div>
</div>

<div class="container">
    <!-- Filters Section -->
    <div class="filter-container">
        <form action="{{ route('memories.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label>🔍 Search Memories</label>
                <div class="search-input-wrapper">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" class="form-input"
                           placeholder="Search by caption, destination, or tags..."
                           value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="filter-group small">
                <label>📅 Year</label>
                <select name="year" class="form-control" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @foreach($years ?? [] as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                @if(request()->hasAny(['search', 'year']))
                    <a href="{{ route('memories.index') }}" class="btn btn-secondary">Clear All</a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Results Meta -->
    @if($memories->count())
    <div class="results-meta">
        <div>
            📸 <strong>{{ $memories->total() ?? $memories->count() }}</strong> memories found
        </div>
        @if(request('search'))
            <div>
                🔎 Results for: <strong>"{{ request('search') }}"</strong>
            </div>
        @endif
        @if(request('year'))
            <div>
                📅 Year: <strong>{{ request('year') }}</strong>
            </div>
        @endif
    </div>
    @endif

    <!-- Memories Grid -->
    @if($memories->count())
    <div class="memories-grid" id="memoriesGrid">
        @foreach($memories as $memory)
        <div class="memory-card" data-memory-id="{{ $memory->id }}">
            @if($memory->image_path)
                <img src="{{ $memory->image_path }}" 
                     alt="{{ $memory->caption }}" 
                     class="memory-image" 
                     onclick="openLightbox('{{ $memory->image_path }}', '{{ addslashes($memory->caption) }}')">
            @endif
            <div class="memory-content">
                @if($memory->caption)
                    <p class="memory-caption">{{ $memory->caption }}</p>
                @endif
                <div class="memory-meta">
                    <div class="memory-location">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        @if($memory->destination)
                            {{ $memory->destination->name }}
                        @else
                            Unknown Location
                        @endif
                        @if($memory->travel_date)
                            · {{ \Carbon\Carbon::parse($memory->travel_date)->format('M Y') }}
                        @endif
                    </div>
                    <form method="POST" action="{{ route('memories.destroy', $memory) }}" onsubmit="return confirm('Delete this memory?')" style="margin:0;">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="delete-memory-btn" title="Delete memory">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if(method_exists($memories, 'links'))
    <div class="pagination-wrapper">
        {{ $memories->appends(request()->query())->links() }}
    </div>
    @endif

    @else
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="48" height="48" fill="none" stroke="var(--deep-earth)" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="empty-title">
            @if(request()->hasAny(['search', 'year']))
                No memories found
            @else
                No memories yet
            @endif
        </h2>
        <p class="empty-text">
            @if(request()->hasAny(['search', 'year']))
                Try adjusting your search or clearing the filters
            @else
                Start uploading photos from your travels to create a beautiful personal album.
            @endif
        </p>
        @if(request()->hasAny(['search', 'year']))
            <a href="{{ route('memories.index') }}" class="btn btn-primary">Clear Filters</a>
        @else
            <button onclick="openUploadModal()" class="btn btn-primary">Upload Your First Memory</button>
        @endif
    </div>
    @endif
</div>

{{-- Upload Modal --}}
<div id="uploadModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Add Travel Memory</h3>
            <button onclick="closeUploadModal()" class="modal-close">&times;</button>
        </div>
        
        <form method="POST" action="{{ route('memories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Photo *</label>
                    <div id="memoryDropzone" class="dropzone-area">
                        <div class="dropzone-icon">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="dropzone-title">Click to upload</p>
                        <p class="dropzone-hint">JPG, PNG up to 10MB</p>
                        <input type="file" name="image" id="memoryInput" accept="image/*" required style="display:none;">
                    </div>
                    <div id="memoryPreview" class="image-preview">
                        <img id="memoryPreviewImg" alt="Preview">
                    </div>
                    @error('image')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Caption</label>
                    <input type="text" name="caption" class="form-input" placeholder="Describe this moment..." value="{{ old('caption') }}">
                    @error('caption')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Destination</label>
                        <select name="destination_id" class="form-control">
                            <option value="">Select destination</option>
                            @foreach($destinations ?? [] as $d)
                                <option value="{{ $d->id }}" {{ old('destination_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}, {{ $d->country }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Travel Date</label>
                        <input type="date" name="travel_date" class="form-input" value="{{ old('travel_date') }}">
                        @error('travel_date')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" style="flex:1; justify-content: center;">
                        Upload Memory
                    </button>
                    <button type="button" onclick="closeUploadModal()" class="btn btn-secondary" style="flex:1; justify-content: center;">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="lightbox-overlay" onclick="closeLightbox()">
    <img id="lightboxImg" class="lightbox-image">
    <p id="lightboxCaption" class="lightbox-caption"></p>
</div>

@push('scripts')
<script>
// Upload modal functions
function openUploadModal() {
    document.getElementById('uploadModal').style.display = 'flex';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    resetUploadForm();
}

function resetUploadForm() {
    document.getElementById('memoryInput').value = '';
    document.getElementById('memoryPreview').style.display = 'none';
    document.getElementById('memoryDropzone').style.display = 'block';
}

// Dropzone functionality
const memoryDropzone = document.getElementById('memoryDropzone');
const memoryInput = document.getElementById('memoryInput');
const memoryPreview = document.getElementById('memoryPreview');
const memoryPreviewImg = document.getElementById('memoryPreviewImg');

if (memoryDropzone) {
    memoryDropzone.addEventListener('click', () => memoryInput.click());
}

if (memoryInput) {
    memoryInput.addEventListener('change', function() {
        if (memoryInput.files && memoryInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                memoryPreviewImg.src = e.target.result;
                memoryPreview.style.display = 'block';
                memoryDropzone.style.display = 'none';
            };
            reader.readAsDataURL(memoryInput.files[0]);
        }
    });
}

// Close modal when clicking outside
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) closeUploadModal();
});

// Lightbox functions
function openLightbox(url, caption) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    
    lightboxImg.src = url;
    lightboxCaption.textContent = caption || 'Travel Memory';
    lightbox.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
        closeLightbox();
    }
});

// Responsive columns
function updateColumns() {
    const grid = document.getElementById('memoriesGrid');
    if (!grid) return;
    
    if (window.innerWidth < 480) {
        grid.style.columns = '1';
    } else if (window.innerWidth < 768) {
        grid.style.columns = '2';
    } else {
        grid.style.columns = '3';
    }
}

window.addEventListener('resize', updateColumns);
updateColumns();
</script>
@endpush
@endsection