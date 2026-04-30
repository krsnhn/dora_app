@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem;background:linear-gradient(135deg,var(--beige) 0%,white 100%);">
    <div style="width:100%;max-width:420px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:64px;height:64px;background:linear-gradient(135deg,var(--forest),var(--ocean));border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <svg width="30" height="30" fill="none" stroke="white" stroke-width="1.5" viewBox="0 0 24 24"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <h1 style="color:var(--earth);font-size:1.8rem;margin:0 0 .4rem;">Forgot Password?</h1>
            <p style="color:var(--text-muted);font-size:.9rem;margin:0;">No worries — we'll send you reset instructions.</p>
        </div>

        <div style="background:white;border-radius:20px;box-shadow:var(--shadow-lg);padding:2.5rem;">
            @if (session('status'))
            <div style="background:#dcfce7;border-radius:10px;padding:1rem;margin-bottom:1.5rem;color:var(--forest);font-size:.9rem;display:flex;gap:.5rem;align-items:flex-start;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:.1rem;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Send Reset Link</button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ route('login') }}" style="color:var(--ocean);text-decoration:none;font-size:.9rem;">
                    ← Back to login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
