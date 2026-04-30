@extends('layouts.app')

@section('title', 'My Inquiries')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">My Inquiries</h1>
        <p class="page-subtitle">Track your package inquiries and their status</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    @if($inquiries->count())
    <div style="display:grid;gap:1rem;">
        @foreach($inquiries as $inquiry)
        <div style="background:white;border-radius:14px;box-shadow:var(--shadow-sm);padding:1.5rem;border-left:4px solid
            @if($inquiry->status === 'confirmed') var(--forest)
            @elseif($inquiry->status === 'contacted') var(--ocean)
            @elseif($inquiry->status === 'cancelled') #dc2626
            @else var(--sunset) @endif;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem;flex-wrap:wrap;">
                        <h3 style="margin:0;color:var(--earth);font-size:1.05rem;">{{ $inquiry->tourPackage->name ?? 'Package' }}</h3>
                        @php
                            $colors = ['pending'=>'warning','contacted'=>'info','confirmed'=>'success','cancelled'=>'danger'];
                        @endphp
                        <span class="badge badge-{{ $colors[$inquiry->status] ?? 'warning' }}">{{ ucfirst($inquiry->status) }}</span>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;margin-bottom:.75rem;">
                        <div style="background:#f8f9fa;border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Destination</div>
                            <div style="font-weight:600;color:var(--earth);font-size:.9rem;">
                                {{ $inquiry->tourPackage->destination->name ?? '—' }}
                            </div>
                        </div>
                        <div style="background:#f8f9fa;border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Group Size</div>
                            <div style="font-weight:600;color:var(--earth);font-size:.9rem;">{{ $inquiry->pax }} {{ $inquiry->pax == 1 ? 'person' : 'people' }}</div>
                        </div>
                        <div style="background:#f8f9fa;border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Price</div>
                            <div style="font-weight:600;color:var(--forest);font-size:.9rem;">₱{{ number_format($inquiry->tourPackage->price ?? 0, 2) }}</div>
                        </div>
                        <div style="background:#f8f9fa;border-radius:8px;padding:.6rem .9rem;">
                            <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;">Submitted</div>
                            <div style="font-weight:600;color:var(--earth);font-size:.9rem;">{{ $inquiry->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>

                    @if($inquiry->message)
                    <div style="background:#f9fafb;border-radius:8px;padding:.75rem 1rem;font-size:.88rem;color:var(--text-muted);font-style:italic;">
                        "{{ $inquiry->message }}"
                    </div>
                    @endif
                </div>

                <div style="min-width:120px;text-align:right;">
                    @if($inquiry->tourPackage)
                    <a href="{{ route('destinations.show', $inquiry->tourPackage->destination) }}" class="btn btn-secondary btn-sm">
                        View Package
                    </a>
                    @endif
                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:.5rem;">
                        Agency: {{ $inquiry->tourPackage->agency->business_name ?? $inquiry->tourPackage->agency->name ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:1.5rem;">
        {{ $inquiries->links('vendor.pagination.dora') }}
    </div>

    @else
    <div style="text-align:center;padding:6rem 1rem;">
        <div style="width:100px;height:100px;background:var(--beige);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;">
            <svg width="48" height="48" fill="none" stroke="var(--earth)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h2 style="color:var(--earth);margin-bottom:.75rem;">No inquiries yet</h2>
        <p style="color:var(--text-muted);max-width:400px;margin:0 auto 2rem;line-height:1.6;">Browse destinations and inquire about tour packages that interest you.</p>
        <a href="{{ route('destinations.index') }}" class="btn btn-primary">Explore Destinations</a>
    </div>
    @endif
</div>
@endsection
