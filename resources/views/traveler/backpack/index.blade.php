@extends('layouts.app')

@section('title', 'Backpack Checklists')

@push('styles')
<style>
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .page-header {
        background: linear-gradient(160deg, var(--deep-earth) 0%, var(--forest-green) 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
        color: white;
    }
    
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        color: rgba(255,255,255,0.9);
    }
    .backpack-grid{display:grid;grid-template-columns:300px minmax(0,1fr);gap:1.25rem;align-items:start}
    .group-list{display:grid;gap:.6rem}
    .group-link{display:block;text-decoration:none;padding:.85rem;border:1px solid var(--line);border-radius:10px;background:var(--surface)}
    .group-link.active,.group-link:hover{border-color:var(--primary);background:rgba(37,107,143,.1)}
    .item-row{display:flex;align-items:center;gap:.75rem;padding:.7rem;border:1px solid var(--line);border-radius:9px;background:var(--surface)}
    .backpack-hero{background:linear-gradient(135deg,#153f52 0%,#2f7d62 100%);border-radius:14px;padding:1.5rem;color:white;margin-bottom:1.5rem;box-shadow:var(--shadow-sm)}
    .backpack-hero .page-title{color:white!important}
    .backpack-hero .page-sub{color:rgba(255,255,255,.82);margin-bottom:0}
    @media(max-width:900px){.backpack-grid{grid-template-columns:1fr}.backpack-side{position:static!important}}
    @media(max-width:760px){.group-edit-form,.item-create-form{grid-template-columns:1fr!important}}
</style>
@endpush

@section('content')
@php
    $checked = $items->where('is_checked', true)->count();
    $total = $items->count();
    $pct = $total ? round(($checked / $total) * 100) : 0;
    $categories = $items->groupBy('category');
@endphp

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Backpack Checklist</h1>
        <p class="page-subtitle">Create your personalized packing lists for each trip.</p>
    </div>
</div>

    <div class="backpack-grid">
        <aside class="backpack-side" style="display:grid;gap:1rem;position:sticky;top:84px;">
            <section class="card" style="padding:1rem;">
                <h2 style="font-family:Inter,system-ui,sans-serif;font-size:1rem;font-weight:800;margin-bottom:.9rem;">Checklist Groups</h2>
                <div class="group-list">
                    @foreach($groups as $group)
                        @php
                            $groupTotal = $group->items_count ?? $group->items()->count();
                            $groupChecked = $group->checked_items_count ?? 0;
                        @endphp
                        <a href="{{ route('backpack.index', ['group' => $group->id]) }}" class="group-link {{ $selectedGroup->id === $group->id ? 'active' : '' }}">
                            <strong>{{ $group->title }}</strong>
                            <div style="display:flex;justify-content:space-between;gap:.75rem;margin-top:.3rem;color:var(--text-muted);font-size:.82rem;">
                                <span>{{ $groupTotal }} items</span>
                                <span>{{ $groupChecked }} packed</span>
                            </div>
                            <div style="margin-top:.3rem;color:var(--text-muted);font-size:.78rem;">
                                @if($group->start_date && $group->end_date)
                                    {{ $group->start_date->format('M j') }} - {{ $group->end_date->format('M j, Y') }} · {{ $group->trip_days }} {{ Str::plural('day', $group->trip_days) }}
                                @elseif($group->start_date)
                                    From {{ $group->start_date->format('M j, Y') }}
                                @else
                                    No dates set
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="card" style="padding:1rem;">
                <h2 style="font-family:Inter,system-ui,sans-serif;font-size:1rem;font-weight:800;margin-bottom:.9rem;">Create Group</h2>
                <form method="POST" action="{{ route('backpack.add-group') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Checklist title</label>
                        <input name="title" class="form-input" required placeholder="e.g. Cebu Summer Trip">
                    </div>
                    <div class="form-group">
                        <label class="form-label">From</label>
                        <input name="start_date" type="date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">To</label>
                        <input name="end_date" type="date" class="form-input">
                    </div>
                    <button class="btn btn-primary" type="submit" style="width:100%;">Create group</button>
                </form>
            </section>
        </aside>

        <main style="display:grid;gap:1rem;">
            <section class="card" style="padding:1rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                    <div>
                        <h2 style="font-family:Inter,system-ui,sans-serif;font-size:1.35rem;font-weight:800;">{{ $selectedGroup->title }}</h2>
                        <p style="color:var(--text-muted);font-size:.9rem;margin-top:.2rem;">
                            @if($selectedGroup->start_date && $selectedGroup->end_date)
                                {{ $selectedGroup->start_date->format('M j, Y') }} to {{ $selectedGroup->end_date->format('M j, Y') }} · {{ $selectedGroup->trip_days }} {{ Str::plural('day', $selectedGroup->trip_days) }}
                            @elseif($selectedGroup->start_date)
                                From {{ $selectedGroup->start_date->format('M j, Y') }}
                            @else
                                No travel dates set
                            @endif
                        </p>
                    </div>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <button class="btn btn-outline btn-sm" type="button" onclick="document.getElementById('editGroupPanel').style.display = document.getElementById('editGroupPanel').style.display === 'none' ? 'block' : 'none'">Edit group</button>
                        @if($groups->count() > 1)
                            <form method="POST" action="{{ route('backpack.delete-group', $selectedGroup) }}" data-confirm="Delete this checklist group and all its items?" data-confirm-title="Delete Group" data-confirm-text="Delete" data-confirm-danger="true">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete group</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div id="editGroupPanel" style="display:none;margin-top:1rem;border-top:1px solid var(--line);padding-top:1rem;">
                    <form class="group-edit-form" method="POST" action="{{ route('backpack.update-group', $selectedGroup) }}" data-confirm="Save changes to this checklist group?" data-confirm-title="Update Group" data-confirm-text="Save" style="display:grid;grid-template-columns:1fr 170px 170px auto;gap:.75rem;align-items:end;">
                        @csrf @method('PATCH')
                        <div>
                            <label class="form-label">Title</label>
                            <input name="title" class="form-input" value="{{ $selectedGroup->title }}" required>
                        </div>
                        <div>
                            <label class="form-label">From</label>
                            <input name="start_date" type="date" class="form-input" value="{{ optional($selectedGroup->start_date)->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="form-label">To</label>
                            <input name="end_date" type="date" class="form-input" value="{{ optional($selectedGroup->end_date)->format('Y-m-d') }}">
                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </section>

            <div class="stats-grid">
                <div class="stat-card"><div class="stat-number">{{ $total }}</div><div class="stat-label">Items</div></div>
                <div class="stat-card"><div class="stat-number">{{ $checked }}</div><div class="stat-label">Packed</div></div>
                <div class="stat-card"><div class="stat-number">{{ $pct }}%</div><div class="stat-label">Complete</div></div>
            </div>

            <section class="card" style="padding:1rem;">
                <form class="item-create-form" method="POST" action="{{ route('backpack.store') }}" style="display:grid;grid-template-columns:minmax(220px,1fr) 190px auto;gap:.75rem;align-items:end;">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $selectedGroup->id }}">
                    <div>
                        <label class="form-label">Checklist item</label>
                        <input name="name" class="form-input" required placeholder="Passport, power bank, swimwear">
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input" required>
                            @foreach($categoryOptions as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Add item</button>
                </form>
            </section>

            <section class="section-box">
                <div class="section-box-header">
                    <span class="section-box-title">Checklist Items</span>
                    @if($checked)
                        <form method="POST" action="{{ route('backpack.clear-checked') }}" data-confirm="Clear all packed items from this group?" data-confirm-title="Clear Packed Items" data-confirm-text="Clear" data-confirm-danger="true">
                            @csrf @method('DELETE')
                            <input type="hidden" name="group_id" value="{{ $selectedGroup->id }}">
                            <button class="btn btn-ghost btn-sm" type="submit">Clear packed</button>
                        </form>
                    @endif
                </div>
                <div style="padding:1rem;display:grid;gap:1rem;">
                    @forelse($categories as $category => $categoryItems)
                        <div>
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:.55rem;">
                                <h3 style="font-family:Inter,system-ui,sans-serif;font-size:.82rem;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);">{{ ucfirst($category) }}</h3>
                                <form method="POST" action="{{ route('backpack.delete-category', $category) }}" data-confirm="Delete this category and all items inside it?" data-confirm-title="Delete Category" data-confirm-text="Delete" data-confirm-danger="true">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="group_id" value="{{ $selectedGroup->id }}">
                                    <button class="btn btn-ghost btn-sm" type="submit">Delete category</button>
                                </form>
                            </div>
                            <div style="display:grid;gap:.5rem;">
                                @foreach($categoryItems as $item)
                                    <div class="item-row">
                                        <form method="POST" action="{{ route('backpack.toggle', $item) }}" data-confirm="{{ $item->is_checked ? 'Mark this item as unpacked?' : 'Mark this item as packed?' }}" data-confirm-title="Update Item" data-confirm-text="Update">
                                            @csrf @method('PATCH')
                                            <button type="submit" aria-label="Toggle packed" style="width:24px;height:24px;border-radius:7px;border:2px solid {{ $item->is_checked ? 'var(--forest-green)' : 'var(--line)' }};background:{{ $item->is_checked ? 'var(--forest-green)' : 'transparent' }};color:white;cursor:pointer;display:grid;place-items:center;">
                                                @if($item->is_checked)
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                                @endif
                                            </button>
                                        </form>
                                        <span style="flex:1;{{ $item->is_checked ? 'text-decoration:line-through;color:var(--text-muted);' : '' }}">{{ $item->name }}</span>
                                        <form method="POST" action="{{ route('backpack.destroy', $item) }}" data-confirm="Remove this item?" data-confirm-title="Remove Item" data-confirm-text="Remove" data-confirm-danger="true">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-ghost btn-sm" type="submit">Remove</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;padding:3rem 1rem;color:var(--text-muted);">
                            <h3 style="font-size:1.2rem;color:var(--text-dark);margin-bottom:.35rem;">No checklist items yet</h3>
                            Add your first item above.
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</div>
@endsection
