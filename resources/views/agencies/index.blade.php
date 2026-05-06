@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Agencies')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <h1 class="page-title" style="color:white;">Find Your Perfect Agency</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Browse verified travel agencies and explore their tour packages.</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">

    {{-- Search bar --}}
    <div style="background:var(--surface);border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem 1.5rem;margin-bottom:1.75rem;border:1px solid var(--line);">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
            <input type="text" name="search" class="form-control"
                style="flex:1;min-width:220px;"
                placeholder="Search agencies or locations..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('agencies.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    @if($agencies->count())

        {{-- Card grid --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;">
            @foreach($agencies as $agency)
                @php
                    $displayName = $agency->business_name ?: $agency->name;
                    $initials    = strtoupper(substr($displayName, 0, 1));
                    $colors      = ['#2d6a4f','#1d4e89','#6a2d5f','#7d4f00','#1a5276','#2c5364'];
                    $bgColor     = $colors[crc32((string)$agency->id) % count($colors)];
                    $rating      = round($agency->avg_rating ?? 0, 1);
                    $pkgCount    = $agency->listed_packages_count;
                @endphp
                <a href="{{ route('agencies.show', $agency) }}"
                   style="text-decoration:none;color:inherit;display:flex;flex-direction:column;
                          background:var(--surface);border-radius:16px;box-shadow:var(--shadow-sm);
                          border:1px solid var(--line);overflow:hidden;
                          transition:transform .18s,box-shadow .18s;"
                   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 32px rgba(0,0,0,.13)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">

                    {{-- Cover strip --}}
                    <div style="height:72px;background:linear-gradient(135deg,{{ $bgColor }},{{ $bgColor }}99);"></div>

                    {{-- Avatar --}}
                    <div style="padding:0 1.25rem;margin-top:-30px;margin-bottom:.5rem;">
                        <div style="width:60px;height:60px;border-radius:50%;background:{{ $bgColor }};
                                    border:3px solid var(--surface);display:flex;align-items:center;
                                    justify-content:center;font-size:1.5rem;font-weight:700;color:white;
                                    box-shadow:0 2px 8px rgba(0,0,0,.18);">
                            {{ $initials }}
                        </div>
                    </div>

                    {{-- Info --}}
                    <div style="padding:0 1.25rem 1.25rem;flex:1;display:flex;flex-direction:column;gap:.35rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                            <span style="font-weight:700;font-size:1.05rem;color:var(--text-dark);">{{ $displayName }}</span>
                            <span class="badge badge-success" style="font-size:.7rem;">Verified</span>
                        </div>

                        @if($agency->address)
                            <div style="font-size:.85rem;color:var(--text-muted);display:flex;align-items:center;gap:.35rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ Str::limit($agency->address, 50) }}
                            </div>
                        @endif

                        {{-- Stats row --}}
                        <div style="display:flex;gap:1rem;margin-top:.5rem;">
                            <span style="font-size:.83rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                <strong>{{ $pkgCount }}</strong>&nbsp;{{ Str::plural('package', $pkgCount) }}
                            </span>
                            @if($rating > 0)
                                <span style="font-size:.83rem;color:#f59e0b;display:flex;align-items:center;gap:.3rem;">
                                    ★ <strong>{{ $rating }}</strong>
                                </span>
                            @endif
                        </div>

                        <div style="margin-top:auto;padding-top:.75rem;">
                            <span class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">View Profile</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:2rem;">
            {{ $agencies->links('vendor.pagination.dora') }}
        </div>

    @else
        <div style="background:var(--surface);border-radius:16px;box-shadow:var(--shadow-sm);padding:3rem 1.5rem;text-align:center;border:1px solid var(--line);">
            <h3 style="margin:0 0 .5rem;color:var(--text-dark);">No agencies found</h3>
            <p style="margin:0;color:var(--text-muted);">Try adjusting your search to find agencies and their available packages.</p>
        </div>
    @endif
</div>
@endsection
