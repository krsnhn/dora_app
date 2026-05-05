<?php

namespace App\Http\Controllers;

use App\Models\BackpackGroup;
use App\Models\BackpackItem;
use Illuminate\Http\Request;

class BackpackController extends Controller
{
    private const CATEGORIES = [
        'essentials',
        'documents',
        'clothing',
        'toiletries',
        'electronics',
        'medicine',
        'gear',
        'snacks',
        'other',
    ];

    public function index(Request $request)
    {
        $groups = auth()->user()->backpackGroups()
            ->withCount([
                'items',
                'items as checked_items_count' => fn ($query) => $query->where('is_checked', true),
            ])
            ->latest()
            ->get();

        if ($groups->isEmpty()) {
            $groups = collect([
                auth()->user()->backpackGroups()->create(['title' => 'Default Trip']),
            ]);
        }

        $selectedGroup = $request->filled('group')
            ? auth()->user()->backpackGroups()->findOrFail($request->get('group'))
            : $groups->first();

        $items = $selectedGroup->items()
            ->orderBy('category')
            ->orderBy('created_at')
            ->get();

        $categoryOptions = self::CATEGORIES;

        return view('traveler.backpack.index', compact('groups', 'selectedGroup', 'items', 'categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:backpack_groups,id',
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
        ]);

        $group = auth()->user()->backpackGroups()->findOrFail($validated['group_id']);

        $group->items()->create([
            'user_id' => auth()->id(),
            'item_name' => $validated['name'],
            'category' => $validated['category'],
            'group_name' => $group->title,
        ]);

        return redirect()->route('backpack.index', ['group' => $group->id])
            ->with('success', 'Checklist item added.');
    }

    public function toggle(BackpackItem $item)
    {
        $this->authorizeItem($item);
        $item->update(['is_checked' => !$item->is_checked]);

        return back()->with('success', $item->is_checked ? 'Item marked packed.' : 'Item marked unpacked.');
    }

    public function destroy(BackpackItem $item)
    {
        $this->authorizeItem($item);
        $item->delete();

        return back()->with('success', 'Checklist item removed.');
    }

    public function clearChecked(Request $request)
    {
        $group = auth()->user()->backpackGroups()->findOrFail($request->get('group_id'));
        $group->items()->where('is_checked', true)->delete();

        return redirect()->route('backpack.index', ['group' => $group->id])
            ->with('success', 'Packed items cleared.');
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100',
            'group_id' => 'required|exists:backpack_groups,id',
        ]);

        return redirect()->route('backpack.index', ['group' => $validated['group_id']])
            ->with('success', 'Category "' . ucfirst($validated['category_name']) . '" is ready. Add an item using that category.');
    }

    public function addGroup(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $group = auth()->user()->backpackGroups()->create($validated);

        return redirect()->route('backpack.index', ['group' => $group->id])
            ->with('success', 'Checklist group created.');
    }

    public function updateGroup(Request $request, BackpackGroup $group)
    {
        $this->authorizeGroup($group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $group->update($validated);
        $group->items()->update(['group_name' => $group->title]);

        return redirect()->route('backpack.index', ['group' => $group->id])
            ->with('success', 'Checklist group updated.');
    }

    public function deleteCategory(Request $request, $category)
    {
        $group = auth()->user()->backpackGroups()->findOrFail($request->get('group_id'));
        $group->items()->where('category', $category)->delete();

        return redirect()->route('backpack.index', ['group' => $group->id])
            ->with('success', 'Category and its items deleted.');
    }

    public function deleteGroup(BackpackGroup $group)
    {
        $this->authorizeGroup($group);
        $group->delete();

        return redirect()->route('backpack.index')
            ->with('success', 'Checklist group deleted.');
    }

    private function authorizeItem(BackpackItem $item): void
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeGroup(BackpackGroup $group): void
    {
        if ($group->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
