@extends('layouts.app')
@section('content')
@push('styles')
<style>
    .auth-page { min-height:calc(100vh - 68px); display:flex; align-items:center; background:linear-gradient(160deg,var(--off-white) 60%,rgba(44,95,45,0.08) 100%); padding:3rem 1rem; }
    .auth-container { width:100%; max-width:460px; margin:0 auto; }
    .auth-card { background:white; border-radius:var(--radius-lg); padding:2.5rem; box-shadow:var(--shadow-lg); border:1px solid rgba(44,24,16,0.06); }
    .auth-logo { text-align:center; margin-bottom:2rem; }
    .auth-logo .brand-logo { width:52px; height:52px; font-size:1.5rem; margin:0 auto 0.75rem; }
    .auth-title { font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:600; color:var(--deep-earth); text-align:center; margin-bottom:0.375rem; }
    .auth-sub { text-align:center; color:var(--text-muted); font-size:0.875rem; margin-bottom:2rem; }
</style>
@endpush

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img class="brand-logo" src="{{ asset('doralogo.png') }}" alt="DORA logo">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-sub">Sign in to continue your journey</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" style="display:flex;justify-content:space-between;">
                        <span>Password</span>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.8rem;color:var(--forest-green);text-transform:none;font-weight:400;letter-spacing:0;">Forgot password?</a>
                        @endif
                    </label>
                    <input type="password" name="password" class="form-input" required autocomplete="current-password">
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--forest-green);">
                    <label for="remember" style="font-size:0.875rem;color:var(--text-muted);">Keep me signed in</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:0.875rem;">
                    Sign In →
                </button>
                <p style="text-align:center;margin-top:1.25rem;font-size:0.875rem;color:var(--text-muted);">
                    New to DORA? <a href="{{ route('register') }}" style="color:var(--forest-green);font-weight:600;">Create Account</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
