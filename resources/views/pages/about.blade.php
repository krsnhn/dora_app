@extends('layouts.app')

@section('title', 'About DORA')

@section('content')
{{-- Hero --}}
<div style="background:var(--platinum-beige);padding:5rem 1rem;text-align:center;border-bottom:1px solid var(--platinum-beige-dark);">
    <div class="container">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:white;border:1px solid var(--platinum-beige-dark);border-radius:100px;padding:.4rem 1rem;margin-bottom:1.5rem;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--forest-green);"></span>
            <span style="color:var(--text-dark);font-size:.8rem;font-weight:600;letter-spacing:.5px;text-transform:uppercase;">Our Story</span>
        </div>
        <h1 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:clamp(2rem,5vw,3.25rem);margin-bottom:1rem;line-height:1.2;font-weight:600;">
            Connecting Travelers with<br>Authentic Local Experiences
        </h1>
        <p style="color:var(--text-muted);font-size:1.1rem;max-width:620px;margin:0 auto;line-height:1.7;">
            DORA bridges the gap between adventure seekers and local travel agencies, making authentic travel accessible to everyone.
        </p>
    </div>
</div>

{{-- Mission & Vision --}}
<div class="container" style="padding:5rem 1rem;">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;max-width:900px;margin:0 auto;">
        <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;border-top:3px solid var(--forest-green);">
            <div style="width:48px;height:48px;background:var(--platinum-beige);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                <svg width="24" height="24" fill="none" stroke="var(--forest-green)" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.5rem;margin-bottom:.75rem;">Our Mission</h2>
            <p style="color:var(--text-muted);line-height:1.8;margin:0;">To empower local travel agencies and give travelers the tools to discover authentic destinations, creating meaningful connections that fuel sustainable tourism across the Philippines and beyond.</p>
        </div>
        <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:2.5rem;border-top:3px solid var(--ocean-blue);">
            <div style="width:48px;height:48px;background:var(--platinum-beige);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                <svg width="24" height="24" fill="none" stroke="var(--ocean-blue)" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:1.5rem;margin-bottom:.75rem;">Our Vision</h2>
            <p style="color:var(--text-muted);line-height:1.8;margin:0;">A world where every traveler can easily connect with trusted local experts, and every local agency has equal opportunity to share the beauty of their region with the world.</p>
        </div>
    </div>
</div>

{{-- Values --}}
<div style="background:#fafaf6;padding:5rem 1rem;border-top:1px solid var(--platinum-beige);border-bottom:1px solid var(--platinum-beige);">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem;">
            <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:2.25rem;margin-bottom:.75rem;font-weight:600;">What We Stand For</h2>
            <p style="color:var(--text-muted);max-width:500px;margin:0 auto;">Our core values guide every decision we make and every feature we build.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;max-width:1000px;margin:0 auto;">
            @php
            $values = [
                ['icon'=>'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z','title'=>'Trust & Safety','desc'=>'Verified agencies and secure transactions give travelers peace of mind.'],
                ['icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Local First','desc'=>'We champion local agencies and help them compete in the digital age.'],
                ['icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','title'=>'Authentic Experiences','desc'=>'Real travel, real stories, real connections — not just tourist traps.'],
                ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','title'=>'Innovation','desc'=>'We continuously improve our tools to make travel planning effortless.'],
            ];
            @endphp
            @foreach($values as $val)
            <div style="background:white;border-radius:14px;padding:1.75rem;text-align:center;box-shadow:var(--shadow-sm);border:1px solid var(--platinum-beige);">
                <div style="width:48px;height:48px;border-radius:12px;background:var(--platinum-beige);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg width="22" height="22" fill="none" stroke="var(--forest-green)" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $val['icon'] }}"/></svg>
                </div>
                <h3 style="color:var(--deep-earth);font-size:1rem;margin-bottom:.5rem;font-weight:600;">{{ $val['title'] }}</h3>
                <p style="color:var(--text-muted);font-size:.88rem;line-height:1.6;margin:0;">{{ $val['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Stats --}}
<div style="background:white;padding:4rem 1rem;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:2rem;text-align:center;max-width:900px;margin:0 auto;">
            @php
            $stats = [
                ['number'=>'20+','label'=>'Destinations'],
                ['number'=>'50+','label'=>'Tour Packages'],
                ['number'=>'100+','label'=>'Happy Travelers'],
                ['number'=>'15+','label'=>'Local Agencies'],
            ];
            @endphp
            @foreach($stats as $s)
            <div style="padding:1.5rem 1rem;border-right:1px solid var(--platinum-beige);">
                <div style="font-size:2.75rem;font-weight:600;color:var(--forest-green);font-family:'Cormorant Garamond',serif;line-height:1;">{{ $s['number'] }}</div>
                <div style="color:var(--text-muted);font-size:.85rem;margin-top:.5rem;text-transform:uppercase;letter-spacing:1px;font-weight:500;">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- CTA --}}
<div style="background:var(--platinum-beige);padding:5rem 1rem;text-align:center;border-top:1px solid var(--platinum-beige-dark);">
    <div class="container">
        <h2 style="font-family:'Cormorant Garamond',serif;color:var(--deep-earth);font-size:2.25rem;margin-bottom:.75rem;font-weight:600;">Ready to Start Your Journey?</h2>
        <p style="color:var(--text-muted);max-width:500px;margin:0 auto 2rem;line-height:1.7;">Join thousands of travelers discovering the world through trusted local guides. Your next adventure is just a click away.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('destinations.index') }}" class="btn btn-primary">Explore Destinations</a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-secondary">Create Free Account</a>
            @endguest
        </div>
    </div>
</div>
@endsection