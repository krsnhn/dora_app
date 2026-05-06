<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\MemoryAlbum;
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
        $user = auth()->user();
        $this->ensureDefaultAlbum($user->id);

        $albums = $user->memoryAlbums()
            ->withCount('memories')
            ->with('coverMemory')
            ->orderBy('name')
            ->get();

        $selectedAlbum = null;
        if ($request->filled('album')) {
            $selectedAlbum = $albums->firstWhere('id', (int) $request->album);
        }

        $query = $user->memories()->with(['destination', 'album']);
        
        // Search filter - matches destination page style
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('caption', 'like', "%{$search}%")
                  ->orWhereHas('album', function($albumQuery) use ($search) {
                      $albumQuery->where('name', 'like', "%{$search}%");
                  })
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

        if ($selectedAlbum) {
            $query->where('album_id', $selectedAlbum->id);
        }
        
        // Sort by latest
        $memories = $query->latest()->paginate(15);
        
        // Get unique years for filter dropdown
        $years = $user->memories()
            ->whereNotNull('travel_date')
            ->selectRaw('DISTINCT YEAR(travel_date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $destinations = Destination::approved()->orderBy('name')->get();
        
        return view('traveler.memories.index', compact('memories', 'destinations', 'years', 'albums', 'selectedAlbum'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
            'album_id' => 'nullable|exists:memory_albums,id',
            'album_name' => 'nullable|string|max:80',
            'destination_id' => 'nullable|exists:destinations,id',
            'travel_date' => 'nullable|date',
        ]);

        $album = $this->resolveAlbum($validated);
        $created = 0;

        if ($request->hasFile('images')) {
            try {
                foreach ($request->file('images') as $image) {
                    $uploaded = Cloudinary::upload(
                        $image->getRealPath(),
                        ['folder' => 'dora/memories/' . $album->id]
                    );

                    Memory::create([
                        'user_id' => auth()->id(),
                        'album_id' => $album->id,
                        'destination_id' => $validated['destination_id'] ?? null,
                        'image_path' => $uploaded->getSecurePath(),
                        'caption' => $validated['caption'] ?? null,
                        'travel_date' => $validated['travel_date'] ?? null,
                    ]);

                    $created++;
                }
            } catch (\Exception $e) {
                \Log::error('Cloudinary upload failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to upload image. Please try again.');
            }
        } else {
            return back()->with('error', 'Please select at least one image to upload.');
        }

        return redirect()
            ->route('memories.index', ['album' => $album->id])
            ->with('success', $created . ' ' . \Illuminate\Support\Str::plural('photo', $created) . ' added to ' . $album->name . '.');
    }

    protected function resolveAlbum(array $validated): MemoryAlbum
    {
        $userId = auth()->id();
        $albumName = trim($validated['album_name'] ?? '');

        if ($albumName !== '') {
            return MemoryAlbum::firstOrCreate(
                ['user_id' => $userId, 'name' => $albumName],
                ['description' => null]
            );
        }

        if (!empty($validated['album_id'])) {
            $album = MemoryAlbum::where('user_id', $userId)->find($validated['album_id']);
            if ($album) {
                return $album;
            }
        }

        return $this->ensureDefaultAlbum($userId);
    }

    protected function ensureDefaultAlbum(int $userId): MemoryAlbum
    {
        return MemoryAlbum::firstOrCreate(
            ['user_id' => $userId, 'name' => 'Recents'],
            ['description' => 'Photos not yet sorted into a custom album.']
        );
    }

    public function updateAlbum(Request $request, MemoryAlbum $album)
    {
        if ($album->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:80|unique:memory_albums,name,' . $album->id . ',id,user_id,' . auth()->id(),
        ]);

        $album->update([
            'name' => trim($validated['name']),
        ]);

        return redirect()
            ->route('memories.index', array_filter(['album' => $album->id]))
            ->with('success', 'Album renamed.');
    }

    public function destroyAlbum(MemoryAlbum $album)
    {
        if ($album->user_id !== auth()->id()) {
            abort(403);
        }

        $defaultAlbum = $this->ensureDefaultAlbum(auth()->id());

        if ($album->id === $defaultAlbum->id) {
            return redirect()->route('memories.index')
                ->with('error', 'The default album cannot be deleted.');
        }

        if ($album->memories()->exists()) {
            $album->memories()->update(['album_id' => $defaultAlbum->id]);
        }

        $album->delete();

        return redirect()->route('memories.index')
            ->with('success', 'Album deleted and memories moved to Recents.');
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
