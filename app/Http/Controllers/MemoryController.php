<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\Destination;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MemoryController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    } */

    public function index(Request $request)
    {
        $query = auth()->user()->memories()->with('destination');
        
        // Search filter - matches destination page style
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('caption', 'like', "%{$search}%")
                  ->orWhereHas('destination', function($destQuery) use ($search) {
                      $destQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('country', 'like', "%{$search}%")
                               ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }
        
        // Year filter
        if ($request->filled('year')) {
            $query->whereYear('travel_date', $request->year);
        }
        
        // Sort by latest
        $memories = $query->latest()->paginate(15);
        
        // Get unique years for filter dropdown
        $years = auth()->user()->memories()
            ->whereNotNull('travel_date')
            ->selectRaw('DISTINCT YEAR(travel_date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $destinations = Destination::approved()->orderBy('name')->get();
        
        return view('traveler.memories.index', compact('memories', 'destinations', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
            'destination_id' => 'nullable|exists:destinations,id',
            'travel_date' => 'nullable|date',
        ]);

        // Upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                $uploaded = Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    ['folder' => 'dora/memories']
                );
                $imagePath = $uploaded->getSecurePath();
            } catch (\Exception $e) {
                \Log::error('Cloudinary upload failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to upload image. Please try again.');
            }
        } else {
            return back()->with('error', 'Please select an image to upload.');
        }

        Memory::create([
            'user_id' => auth()->id(),
            'destination_id' => $validated['destination_id'] ?? null,
            'image_path' => $imagePath,
            'caption' => $validated['caption'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
        ]);

        return redirect()->route('memories.index')->with('success', 'Memory added to your travel album!');
    }

    public function destroy(Memory $memory)
    {
        if ($memory->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Optional: Delete from Cloudinary
        if ($memory->image_path) {
            try {
                // Extract public ID from URL if needed
                // Cloudinary::destroy($publicId);
            } catch (\Exception $e) {
                \Log::error('Failed to delete from Cloudinary: ' . $e->getMessage());
            }
        }
        
        $memory->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Memory removed.']);
        }
        
        return redirect()->route('memories.index')->with('success', 'Memory removed.');
    }
}