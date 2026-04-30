@extends('layouts.app')
@section('content')
@push('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 68px);
        display: flex;
        align-items: flex-start;
        background: linear-gradient(160deg, var(--off-white) 60%, rgba(44,95,45,0.08) 100%);
        padding: 3rem 1rem;
    }
    .auth-container { width: 100%; max-width: 640px; margin: 0 auto; }
    .auth-card { background: white; border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-lg); border: 1px solid rgba(44,24,16,0.06); }
    .auth-logo { text-align: center; margin-bottom: 2rem; }
    .auth-logo .brand-logo { width: 52px; height: 52px; font-size: 1.5rem; margin: 0 auto 0.75rem; }
    .auth-title { font-family: 'Cormorant Garamond', serif; font-size: 2rem; font-weight: 600; color: var(--deep-earth); text-align: center; margin-bottom: 0.375rem; }
    .auth-sub { text-align: center; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem; }
    .role-tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 2rem; }
    .role-tab {
        border: 2px solid var(--platinum-beige-dark);
        border-radius: var(--radius-sm);
        padding: 1.25rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: none;
        font-family: 'Jost', sans-serif;
    }
    .role-tab.active { border-color: var(--forest-green); background: rgba(44,95,45,0.05); }
    .role-tab-icon { font-size: 1.75rem; margin-bottom: 0.5rem; }
    .role-tab-name { font-weight: 600; font-size: 0.9rem; color: var(--deep-earth); }
    .role-tab-desc { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .terms-check { display: flex; align-items: flex-start; gap: 0.75rem; }
    .terms-check input { margin-top: 0.2rem; accent-color: var(--forest-green); }
    .terms-check label { font-size: 0.825rem; color: var(--text-muted); line-height: 1.5; }
    .terms-check a { color: var(--forest-green); }
    .agency-fields { display: none; }
    .agency-fields.visible { display: block; }
    @media(max-width:480px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endpush

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <div class="brand-logo">D</div>
                <h1 class="auth-title">Create Your Account</h1>
                <p class="auth-sub">Join DORA and start your travel journey</p>
            </div>

            <!-- Role selection -->
            <div class="role-tabs">
                <button type="button" class="role-tab active" id="tabTraveler" onclick="selectRole('traveler')">
                    <div class="role-tab-icon">🧳</div>
                    <div class="role-tab-name">Traveler</div>
                    <div class="role-tab-desc">Explore destinations & book tours</div>
                </button>
                <button type="button" class="role-tab" id="tabAgency" onclick="selectRole('agency')">
                    <div class="role-tab-icon">🏢</div>
                    <div class="role-tab-name">Travel Agency</div>
                    <div class="role-tab-desc">List packages & grow your business</div>
                </button>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="role" id="roleInput" value="traveler">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                        @error('name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                        @error('email')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <!-- Agency-only fields -->
                <div class="agency-fields" id="agencyFields">
                    <div style="border-top: 1px solid var(--platinum-beige-dark); margin: 1.25rem 0 1.5rem; padding-top: 1.5rem;">
                        <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:var(--deep-earth);margin-bottom:1.25rem;">Agency Details</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Business Name *</label>
                        <input type="text" name="business_name" class="form-input" value="{{ old('business_name') }}">
                        @error('business_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
                            @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Facebook Page</label>
                            <input type="url" name="facebook_page" class="form-input" value="{{ old('facebook_page') }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Business Address *</label>
                        <textarea name="address" class="form-input" rows="2" placeholder="Complete business address">{{ old('address') }}</textarea>
                        @error('address')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Valid ID / Business Permit *</label>
                        <input type="file" name="valid_id" class="form-input" accept=".jpg,.jpeg,.png,.pdf">
                        <span style="font-size:0.75rem;color:var(--text-muted);margin-top:0.25rem;display:block;">Upload a valid government ID or business permit (JPG, PNG, PDF · Max 5MB)</span>
                        @error('valid_id')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div style="background:rgba(30,74,109,0.06);border:1px solid rgba(30,74,109,0.15);border-radius:8px;padding:0.875rem;margin-bottom:1.25rem;">
                        <p style="font-size:0.8rem;color:var(--ocean-blue);line-height:1.5;">
                            ℹ️ Your agency account will be reviewed by our admin team after registration. You'll be able to start listing packages once approved.
                        </p>
                    </div>
                </div>

                <div class="form-group terms-check">
                    <input type="checkbox" name="terms" id="terms" value="1" required {{ old('terms') ? 'checked' : '' }}>
                    <label for="terms">
                        I agree to DORA's <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>. I understand how my data will be used.
                    </label>
                </div>
                @error('terms')<span class="form-error" style="display:block;margin-top:-0.75rem;margin-bottom:1rem;">{{ $message }}</span>@enderror

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:0.875rem;">
                    Create Account →
                </button>

                <p style="text-align:center;margin-top:1.25rem;font-size:0.875rem;color:var(--text-muted);">
                    Already have an account? <a href="{{ route('login') }}" style="color:var(--forest-green);font-weight:600;">Sign In</a>
                </p>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectRole(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('tabTraveler').classList.toggle('active', role === 'traveler');
    document.getElementById('tabAgency').classList.toggle('active', role === 'agency');
    document.getElementById('agencyFields').classList.toggle('visible', role === 'agency');
}

// Restore role from old input
@if(old('role') === 'agency') selectRole('agency'); @endif
</script>
@endpush
@endsection
