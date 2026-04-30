@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Platform overview and management</p>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">

    {{-- Stats Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:2.5rem;">
        @php
        $stats = [
            ['label'=>'Total Users','value'=>$stats['total_users'],'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','color'=>'var(--ocean)','bg'=>'#e0f2fe'],
            ['label'=>'Destinations','value'=>$stats['total_destinations'],'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z','color'=>'var(--forest)','bg'=>'#dcfce7'],
            ['label'=>'Tour Packages','value'=>$stats['total_packages'],'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','color'=>'var(--sunset)','bg'=>'#fff7ed'],
            ['label'=>'Pending Agencies','value'=>$stats['pending_agencies'],'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'#9333ea','bg'=>'#f3e8ff'],
            ['label'=>'Total Inquiries','value'=>$stats['total_inquiries'],'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z','color'=>'#0891b2','bg'=>'#e0f7fa'],
            ['label'=>'Pending Feedback','value'=>$stats['pending_feedback'],'icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','color'=>'#d97706','bg'=>'#fef3c7'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div style="background:white;border-radius:14px;box-shadow:var(--shadow-sm);padding:1.5rem;display:flex;align-items:center;gap:1rem;">
            <div style="width:52px;height:52px;border-radius:12px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="24" height="24" fill="none" stroke="{{ $stat['color'] }}" stroke-width="1.5" viewBox="0 0 24 24"><path d="{{ $stat['icon'] }}"/></svg>
            </div>
            <div>
                <div style="font-size:1.8rem;font-weight:800;color:var(--earth);line-height:1;">{{ number_format($stat['value']) }}</div>
                <div style="font-size:.85rem;color:var(--text-muted);">{{ $stat['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

        {{-- Recent Users --}}
        <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--beige);display:flex;justify-content:space-between;align-items:center;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--earth);margin:0;">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" style="font-size:.85rem;color:var(--ocean);text-decoration:none;">View all →</a>
            </div>
            <div style="padding:1rem;">
                @foreach($recentUsers as $user)
                <div style="display:flex;align-items:center;gap:.75rem;padding:.6rem 0;border-bottom:1px solid #f5f5f5;">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--ocean);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;color:var(--earth);font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">{{ ucfirst($user->role) }} · {{ $user->created_at->diffForHumans() }}</div>
                    </div>
                    @if($user->status === 'active')
                        <span class="badge badge-success" style="font-size:.7rem;">Active</span>
                    @elseif($user->status === 'pending')
                        <span class="badge badge-warning" style="font-size:.7rem;">Pending</span>
                    @else
                        <span class="badge badge-danger" style="font-size:.7rem;">{{ ucfirst($user->status) }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Destinations + Quick Links --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            {{-- Quick Links --}}
            <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);padding:1.5rem;">
                <h3 style="font-size:1rem;font-weight:700;color:var(--earth);margin:0 0 1rem;">Quick Actions</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    <a href="{{ route('admin.users.index') }}?role=agency&status=pending" style="background:var(--beige);border-radius:10px;padding:1rem;text-decoration:none;text-align:center;transition:all .2s;" onmouseover="this.style.background='var(--forest)';this.querySelector('*').style.color='white'" onmouseout="this.style.background='var(--beige)';this.querySelector('*').style.color='var(--earth)'">
                        <div style="font-weight:700;color:var(--earth);font-size:.9rem;transition:color .2s;">Verify Agencies</div>
                        <div style="color:var(--text-muted);font-size:.8rem;">{{ $stats['pending_agencies'] }} pending</div>
                    </a>
                    <a href="{{ route('admin.destinations.index') }}" style="background:var(--beige);border-radius:10px;padding:1rem;text-decoration:none;text-align:center;">
                        <div style="font-weight:700;color:var(--earth);font-size:.9rem;">Destinations</div>
                        <div style="color:var(--text-muted);font-size:.8rem;">Manage all</div>
                    </a>
                    <a href="{{ route('admin.feedback.index') }}" style="background:var(--beige);border-radius:10px;padding:1rem;text-decoration:none;text-align:center;">
                        <div style="font-weight:700;color:var(--earth);font-size:.9rem;">Moderate Feedback</div>
                        <div style="color:var(--text-muted);font-size:.8rem;">{{ $stats['pending_feedback'] }} pending</div>
                    </a>
                    <a href="{{ route('admin.destinations.create') }}" style="background:var(--forest);border-radius:10px;padding:1rem;text-decoration:none;text-align:center;">
                        <div style="font-weight:700;color:white;font-size:.9rem;">+ Add Destination</div>
                        <div style="color:rgba(255,255,255,.7);font-size:.8rem;">New destination</div>
                    </a>
                </div>
            </div>

            {{-- Recent Feedback --}}
            <div style="background:white;border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;flex:1;">
                <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--beige);display:flex;justify-content:space-between;align-items:center;">
                    <h3 style="font-size:1rem;font-weight:700;color:var(--earth);margin:0;">Pending Feedback</h3>
                    <a href="{{ route('admin.feedback.index') }}" style="font-size:.85rem;color:var(--ocean);text-decoration:none;">View all →</a>
                </div>
                <div style="padding:1rem;">
                    @forelse($recentFeedback as $fb)
                    <div style="padding:.6rem 0;border-bottom:1px solid #f5f5f5;">
                        <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.25rem;">
                            @for($i=1;$i<=5;$i++)
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="{{ $i <= $fb->rating ? '#f59e0b' : 'none' }}" stroke="#f59e0b" stroke-width="2"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            @endfor
                            <span style="font-size:.8rem;color:var(--text-muted);margin-left:.25rem;">{{ $fb->user->name ?? 'Anonymous' }}</span>
                        </div>
                        <p style="font-size:.85rem;color:var(--earth);margin:0;">{{ Str::limit($fb->comment, 60) }}</p>
                    </div>
                    @empty
                    <p style="color:var(--text-muted);font-size:.9rem;text-align:center;padding:.5rem 0;">No pending feedback</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
