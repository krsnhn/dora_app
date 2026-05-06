@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', ($user->business_name ?: $user->name) . ' - Admin Agency Profile')

@section('content')
@php
    $displayName = $user->business_name ?: $user->name;
    $initials = strtoupper(substr($displayName, 0, 1));
    $colors = ['#2d6a4f','#1d4e89','#6a2d5f','#7d4f00','#1a5276','#2c5364'];
    $bgColor = $colors[crc32((string)$user->id) % count($colors)];
@endphp

<div style="background:var(--surface);border-bottom:1px solid var(--line);margin-bottom:2rem;">
    <div style="height:190px;background:linear-gradient(135deg,{{ $bgColor }},{{ $bgColor }}99);"></div>

    <div class="container" style="padding-bottom:1.25rem;">
        <div style="display:flex;align-items:flex-end;gap:1.25rem;flex-wrap:wrap;margin-top:-48px;">
            <div style="width:96px;height:96px;border-radius:50%;background:{{ $bgColor }};border:4px solid var(--surface);display:flex;align-items:center;justify-content:center;font-size:2.3rem;font-weight:700;color:white;box-shadow:0 4px 16px rgba(0,0,0,.22);">
                {{ $initials }}
            </div>

            <div style="flex:1;min-width:220px;padding-top:52px;">
                <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                    <h1 style="margin:0;font-size:1.5rem;color:var(--text-dark);">{{ $displayName }}</h1>
                    <span class="badge {{ $user->status === 'active' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($user->status) }}</span>
                    <span class="badge {{ $user->agency_status === 'approved' ? 'badge-blue' : ($user->agency_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($user->agency_status) }}</span>
                </div>
                <div style="margin-top:.45rem;color:var(--text-muted);font-size:.9rem;">{{ $user->email }}{{ $user->phone ? ' - '.$user->phone : '' }}</div>
                @if($user->address)
                    <div style="margin-top:.35rem;color:var(--text-muted);font-size:.86rem;">{{ $user->address }}</div>
                @endif
            </div>

            <div style="display:flex;gap:.6rem;flex-wrap:wrap;padding-top:52px;">
                <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" class="btn btn-outline btn-sm">Open User Record</a>
                @if($user->facebook_page)
                    <a href="{{ $user->facebook_page }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm">Facebook Page</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-bottom:3rem;">
    <a href="{{ route('admin.agencies.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--text-muted);font-size:.88rem;text-decoration:none;margin-bottom:1.25rem;">Back to Agency Directory</a>

    <div style="display:grid;grid-template-columns:1fr minmax(0,330px);gap:1.75rem;" class="admin-agency-show-grid">
        <div>
            <h2 style="font-size:1.15rem;font-weight:700;color:var(--text-dark);margin:0 0 1rem;">Attached Packages <span style="font-size:.84rem;font-weight:400;color:var(--text-muted);">({{ $packages->count() }})</span></h2>

            @forelse($packages as $package)
                <article style="background:var(--surface);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-sm);padding:1.1rem 1.2rem;margin-bottom:1rem;">
                    <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;align-items:flex-start;">
                        <div>
                            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                                <strong style="font-size:1rem;color:var(--text-dark);">{{ $package->name }}</strong>
                                <span class="badge badge-earth">{{ $package->destination?->name ?? 'No destination linked' }}</span>
                                <span class="badge {{ $package->status === 'active' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($package->status) }}</span>
                            </div>
                            @if($package->description)
                                <p style="margin:.45rem 0 0;color:var(--text-muted);font-size:.88rem;line-height:1.55;">{{ Str::limit($package->description, 220) }}</p>
                            @endif
                            @if($package->inclusions)
                                <div style="margin-top:.5rem;font-size:.82rem;color:var(--text-muted);padding:.55rem .75rem;border-radius:8px;background:var(--surface-soft);border:1px solid var(--line);">
                                    <strong style="color:var(--deep-earth);">Inclusions:</strong> {{ $package->inclusions }}
                                </div>
                            @endif
                        </div>
                        <div style="text-align:right;min-width:150px;">
                            <div style="font-size:1.1rem;font-weight:800;color:var(--text-dark);">PHP {{ number_format($package->price, 2) }}</div>
                            <div style="color:var(--text-muted);font-size:.85rem;">{{ $package->duration }} {{ Str::plural('day', (int) $package->duration) }}</div>
                        </div>
                    </div>
                </article>
            @empty
                <div style="background:var(--surface);border:1px dashed var(--line);border-radius:16px;padding:2.2rem;text-align:center;color:var(--text-muted);">
                    No packages are attached to this agency yet.
                </div>
            @endforelse
        </div>

        <aside>
            <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.2rem;box-shadow:var(--shadow-sm);margin-bottom:1rem;">
                <h3 style="margin:0 0 .75rem;font-size:1rem;color:var(--text-dark);">Account Snapshot</h3>
                <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.86rem;color:var(--text-muted);">
                    <div><strong style="color:var(--text-dark);">Status:</strong> {{ ucfirst($user->status) }}</div>
                    <div><strong style="color:var(--text-dark);">Agency Review:</strong> {{ ucfirst($user->agency_status) }}</div>
                    <div><strong style="color:var(--text-dark);">Packages:</strong> {{ $packages->count() }}</div>
                    <div><strong style="color:var(--text-dark);">Reviews:</strong> {{ $feedbacks->count() }}</div>
                    @if(($avgRating ?? 0) > 0)
                        <div><strong style="color:var(--text-dark);">Avg Rating:</strong> <span style="color:#f59e0b;">★ {{ number_format($avgRating, 1) }}</span></div>
                    @endif
                </div>
            </div>

            @if($feedbacks->count())
                <div style="background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:1.2rem;box-shadow:var(--shadow-sm);">
                    <h3 style="margin:0 0 .8rem;font-size:1rem;color:var(--text-dark);">Latest Reviews</h3>
                    <div style="display:flex;flex-direction:column;gap:.85rem;">
                        @foreach($feedbacks as $fb)
                            <div style="border-bottom:1px solid var(--line);padding-bottom:.65rem;">
                                <div style="display:flex;justify-content:space-between;gap:.5rem;align-items:center;">
                                    <strong style="font-size:.84rem;color:var(--text-dark);">{{ $fb->user->name }}</strong>
                                    <span style="color:#f59e0b;font-size:.84rem;">{{ str_repeat('★', $fb->rating) }}{{ str_repeat('☆', 5 - $fb->rating) }}</span>
                                </div>
                                @if($fb->comment)
                                    <p style="margin:.25rem 0 0;font-size:.8rem;color:var(--text-muted);line-height:1.5;">{{ Str::limit($fb->comment, 120) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>

<style>
@media(max-width:768px){
    .admin-agency-show-grid{grid-template-columns:1fr !important;}
}
</style>
@endsection
