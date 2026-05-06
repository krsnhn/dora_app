@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', ($user->business_name ?: $user->name) . ' — Agency Profile')

@section('content')

@php
    $displayName = $user->business_name ?: $user->name;
    $initials    = strtoupper(substr($displayName, 0, 1));
    $colors      = ['#2d6a4f','#1d4e89','#6a2d5f','#7d4f00','#1a5276','#2c5364'];
    $bgColor     = $colors[crc32((string)$user->id) % count($colors)];
    $starFull    = floor($avgRating ?? 0);
    $starHalf    = ($avgRating - $starFull) >= 0.5;
@endphp

{{-- ── Profile Header ── --}}
<div style="background:var(--surface);border-bottom:1px solid var(--line);margin-bottom:2rem;">
    {{-- Cover --}}
    <div style="height:200px;background:linear-gradient(135deg,{{ $bgColor }},{{ $bgColor }}99);position:relative;"></div>

    <div class="container" style="padding-bottom:0;">
        <div style="display:flex;align-items:flex-end;gap:1.25rem;margin-top:-48px;flex-wrap:wrap;padding-bottom:1.25rem;">

            {{-- Avatar --}}
            <div style="width:96px;height:96px;border-radius:50%;background:{{ $bgColor }};border:4px solid var(--surface);
                        display:flex;align-items:center;justify-content:center;
                        font-size:2.5rem;font-weight:700;color:white;
                        box-shadow:0 4px 16px rgba(0,0,0,.22);flex-shrink:0;">
                {{ $initials }}
            </div>

            {{-- Name & meta --}}
            <div style="flex:1;min-width:200px;padding-top:52px;">
                <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                    <h1 style="margin:0;font-size:1.6rem;color:var(--text-dark);">{{ $displayName }}</h1>
                    <span class="badge badge-success">Verified Agency</span>
                </div>

                <div style="display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.5rem;color:var(--text-muted);font-size:.88rem;">
                    @if($user->address)
                        <span style="display:flex;align-items:center;gap:.3rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $user->address }}
                        </span>
                    @endif
                    @if($user->phone)
                        <span style="display:flex;align-items:center;gap:.3rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.3 19.79 19.79 0 0 1 1.61 4.7 2 2 0 0 1 3.59 2.5h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10a16 16 0 0 0 6.1 6.1l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $user->phone }}
                        </span>
                    @endif
                    @if($user->facebook_page)
                        <a href="{{ $user->facebook_page }}" target="_blank" rel="noopener noreferrer"
                           style="display:flex;align-items:center;gap:.3rem;color:var(--text-muted);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            Facebook Page
                        </a>
                    @endif
                </div>
            </div>

            {{-- Stats chips --}}
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-self:flex-end;padding-top:52px;">
                <div style="background:var(--surface-soft);border:1px solid var(--line);border-radius:10px;padding:.5rem .9rem;text-align:center;min-width:72px;">
                    <div style="font-size:1.2rem;font-weight:800;color:var(--text-dark);">{{ $packages->count() }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">{{ Str::plural('Package', $packages->count()) }}</div>
                </div>
                @if(($avgRating ?? 0) > 0)
                    <div style="background:var(--surface-soft);border:1px solid var(--line);border-radius:10px;padding:.5rem .9rem;text-align:center;min-width:72px;">
                        <div style="font-size:1.2rem;font-weight:800;color:#f59e0b;">{{ number_format($avgRating, 1) }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">Avg Rating</div>
                    </div>
                @endif
                <div style="background:var(--surface-soft);border:1px solid var(--line);border-radius:10px;padding:.5rem .9rem;text-align:center;min-width:72px;">
                    <div style="font-size:1.2rem;font-weight:800;color:var(--text-dark);">{{ $feedbacks->count() }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">{{ Str::plural('Review', $feedbacks->count()) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Back link + flash ── --}}
<div class="container">
    <a href="{{ route('agencies.index') }}"
       style="display:inline-flex;align-items:center;gap:.4rem;color:var(--text-muted);font-size:.88rem;text-decoration:none;margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Agencies
    </a>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr minmax(0,340px);gap:2rem;align-items:start;" class="agency-show-grid">

        {{-- ── LEFT: Packages ── --}}
        <div>
            <h2 style="font-size:1.2rem;font-weight:700;color:var(--text-dark);margin:0 0 1rem;">
                Tour Packages
                <span style="font-size:.85rem;font-weight:400;color:var(--text-muted);">({{ $packages->count() }})</span>
            </h2>

            @forelse($packages as $package)
                @php
                    $img = $package->display_image;
                @endphp
                <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;overflow:hidden;margin-bottom:1.25rem;box-shadow:var(--shadow-sm);">

                    {{-- Package image or placeholder --}}
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $package->name }}"
                             style="width:100%;height:180px;object-fit:cover;display:block;">
                    @else
                        <div style="width:100%;height:100px;background:linear-gradient(135deg,{{ $bgColor }}33,{{ $bgColor }}11);
                                    display:flex;align-items:center;justify-content:center;color:{{ $bgColor }};font-size:2rem;">
                            ✈
                        </div>
                    @endif

                    <div style="padding:1.25rem;">
                        <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:flex-start;margin-bottom:.6rem;">
                            <div>
                                <h3 style="margin:0 0 .25rem;font-size:1.1rem;color:var(--text-dark);">{{ $package->name }}</h3>
                                @if($package->destination)
                                    <span class="badge badge-earth">{{ $package->destination->name }}</span>
                                @endif
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <div style="font-size:1.2rem;font-weight:800;color:var(--text-dark);">PHP {{ number_format($package->price, 2) }}</div>
                                <div style="font-size:.8rem;color:var(--text-muted);">{{ $package->duration }} {{ Str::plural('day', (int) $package->duration) }}</div>
                            </div>
                        </div>

                        @if($package->description)
                            <p style="margin:.5rem 0 .75rem;color:var(--text-muted);font-size:.9rem;line-height:1.55;">{{ Str::limit($package->description, 220) }}</p>
                        @endif

                        @if($package->inclusions)
                            <div style="font-size:.82rem;color:var(--text-muted);line-height:1.55;margin-bottom:.75rem;padding:.6rem .8rem;border-radius:8px;background:var(--surface-soft);">
                                <strong style="color:var(--deep-earth);">Inclusions:</strong> {{ $package->inclusions }}
                            </div>
                        @endif

                        {{-- Inquire accordion --}}
                        <details>
                            <summary class="btn btn-primary btn-sm" style="list-style:none;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Inquire Now
                            </summary>

                            <form method="POST" action="{{ route('inquiries.store', $package) }}"
                                  style="margin-top:1rem;padding:1rem;border:1px solid var(--line);border-radius:12px;background:var(--surface-soft);">
                                @csrf
                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.85rem;">
                                    <div class="form-group">
                                        <label class="form-label">Your Name *</label>
                                        <input type="text" name="contact_name" class="form-input"
                                               value="{{ auth()->user()->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email *</label>
                                        <input type="email" name="contact_email" class="form-input"
                                               value="{{ auth()->user()->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone *</label>
                                        <input type="tel" name="contact_phone" class="form-input"
                                               placeholder="+63 9XX XXX XXXX" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Pax *</label>
                                        <input type="number" name="pax" class="form-input"
                                               value="1" min="1" max="100" required>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:.85rem;">
                                    <label class="form-label">Preferred Travel Date *</label>
                                    <input type="date" name="travel_date" class="form-input"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                                <div class="form-group" style="margin-top:.85rem;">
                                    <label class="form-label">Message *</label>
                                    <textarea name="message" class="form-input" rows="3"
                                              placeholder="Tell the agency about your plans or questions." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-top:.5rem;">Send Inquiry</button>
                            </form>
                        </details>
                    </div>
                </div>
            @empty
                <div style="background:var(--surface);border:1px dashed var(--line);border-radius:16px;padding:2.5rem;text-align:center;color:var(--text-muted);">
                    This agency has no public packages available right now. Check back later.
                </div>
            @endforelse
        </div>

        {{-- ── RIGHT: Reviews sidebar ── --}}
        <aside>
            {{-- Contact card --}}
            <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.25rem;margin-bottom:1.25rem;box-shadow:var(--shadow-sm);">
                <h3 style="margin:0 0 .9rem;font-size:1rem;color:var(--text-dark);">Contact Info</h3>
                <div style="display:flex;flex-direction:column;gap:.6rem;font-size:.88rem;color:var(--text-muted);">
                    <span style="display:flex;align-items:center;gap:.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:{{ $user->email }}" style="color:var(--text-muted);">{{ $user->email }}</a>
                    </span>
                    @if($user->phone)
                        <span style="display:flex;align-items:center;gap:.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.3 19.79 19.79 0 0 1 1.61 4.7 2 2 0 0 1 3.59 2.5h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10a16 16 0 0 0 6.1 6.1l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $user->phone }}
                        </span>
                    @endif
                    @if($user->address)
                        <span style="display:flex;align-items:flex-start;gap:.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $user->address }}
                        </span>
                    @endif
                    @if($user->facebook_page)
                        <span style="display:flex;align-items:center;gap:.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            <a href="{{ $user->facebook_page }}" target="_blank" rel="noopener noreferrer" style="color:var(--text-muted);">Facebook Page</a>
                        </span>
                    @endif
                </div>
            </div>

            {{-- Reviews --}}
            @if($feedbacks->count())
                <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.25rem;box-shadow:var(--shadow-sm);">
                    <h3 style="margin:0 0 .9rem;font-size:1rem;color:var(--text-dark);">
                        Traveler Reviews
                        @if(($avgRating ?? 0) > 0)
                            <span style="color:#f59e0b;font-size:.95rem;margin-left:.35rem;">
                                ★ {{ number_format($avgRating, 1) }}
                            </span>
                        @endif
                    </h3>

                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        @foreach($feedbacks as $fb)
                            <div style="padding-bottom:.85rem;border-bottom:1px solid var(--line);">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.25rem;">
                                    <strong style="font-size:.88rem;color:var(--text-dark);">{{ $fb->user->name }}</strong>
                                    <span style="color:#f59e0b;font-size:.88rem;">
                                        {{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}
                                    </span>
                                </div>
                                @if($fb->comment)
                                    <p style="margin:0 0 .2rem;font-size:.83rem;color:var(--text-muted);line-height:1.5;">{{ $fb->comment }}</p>
                                @endif
                                <span style="font-size:.75rem;color:#aaa;">{{ $fb->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.25rem;text-align:center;color:var(--text-muted);font-size:.88rem;box-shadow:var(--shadow-sm);">
                    No reviews yet.
                </div>
            @endif
        </aside>

    </div>
</div>

<style>
@media(max-width:768px){
    .agency-show-grid{
        grid-template-columns:1fr !important;
    }
}
</style>
@endsection
