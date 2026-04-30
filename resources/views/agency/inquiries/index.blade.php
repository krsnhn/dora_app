@extends('layouts.app')

@section('title', 'Inquiries')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Inquiries</h1>
        <p class="page-subtitle">Manage traveler inquiries about your packages</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    {{-- Filter Bar --}}
    <div style="background:white;border-radius:12px;box-shadow:var(--shadow-sm);padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
        <form method="GET" style="display:flex;gap:.75rem;flex-wrap:wrap;width:100%;">
           
            <input type="text" name="search" class="form-input" style="flex:2;min-width:200px;"
                   placeholder="Search by name or email..." value="{{ request('search') }}">
             <select name="status" class="form-input" style="width:auto;flex:1;min-width:150px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request()->hasAny(['status','search']))
            <a href="{{ route('agency.inquiries.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    @if($inquiries->count())
    <div style="display:grid;gap:1rem;">
        @foreach($inquiries as $inquiry)
        <div style="background:white;border-radius:14px;box-shadow:var(--shadow-sm);padding:1.5rem;border-left:4px solid
            @if($inquiry->status === 'confirmed') var(--forest-green)
            @elseif($inquiry->status === 'contacted') var(--ocean-blue)
            @elseif($inquiry->status === 'cancelled') #dc2626
            @else var(--sunset-orange) @endif;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--forest-green);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;">
                            {{ strtoupper(substr($inquiry->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--text-dark);">{{ $inquiry->name }}</div>
                            <div style="font-size:.85rem;color:var(--text-muted);">{{ $inquiry->email }}</div>
                        </div>
                        @php
                            $statusColors = ['pending'=>'warning','contacted'=>'info','confirmed'=>'success','cancelled'=>'danger'];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$inquiry->status] ?? 'warning' }}">
                            {{ ucfirst($inquiry->status) }}
                        </span>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem;margin-bottom:.75rem;">
                        <div style="background:var(--platinum-beige);border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Package</div>
                            <div style="font-weight:600;color:var(--text-dark);font-size:.9rem;">{{ $inquiry->tourPackage->name }}</div>
                        </div>
                        <div style="background:var(--platinum-beige);border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Group Size</div>
                            <div style="font-weight:600;color:var(--text-dark);font-size:.9rem;">{{ $inquiry->pax }} {{ $inquiry->pax == 1 ? 'person' : 'people' }}</div>
                        </div>
                        <div style="background:var(--platinum-beige);border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Received</div>
                            <div style="font-weight:600;color:var(--text-dark);font-size:.9rem;">{{ $inquiry->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if($inquiry->message)
                    <div style="background:#f8f9fa;border-radius:8px;padding:.75rem 1rem;font-size:.9rem;color:var(--text-muted);font-style:italic;">
                        "{{ $inquiry->message }}"
                    </div>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;gap:.5rem;min-width:140px;">
                    {{-- Reply by email --}}
                    <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->tourPackage->name }} Inquiry"
                       class="btn btn-primary btn-sm" style="text-align:center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:.3rem;"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Reply
                    </a>

                    {{-- Status Update --}}
                    <form method="POST" action="{{ route('agency.inquiries.update', $inquiry) }}">
                        @csrf @method('PATCH')
                        <select name="status" class="form-input" style="font-size:.8rem;padding:.4rem .6rem;margin-bottom:.4rem;" onchange="this.form.submit()">
                            <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="confirmed" {{ $inquiry->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ $inquiry->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:1.5rem;">
        {{ $inquiries->appends(request()->query())->links('vendor.pagination.dora') }}
    </div>

    @else
    <div style="text-align:center;padding:5rem 1rem;background:white;border-radius:16px;box-shadow:var(--shadow-md);">
        <div style="width:80px;height:80px;background:var(--platinum-beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg width="36" height="36" fill="none" stroke="var(--text-dark)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h3 style="color:var(--text-dark);margin-bottom:.5rem;">No inquiries yet</h3>
        <p style="color:var(--text-muted);">When travelers inquire about your packages, they'll appear here.</p>
    </div>
    @endif
</div>
@endsection
