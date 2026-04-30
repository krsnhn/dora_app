@extends('layouts.app')

@section('title', 'My Backpack')

@section('content')
@push('styles')
<style>
    .page-header {
        background: linear-gradient(160deg, var(--off-white) 60%, rgba(44,95,45,0.08) 100%);
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(44,24,16,0.06);
    }
    
    .page-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }
    
    /* Group Selection */
    .group-selector {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .group-selector-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .group-selector-title {
        font-weight: 600;
        color: var(--deep-earth);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Progress Bar */
    .progress-container {
        background: white;
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .progress-label {
        font-weight: 600;
        color: var(--deep-earth);
    }
    
    .progress-stats {
        font-weight: 700;
        color: var(--forest-green);
    }
    
    .progress-bar-bg {
        background: var(--beige);
        border-radius: 100px;
        height: 10px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--forest-green), var(--ocean-blue));
        border-radius: 100px;
        transition: width 0.5s;
    }
    
    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    }
    
    .category-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        border: 1px solid rgba(44,24,16,0.06);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(44,95,45,0.15);
    }
    
    .category-header {
        padding: 1.5rem;
        background: var(--platinum-beige);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 1px solid rgba(44,24,16,0.08);
    }
    
    .category-icon {
        width: 20px;
        height: 20px;
        color: var(--deep-earth);
    }
    
    .category-title {
        margin: 0;
        font-size: 1rem;
        color: var(--deep-earth);
        text-transform: capitalize;
        font-weight: 600;
    }
    
    .category-count {
        margin-left: auto;
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .category-actions {
        margin-left: 1rem;
    }
    
    .delete-category-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.25rem;
        transition: all 0.2s;
        border-radius: 4px;
    }
    
    .delete-category-btn:hover {
        color: #ef4444;
    }
    
    /* Items List */
    .items-list {
        padding: 0;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .items-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .items-list::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .items-list::-webkit-scrollbar-thumb {
        background: rgba(44,24,16,0.1);
        border-radius: 3px;
    }
    
    .items-list::-webkit-scrollbar-thumb:hover {
        background: rgba(44,24,16,0.2);
    }
    
    .item-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid rgba(44,24,16,0.05);
        transition: background 0.15s;
    }
    
    .item-row:hover {
        background: rgba(44,24,16,0.02);
    }
    
    .checkbox-btn {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: 2px solid var(--beige);
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s;
    }
    
    .checkbox-btn.checked {
        background: var(--forest-green);
        border-color: var(--forest-green);
    }
    
    .item-name {
        flex: 1;
        font-size: 0.95rem;
        transition: all 0.15s;
    }
    
    .item-name.checked {
        color: var(--text-muted);
        text-decoration: line-through;
    }
    
    .delete-item-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        padding: 0.25rem;
        opacity: 0.5;
        transition: all 0.2s;
        border-radius: 4px;
    }
    
    .delete-item-btn:hover {
        opacity: 1;
        color: #ef4444;
    }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--deep-earth);
        font-size: 0.875rem;
    }
    
    .form-input, .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid var(--platinum-beige-dark);
        border-radius: var(--radius-sm);
        font-family: 'Jost', sans-serif;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: white;
    }
    
    .form-input:focus, .form-control:focus {
        outline: none;
        border-color: var(--forest-green);
    }
    
    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .modal-container {
        background: white;
        border-radius: var(--radius-lg);
        max-width: 480px;
        width: 100%;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(44,24,16,0.06);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid var(--platinum-beige-dark);
    }
    
    .modal-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--deep-earth);
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.75rem;
        color: var(--text-muted);
        transition: color 0.2s;
        line-height: 1;
    }
    
    .modal-close:hover {
        color: var(--deep-earth);
    }
    
    .modal-body {
        padding: 1.75rem;
    }
    
    .btn-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 1rem;
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
    }
    
    .empty-icon {
        width: 80px;
        height: 80px;
        background: var(--platinum-beige);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-title {
        color: var(--deep-earth);
        margin-bottom: 0.75rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
    }
    
    .empty-text {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .group-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: var(--forest-green);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        margin-left: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }
        
        .categories-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-body {
            padding: 1.25rem;
        }
        
        .modal-header {
            padding: 1.25rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
        
        .group-selector-header {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

<div class="page-header">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 class="page-title">My Backpack</h1>
                <p class="page-subtitle">Your personal travel packing checklist</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-bottom:4rem;">
    
    <!-- Group Selector -->
    <div class="group-selector">
        <div class="group-selector-header">
            <div class="group-selector-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                </svg>
                Backpack for:
            </div>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                <button onclick="selectGroup('default')" class="btn {{ $selectedGroup === 'default' ? 'btn-primary' : 'btn-secondary' }}" style="font-size:0.9rem;">
                    📦 Default
                </button>
                @foreach($groups as $group)
                    @if($group !== 'default')
                    <button onclick="selectGroup('{{ $group }}')" class="btn {{ $selectedGroup === $group ? 'btn-primary' : 'btn-secondary' }}" style="font-size:0.9rem;">
                        🎒 {{ $group }}
                    </button>
                    @endif
                @endforeach
                <button onclick="openAddGroupModal()" class="btn btn-outline" style="font-size:0.9rem;">
                    + New Trip
                </button>
            </div>
        </div>
        @if($selectedGroup !== 'default')
        <div style="margin-top:0.75rem; font-size:0.85rem; color:var(--text-muted); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
            <span>📅 Packing for: <strong>{{ $selectedGroup }}</strong></span>
            <form method="POST" action="{{ route('backpack.delete-group', $selectedGroup) }}" onsubmit="return confirm('Delete this trip and all its items?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:#fee2e2; color:#dc2626; border:none; padding:0.25rem 0.75rem;">
                    Delete Trip
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Progress Section --}}
    @if($items->count())
    @php
        $checked = $items->where('is_checked', true)->count();
        $total = $items->count();
        $pct = $total > 0 ? round(($checked / $total) * 100) : 0;
    @endphp
    <div class="progress-container">
        <div class="progress-header">
            <span class="progress-label">🎒 Packing Progress</span>
            <span class="progress-stats">{{ $checked }}/{{ $total }} items ({{ $pct }}%)</span>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $pct }}%;"></div>
        </div>
    </div>
    @endif

    @php
    $categories = $items->groupBy('category')->keys()->sort();
    $categoryIcons = [
        'essentials' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'clothing' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
        'toiletries' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
        'electronics' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
        'documents' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'other' => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z',
    ];
    @endphp

    @if($items->count())
    <div class="categories-grid">
        @foreach($categories as $category)
        @php $catItems = $items->where('category', $category); @endphp
        <div class="category-card">
            <div class="category-header">
                <svg class="category-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="{{ $categoryIcons[$category] ?? $categoryIcons['other'] }}"/>
                </svg>
                <h3 class="category-title">{{ ucfirst($category) }}</h3>
                <span class="category-count">{{ $catItems->where('is_checked',true)->count() }}/{{ $catItems->count() }}</span>
                <div class="category-actions">
                    <form method="POST" action="{{ route('backpack.delete-category', $category) }}" onsubmit="return confirm('Delete entire category &quot;{{ ucfirst($category) }}&quot; and all its items?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-category-btn" title="Delete category">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="items-list">
                @foreach($catItems as $item)
                <div class="item-row">
                    <form method="POST" action="{{ route('backpack.toggle', $item) }}" style="display:contents;">
                        @csrf @method('PATCH')
                        <button type="submit" class="checkbox-btn {{ $item->is_checked ? 'checked' : '' }}">
                            @if($item->is_checked)
                            <svg width="12" height="12" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7"/>
                            </svg>
                            @endif
                        </button>
                    </form>
                    <span class="item-name {{ $item->is_checked ? 'checked' : '' }}">
                        {{ $item->name }}
                    </span>
                    <form method="POST" action="{{ route('backpack.destroy', $item) }}" onsubmit="return confirm('Remove this item?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="delete-item-btn" title="Delete item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button onclick="openAddItemModal()" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:0.5rem;">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Add Item
        </button>
        <button onclick="openAddCategoryModal()" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:0.5rem;">
                <path d="M4 7h16M4 12h16M4 17h10"/>
            </svg>
            Add Category
        </button>
    </div>

    @else
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="48" height="48" fill="none" stroke="var(--deep-earth)" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <h2 class="empty-title">Your backpack is empty</h2>
        <p class="empty-text">Add items to your packing checklist and never forget anything on your next adventure.</p>
        <div class="action-buttons">
            <button onclick="openAddItemModal()" class="btn btn-primary">Add First Item</button>
            <button onclick="openAddCategoryModal()" class="btn btn-secondary">Create Category</button>
        </div>
    </div>
    @endif
</div>

{{-- Add Item Modal --}}
<div id="addItemModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Add Packing Item</h3>
            <button onclick="closeAddItemModal()" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('backpack.store') }}">
            @csrf
            <input type="hidden" name="group_name" value="{{ $selectedGroup }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Item Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g., Sunscreen SPF 50, Passport, Power Bank" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                        @endforeach
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Add Item</button>
                    <button type="button" onclick="closeAddItemModal()" class="btn btn-secondary" style="flex:1;">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add Category Modal --}}
<div id="addCategoryModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Create New Category</h3>
            <button onclick="closeAddCategoryModal()" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('backpack.add-category') }}">
            @csrf
            <input type="hidden" name="group_name" value="{{ $selectedGroup }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="category_name" class="form-input" placeholder="e.g., Medications, Snacks, Gear" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (optional)</label>
                    <select name="icon" class="form-control">
                        <option value="🎒">🎒 Backpack</option>
                        <option value="👕">👕 Clothing</option>
                        <option value="🧴">🧴 Toiletries</option>
                        <option value="📱">📱 Electronics</option>
                        <option value="📄">📄 Documents</option>
                        <option value="💊">💊 Medicine</option>
                        <option value="🍎">🍎 Snacks</option>
                        <option value="🔦">🔦 Gear</option>
                        <option value="⭐">⭐ Other</option>
                    </select>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Create Category</button>
                    <button type="button" onclick="closeAddCategoryModal()" class="btn btn-secondary" style="flex:1;">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add Group Modal --}}
<div id="addGroupModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Create New Trip</h3>
            <button onclick="closeAddGroupModal()" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('backpack.add-group') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Trip Name *</label>
                    <input type="text" name="group_name" class="form-input" placeholder="e.g., Bali Summer 2024, Japan Cherry Blossom" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Travel Date (optional)</label>
                    <input type="date" name="travel_date" class="form-input">
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Create Trip</button>
                    <button type="button" onclick="closeAddGroupModal()" class="btn btn-secondary" style="flex:1;">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Group selection
function selectGroup(group) {
    window.location.href = '{{ route("backpack.index") }}?group=' + encodeURIComponent(group);
}

// Add Item Modal
function openAddItemModal() {
    document.getElementById('addItemModal').style.display = 'flex';
}

function closeAddItemModal() {
    document.getElementById('addItemModal').style.display = 'none';
}

// Add Category Modal
function openAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'flex';
}

function closeAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'none';
}

// Add Group Modal
function openAddGroupModal() {
    document.getElementById('addGroupModal').style.display = 'flex';
}

function closeAddGroupModal() {
    document.getElementById('addGroupModal').style.display = 'none';
}

// Close modals when clicking outside
document.getElementById('addItemModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddItemModal();
});

document.getElementById('addCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddCategoryModal();
});

document.getElementById('addGroupModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddGroupModal();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddItemModal();
        closeAddCategoryModal();
        closeAddGroupModal();
    }
});
</script>
@endsection