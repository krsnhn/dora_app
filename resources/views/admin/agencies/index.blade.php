@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Agency Directory')

@section('content')
<div class="page-header" style="background:linear-gradient(160deg,var(--deep-earth) 0%,var(--forest-green) 100%);color:white;">
    <div class="container">
        <h1 class="page-title" style="color:white;">Agency Directory</h1>
        <p class="page-subtitle" style="color:rgba(255,255,255,0.9);">Review every agency account and the packages currently attached to it.</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    <div style="background:var(--surface);border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem 1.5rem;margin-bottom:1.5rem;border:1px solid var(--line);">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
            <input type="text" name="search" class="form-control" style="flex:1;min-width:220px;" placeholder="Search agency name, email, or business name..." value="{{ request('search') }}">
            <select name="status" class="form-control" style="width:auto;min-width:160px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.agencies.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    @if($agencies->count())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:1.5rem;">
            @foreach($agencies as $agency)
                @php
                    $displayName = $agency->business_name ?: $agency->name;
                    $initials = strtoupper(substr($displayName, 0, 1));
                    $colors = ['#2d6a4f','#1d4e89','#6a2d5f','#7d4f00','#1a5276','#2c5364'];
                    $bgColor = $colors[crc32((string)$agency->id) % count($colors)];
                    $rating = round($agency->avg_rating ?? 0, 1);
                @endphp
                <a href="{{ route('admin.agencies.show', $agency) }}"
                   style="text-decoration:none;color:inherit;display:flex;flex-direction:column;background:var(--surface);border-radius:16px;box-shadow:var(--shadow-sm);border:1px solid var(--line);overflow:hidden;transition:transform .18s,box-shadow .18s;"
                   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 32px rgba(0,0,0,.13)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='var(--shadow-sm)'">
                    <div style="height:72px;background:linear-gradient(135deg,{{ $bgColor }},{{ $bgColor }}99);"></div>
                    <div style="padding:0 1.25rem;margin-top:-30px;margin-bottom:.5rem;">
                        <div style="width:60px;height:60px;border-radius:50%;background:{{ $bgColor }};border:3px solid var(--surface);display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:700;color:white;box-shadow:0 2px 8px rgba(0,0,0,.18);">
                            {{ $initials }}
                        </div>
                    </div>

                    <div style="padding:0 1.25rem 1.25rem;flex:1;display:flex;flex-direction:column;gap:.35rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                            <span style="font-weight:700;font-size:1.05rem;color:var(--text-dark);">{{ $displayName }}</span>
                            <span class="badge {{ $agency->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($agency->status) }}</span>
                            <span class="badge {{ $agency->agency_status === 'approved' ? 'badge-blue' : ($agency->agency_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($agency->agency_status) }}</span>
                        </div>

                        <div style="font-size:.85rem;color:var(--text-muted);">{{ $agency->email }}</div>
                        @if($agency->address)
                            <div style="font-size:.83rem;color:var(--text-muted);">{{ Str::limit($agency->address, 48) }}</div>
                        @endif

                        <div style="display:flex;gap:1rem;margin-top:.5rem;flex-wrap:wrap;">
                            <span style="font-size:.83rem;color:var(--text-muted);"><strong>{{ $agency->tour_packages_count }}</strong> {{ Str::plural('package', $agency->tour_packages_count) }}</span>
                            @if($rating > 0)
                                <span style="font-size:.83rem;color:#f59e0b;">★ <strong>{{ $rating }}</strong></span>
                            @endif
                        </div>

                        <div style="margin-top:auto;padding-top:.8rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                            <span class="btn btn-primary btn-sm" style="flex:1;text-align:center;display:block;">View Profile</span>
                            <span class="btn btn-outline btn-sm" style="flex:1;text-align:center;display:block;">Inspect Packages</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">
            {{ $agencies->links('vendor.pagination.dora') }}
        </div>
    @else
        <div style="background:var(--surface);border-radius:16px;box-shadow:var(--shadow-sm);padding:3rem 1.5rem;text-align:center;border:1px solid var(--line);">
            <h3 style="margin:0 0 .5rem;color:var(--text-dark);">No agencies found</h3>
            <p style="margin:0;color:var(--text-muted);">Try adjusting the search or status filter.</p>
        </div>
    @endif
</div>
@endsection
