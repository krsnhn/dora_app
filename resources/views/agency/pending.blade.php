@extends('layouts.app')
@section('content')
<div style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem;">
    <div style="background:white;border-radius:var(--radius-lg);padding:3rem;max-width:560px;width:100%;text-align:center;box-shadow:var(--shadow-lg);">
        <div style="font-size:4rem;margin-bottom:1.5rem;">⏳</div>
        <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.25rem;font-weight:600;color:var(--deep-earth);margin-bottom:1rem;">Account Under Review</h1>

        @if(auth()->user()->agency_status === 'rejected')
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:1.25rem;margin-bottom:1.5rem;text-align:left;">
            <p style="color:#991b1b;font-weight:600;margin-bottom:0.5rem;">Your application was not approved.</p>
            @if(auth()->user()->verification_notes)
            <p style="color:#991b1b;font-size:0.875rem;">Reason: {{ auth()->user()->verification_notes }}</p>
            @endif
        </div>
        <p style="color:var(--text-muted);line-height:1.7;margin-bottom:2rem;">Please contact support if you believe this was in error or would like to resubmit your application.</p>
        @else
        <p style="color:var(--text-muted);line-height:1.7;margin-bottom:2rem;">Thank you for registering as a travel agency on DORA! Your account is currently being reviewed by our admin team. This process typically takes 1-2 business days.</p>
        <div style="display:flex;flex-direction:column;gap:0.75rem;text-align:left;background:var(--off-white);border-radius:var(--radius-sm);padding:1.25rem;margin-bottom:2rem;">
            <div style="display:flex;gap:0.75rem;font-size:0.875rem;">
                <span style="color:var(--forest-green);">✅</span>
                <span>Registration submitted</span>
            </div>
            <div style="display:flex;gap:0.75rem;font-size:0.875rem;color:var(--text-muted);">
                <span>⏳</span>
                <span>Admin verification (pending)</span>
            </div>
            <div style="display:flex;gap:0.75rem;font-size:0.875rem;color:var(--text-muted);">
                <span>🎯</span>
                <span>Start listing tour packages</span>
            </div>
        </div>
        @endif

        <a href="{{ route('destinations.index') }}" class="btn btn-outline">Browse Destinations</a>
    </div>
</div>
@endsection
