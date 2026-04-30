@extends('layouts.app')

@section('title', 'My Tour Packages')

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

    /* Table styling */
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Modal Styles */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        animation: fadeIn 0.2s ease-out;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-xl);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideIn 0.3s ease-out;
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--platinum-beige);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        margin: 0;
        color: var(--deep-earth);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: var(--platinum-beige);
        color: var(--text-dark);
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--platinum-beige);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Button styles */
    .btn-link {
        color: var(--primary);
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        font-size: inherit;
        font-family: inherit;
        padding: 0;
        transition: color 0.2s;
    }

    .btn-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Badge styles */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #dc2626; }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush

<div class="page-header">
    <div class="page-header-content">
        <h1>My <strong>Tour Packages</strong></h1>
        <p>Manage your travel offerings and track inquiries</p>
    </div>
</div>

<div class="container">
    <!-- Filters Section -->
    <div class="filter-container">
        <form action="{{ route('agency.packages.index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label>🔍 Search</label>
                <div class="search-input-wrapper">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" class="form-input"
                           placeholder="Package name, description, or destination..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="filter-group small">
                <label>📍 Destination</label>
                <select name="destination" class="form-input" onchange="this.form.submit()">
                    <option value="">All Destinations</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ request('destination') == $destination->id ? 'selected' : '' }}>
                            {{ $destination->name }} ({{ $destination->country }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group small">
                <label>📊 Status</label>
                <select name="status" class="form-input" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                @if(request()->hasAny(['search', 'destination', 'status', 'min_price', 'max_price']))
                    <a href="{{ route('agency.packages.index') }}" class="btn btn-secondary">Clear All</a>
                @endif
                <button type="button" class="btn btn-primary" onclick="openModal('createPackageModal')">+ New Package</button>
            </div>
        </form>
    </div>

    <!-- Create Package Modal -->
    <div id="createPackageModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New Package</h3>
                <button type="button" class="modal-close" onclick="closeModal('createPackageModal')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('agency.packages.store') }}" enctype="multipart/form-data" id="createPackageForm">
                @csrf
                <div class="modal-body">
                    <div id="formErrors" style="display:none;margin-bottom:1.5rem;"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Package Name *</label>
                            <input type="text" name="name" class="form-input"
                                   value="{{ old('name') }}" placeholder="e.g. Bali Adventure Tour" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Destination *</label>
                            <select name="destination_id" class="form-input" required>
                                <option value="">Select destination...</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }} ({{ $destination->country }})
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
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

                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-input"
                                  rows="3" placeholder="Describe what travelers can expect..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label class="form-label">Inclusions</label>
                        <textarea name="inclusions" class="form-input"
                                  rows="2" placeholder="e.g. Accommodation, Meals, Transportation, Tour guide...">{{ old('inclusions') }}</textarea>
                        <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">List what's included in this package</div>
                        @error('inclusions')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1.5rem;">
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

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createPackageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;"><path d="M5 13l4 4L19 7"/></svg>
                        Create Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Package Inquiries Modal -->
    <div id="packageInquiriesModal" class="modal" style="display:none;">
        <div class="modal-content" style="max-width:900px;">
            <div class="modal-header">
                <h3 id="inquiriesModalTitle">Package Inquiries</h3>
                <button type="button" class="modal-close" onclick="closeModal('packageInquiriesModal')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <!-- Inquiries Filter -->
                <div style="background:var(--platinum-beige);border-radius:8px;padding:1rem;margin-bottom:1.5rem;">
                    <form method="GET" id="inquiriesFilterForm" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
                        <input type="text" name="search" class="form-input" style="flex:2;min-width:200px;"
                               placeholder="Search by name or email..." value="">
                        <select name="status" class="form-input" style="width:auto;flex:1;min-width:150px;">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="contacted">Contacted</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="button" class="btn btn-secondary" onclick="clearInquiriesFilters()">Clear</button>
                    </form>
                </div>

                <!-- Inquiries List -->
                <div id="inquiriesList">
                    <div style="text-align:center;padding:3rem;">
                        <div style="width:60px;height:60px;background:var(--platinum-beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                            <svg width="28" height="28" fill="none" stroke="var(--text-dark)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <p style="color:var(--text-muted);">Select a package to view its inquiries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Meta -->
    <div class="results-meta">
        <div>
            📦 <strong>{{ number_format($packages->total()) }}</strong> packages found
        </div>
        @if(request('search'))
            <div>
                🔎 Results for: <strong>"{{ request('search') }}"</strong>
            </div>
        @endif
        @if(request('destination'))
            @php $dest = $destinations->find(request('destination')) @endphp
            <div>
                📍 Destination: <strong>{{ $dest ? $dest->name : 'Unknown' }}</strong>
            </div>
        @endif
        @if(request('status'))
            <div>
                📊 Status: <strong>{{ ucfirst(request('status')) }}</strong>
            </div>
        @endif
    </div>

    @if($packages->count())
    <div class="table-container">
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Inquiries</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                @if($package->destination->image_url)
                                <img src="{{ $package->destination->image_url }}" alt="{{ $package->name }}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                                @else
                                <div style="width:48px;height:48px;border-radius:8px;background:var(--platinum-beige);display:flex;align-items:center;justify-content:center;">
                                    <svg width="20" height="20" fill="none" stroke="var(--deep-earth)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;color:var(--deep-earth);">{{ $package->name }}</div>
                                    <div style="font-size:.8rem;color:var(--text-muted);">{{ Str::limit($package->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('destinations.show', $package->destination) }}" style="color:var(--primary);text-decoration:none;font-weight:500;">
                                {{ $package->destination->name }}
                            </a>
                            <div style="font-size:.8rem;color:var(--text-muted);">{{ $package->destination->country }}</div>
                        </td>
                        <td>
                            <span style="font-weight:700;color:var(--forest-green);">₱{{ number_format($package->price, 2) }}</span>
                        </td>
                        <td style="color:var(--text-muted);">{{ $package->duration }} days</td>
                        <td>
                            @if($package->status === 'active')
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-gray">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-link" onclick="viewPackageInquiries({{ $package->id }}, '{{ addslashes($package->name) }}')" style="font-weight:600;color:var(--primary);text-decoration:none;padding:0;border:none;background:none;cursor:pointer;">
                                {{ $package->inquiries()->count() }}
                            </button>
                        </td>
                        <td>
                            <div style="display:flex;gap:.5rem;">
                                <a href="{{ route('agency.packages.edit', $package) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('agency.packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:none;cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $packages->links('vendor.pagination.dora') }}
    </div>

    @else
    <div style="text-align:center;padding:5rem 1rem;background:white;border-radius:16px;box-shadow:var(--shadow-md);">
        <div style="width:80px;height:80px;background:var(--platinum-beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="36" height="36" fill="none" stroke="var(--deep-earth)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <h3 style="color:var(--deep-earth);margin-bottom:.5rem;">No packages yet</h3>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">Start attracting travelers by creating your first tour package.</p>
        <a href="{{ route('agency.packages.create') }}" class="btn btn-primary">Create Your First Package</a>
    </div>
    @endif
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Clear form errors when opening create modal
    if (modalId === 'createPackageModal') {
        document.getElementById('formErrors').style.display = 'none';
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form when closing create modal
    if (modalId === 'createPackageModal') {
        document.getElementById('createPackageForm').reset();
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('imageDropzone').style.display = 'block';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// Image upload handling for create package modal
const dropzone = document.getElementById('imageDropzone');
const input = document.getElementById('imageInput');
const preview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');

if (dropzone && input && preview && previewImg) {
    dropzone.addEventListener('click', () => input.click());
    dropzone.addEventListener('dragover', e => { 
        e.preventDefault(); 
        dropzone.style.borderColor='var(--forest)'; 
    });
    dropzone.addEventListener('dragleave', () => 
        dropzone.style.borderColor='var(--platinum-beige-dark)'
    );
    dropzone.addEventListener('drop', e => { 
        e.preventDefault(); 
        dropzone.style.borderColor='var(--platinum-beige-dark)'; 
        handleFile(e.dataTransfer.files[0]); 
    });
    input.addEventListener('change', () => handleFile(input.files[0]));
}

function handleFile(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { 
        previewImg.src = e.target.result; 
        preview.style.display='block'; 
        dropzone.style.display='none'; 
    };
    reader.readAsDataURL(file);
    const dt = new DataTransfer(); 
    dt.items.add(file); 
    input.files = dt.files;
}

function clearImage() {
    input.value = ''; 
    preview.style.display='none'; 
    dropzone.style.display='block';
}

// View package inquiries
function viewPackageInquiries(packageId, packageName) {
    document.getElementById('inquiriesModalTitle').textContent = `Inquiries for ${packageName}`;
    openModal('packageInquiriesModal');
    
    // Load inquiries via AJAX
    fetch(`/agency/inquiries?package_id=${packageId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderInquiries(data.inquiries, packageId);
    })
    .catch(error => {
        console.error('Error loading inquiries:', error);
        document.getElementById('inquiriesList').innerHTML = '<p style="text-align:center;color:var(--text-muted);">Error loading inquiries</p>';
    });
}

function renderInquiries(inquiries, packageId) {
    const container = document.getElementById('inquiriesList');
    
    if (!inquiries || inquiries.length === 0) {
        container.innerHTML = `
            <div style="text-align:center;padding:3rem;">
                <div style="width:60px;height:60px;background:var(--platinum-beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg width="28" height="28" fill="none" stroke="var(--text-dark)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p style="color:var(--text-muted);">No inquiries for this package yet</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = inquiries.map(inquiry => `
        <div style="background:white;border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem;margin-bottom:1rem;border-left:4px solid
            ${inquiry.status === 'confirmed' ? 'var(--forest-green)' : 
              inquiry.status === 'contacted' ? 'var(--ocean-blue)' : 
              inquiry.status === 'cancelled' ? '#dc2626' : 'var(--sunset-orange)'}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--forest-green);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;">
                            ${inquiry.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div style="font-weight:600;color:var(--text-dark);">${inquiry.name}</div>
                            <div style="font-size:.8rem;color:var(--text-muted);">${inquiry.email}</div>
                        </div>
                        <span class="badge badge-${inquiry.status === 'confirmed' ? 'success' : 
                                                inquiry.status === 'contacted' ? 'info' : 
                                                inquiry.status === 'cancelled' ? 'danger' : 'warning'}">
                            ${inquiry.status.charAt(0).toUpperCase() + inquiry.status.slice(1)}
                        </span>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.75rem;margin-bottom:.75rem;">
                        <div style="background:var(--platinum-beige);border-radius:6px;padding:.5rem .75rem;">
                            <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Group Size</div>
                            <div style="font-weight:600;color:var(--text-dark);font-size:.85rem;">${inquiry.pax} ${inquiry.pax == 1 ? 'person' : 'people'}</div>
                        </div>
                        <div style="background:var(--platinum-beige);border-radius:6px;padding:.5rem .75rem;">
                            <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Received</div>
                            <div style="font-weight:600;color:var(--text-dark);font-size:.85rem;">${new Date(inquiry.created_at).toLocaleDateString()}</div>
                        </div>
                    </div>

                    ${inquiry.message ? `<div style="background:#f8f9fa;border-radius:6px;padding:.75rem 1rem;font-size:.85rem;color:var(--text-muted);font-style:italic;">"${inquiry.message}"</div>` : ''}
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;min-width:120px;">
                    <a href="mailto:${inquiry.email}?subject=Re: ${packageName} Inquiry"
                       class="btn btn-primary btn-sm" style="text-align:center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.3rem;"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Reply
                    </a>

                    <form method="POST" action="/agency/inquiries/${inquiry.id}/status" style="margin:0;">
                        @csrf @method('PATCH')
                        <select name="status" class="form-input" style="font-size:.75rem;padding:.3rem .5rem;margin-bottom:.3rem;" onchange="this.form.submit()">
                            <option value="pending" ${inquiry.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="contacted" ${inquiry.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                            <option value="confirmed" ${inquiry.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="cancelled" ${inquiry.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    `).join('');
}

function clearInquiriesFilters() {
    document.querySelector('#inquiriesFilterForm input[name="search"]').value = '';
    document.querySelector('#inquiriesFilterForm select[name="status"]').value = '';
    document.getElementById('inquiriesFilterForm').submit();
}

// Handle inquiries filter form submission
document.getElementById('inquiriesFilterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const search = formData.get('search');
    const status = formData.get('status');
    
    // Get current package ID from modal title or store it
    const title = document.getElementById('inquiriesModalTitle').textContent;
    const packageId = window.currentPackageId;
    
    let url = `/agency/inquiries?package_id=${packageId}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${status}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderInquiries(data.inquiries, packageId);
    })
    .catch(error => {
        console.error('Error filtering inquiries:', error);
    });
});

// Handle create package form submission
document.getElementById('createPackageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;animation:spin 1s linear infinite;"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Creating...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and reload page
            closeModal('createPackageModal');
            location.reload();
        } else {
            // Handle validation errors
            const errorContainer = document.getElementById('formErrors');
            if (data.errors) {
                let errorHtml = '<div style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:1rem;color:#dc2626;font-size:.9rem;">';
                errorHtml += '<strong>Please fix the following errors:</strong><ul style="margin:0.5rem 0 0;padding-left:1.5rem;">';
                for (const [field, messages] of Object.entries(data.errors)) {
                    messages.forEach(message => {
                        errorHtml += `<li>${message}</li>`;
                    });
                }
                errorHtml += '</ul></div>';
                errorContainer.innerHTML = errorHtml;
                errorContainer.style.display = 'block';
            } else if (data.message) {
                errorContainer.innerHTML = `<div style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:1rem;color:#dc2626;font-size:.9rem;">${data.message}</div>`;
                errorContainer.style.display = 'block';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection
