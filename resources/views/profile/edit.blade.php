@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Edit Profile</h1>
        <p class="page-subtitle">Update your account information</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;max-width:700px;">

    {{-- Success Message --}}
    @if(session('success'))
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;border-left:3px solid #10b981;color:#065f46;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:.9rem;">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Profile Info --}}
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2rem;margin-bottom:1.5rem;">
        <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.4rem;margin-bottom:1.5rem;padding-bottom:.75rem;border-bottom:1px solid var(--platinum-beige);font-weight:600;">Profile Information</h3>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PATCH')
            <div style="display:grid;gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-input"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                @if($user->role === 'agency')
                <div style="padding-top:1rem;border-top:1px solid var(--platinum-beige);">
                    <p style="font-size:.75rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:1rem;">Agency Information</p>
                    <div style="display:grid;gap:1.25rem;">
                        <div class="form-group">
                            <label class="form-label">Business Name</label>
                            <input type="text" name="business_name" class="form-input" value="{{ old('business_name', $user->business_name) }}">
                            @error('business_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-input" value="{{ old('address', $user->address) }}">
                            @error('address')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Facebook Page URL</label>
                            <input type="url" name="facebook_page" class="form-input" value="{{ old('facebook_page', $user->facebook_page) }}" placeholder="https://facebook.com/yourpage">
                            @error('facebook_page')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                @endif

                <div style="display:flex;gap:1rem;padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2rem;margin-bottom:1.5rem;">
        <h3 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.4rem;margin-bottom:1.5rem;padding-bottom:.75rem;border-bottom:1px solid var(--platinum-beige);font-weight:600;">Change Password</h3>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('PUT')
            <div style="display:grid;gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Current Password *</label>
                    <input type="password" name="current_password" class="form-input" required>
                    @error('current_password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">New Password *</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="Minimum 8 characters" required>
                    @error('password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password *</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <div style="padding-top:.5rem;">
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2rem;border-top:3px solid #dc2626;">
        <h3 style="font-family:'Cormorant Garamond',serif;color:#dc2626;font-size:1.4rem;margin-bottom:.5rem;font-weight:600;">Delete Account</h3>
        <p style="color:var(--text-muted);font-size:.9rem;margin-bottom:1.25rem;line-height:1.6;">Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.</p>
        <button onclick="document.getElementById('deleteModal').style.display='flex'" class="btn" style="background:#fee2e2;color:#dc2626;border:1.5px solid #fecaca;cursor:pointer;font-weight:600;">
            Delete Account
        </button>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(44,24,16,.6);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div style="background:white;border-radius:16px;padding:2rem;max-width:440px;width:90%;box-shadow:var(--shadow-lg);">
        <h3 style="font-family:'Cormorant Garamond',serif;color:#dc2626;margin-bottom:.75rem;font-size:1.5rem;font-weight:600;">Delete Account</h3>
        <p style="color:var(--text-muted);font-size:.9rem;margin-bottom:1.5rem;line-height:1.6;">Are you sure you want to delete your account? This action cannot be undone.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('DELETE')
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label">Enter your password to confirm</label>
                <input type="password" name="password" class="form-input" required autofocus>
                @error('password', 'userDeletion')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn" style="background:#dc2626;color:white;border:none;cursor:pointer;font-weight:600;">Delete Account</button>
                <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endsection