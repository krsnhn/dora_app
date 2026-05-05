@extends('layouts.app')

@section('title', 'Edit Tour Package')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <a href="{{ route('agency.packages.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:rgba(255,255,255,0.8);text-decoration:none;font-size:.9rem;margin-bottom:.75rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Back to Packages
        </a>
        <h1 class="page-title" style="color:white;">Edit Tour Package</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Update details for <strong>{{ $package->name }}</strong></p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;max-width:800px;">
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;">
        <form method="POST" action="{{ route('agency.packages.update', $package) }}" data-confirm="Save changes to this package?" data-confirm-title="Update Package" data-confirm-text="Save">
            @csrf @method('PATCH')

            <div style="display:grid;gap:1.5rem;">
                {{-- Package Name --}}
                <div class="form-group">
                    <label class="form-label">Package Name *</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name', $package->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Destination --}}
                <div class="form-group">
                    <label class="form-label">Destination *</label>
                    <select name="destination_id" class="form-input" required>
                        <option value="">Select a destination...</option>
                        @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>
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
                               value="{{ old('price', $package->price) }}" step="0.01" min="0" required>
                        @error('price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (days) *</label>
                        <input type="number" name="duration" class="form-input"
                               value="{{ old('duration', $package->duration) }}" min="1" required>
                        @error('duration')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-input" rows="4" required>{{ old('description', $package->description) }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Inclusions --}}
                <div class="form-group">
                    <label class="form-label">Inclusions</label>
                    <textarea name="inclusions" class="form-input" rows="3">{{ old('inclusions', $package->inclusions) }}</textarea>
                    <div style="font-size:.8rem;color:var(--text-muted);margin-top:.4rem;">List what's included in this package</div>
                    @error('inclusions')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="active" {{ old('status', $package->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $package->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div style="display:flex;gap:1rem;padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.4rem;"><path d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('agency.packages.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
