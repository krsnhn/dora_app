<?php

namespace App\Http\Controllers;

use App\Models\BackpackItem;
use Illuminate\Http\Request;

class BackpackController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    } */

    public function index(Request $request)
    {
        $selectedGroup = $request->get('group', 'default');
        
        $items = auth()->user()->backpackItems()
            ->where('group_name', $selectedGroup)
            ->orderBy('category')
            ->orderBy('created_at')
            ->get();
        
        $groups = auth()->user()->backpackItems()
            ->select('group_name')
            ->distinct()
            ->pluck('group_name')
            ->toArray();
        
        // Add 'default' to groups if not already present
        if (!in_array('default', $groups)) {
            $groups[] = 'default';
        }
        
        return view('traveler.backpack.index', compact('items', 'groups', 'selectedGroup'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'group_name' => 'nullable|string|max:255',
        ]);

        auth()->user()->backpackItems()->create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'group_name' => $validated['group_name'] ?? 'default',
        ]);

        return back()->with('success', 'Item added to your backpack!');
    }

    public function toggle(BackpackItem $item)
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $item->update(['is_checked' => !$item->is_checked]);

        return back()->with('success', $item->is_checked ? 'Item checked!' : 'Item unchecked.');
    }

    public function destroy(BackpackItem $item)
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Item removed from your backpack.');
    }

    public function clearChecked(Request $request)
    {
        $groupName = $request->get('group_name', 'default');
        
        auth()->user()->backpackItems()
            ->where('group_name', $groupName)
            ->where('is_checked', true)
            ->delete();

        return back()->with('success', 'All checked items have been removed.');
    }

    public function addCategory(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'group_name' => 'nullable|string|max:255',
        ]);
        
        // Categories are just strings stored with items, no separate table needed
        // Just return success message
        return back()->with('success', 'Category "' . ucfirst($validated['category_name']) . '" created. You can now add items to this category.');
    }

    public function addGroup(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:255',
            'travel_date' => 'nullable|date',
        ]);
        
        return redirect()->route('backpack.index', ['group' => $validated['group_name']])
            ->with('success', 'Trip "' . $validated['group_name'] . '" created! Start adding items.');
    }

    public function deleteCategory(Request $request, $category)
    {
        $groupName = $request->get('group_name', 'default');
        
        auth()->user()->backpackItems()
            ->where('group_name', $groupName)
            ->where('category', $category)
            ->delete();
        
        return back()->with('success', 'Category "' . ucfirst($category) . '" and all its items have been deleted.');
    }

    public function deleteGroup($group)
    {
        auth()->user()->backpackItems()
            ->where('group_name', $group)
            ->delete();
        
        return redirect()->route('backpack.index')
            ->with('success', 'Trip "' . $group . '" and all its items have been deleted.');
    }
}