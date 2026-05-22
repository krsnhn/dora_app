<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationRequest;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DestinationController extends Controller
{
    protected CloudinaryService $cloudinary;

    /*public function __construct(CloudinaryService $cloudinary)
    {
        $this->middleware(['auth', 'admin']);
        $this->cloudinary = $cloudinary;
    }*/

    public function index(Request $request)
    {
        $query = Destination::with('creator')->withCount('tourPackages');

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($request->filled('approved')) {
            $query->where('is_approved', (bool) $request->get('approved'));
        }

        $destinations = $query->latest()->paginate(20)->withQueryString();
        $pendingRequests = DestinationRequest::with('agency')->where('status', 'pending')->latest()->get();
        return view('admin.destinations.index', compact('destinations', 'pendingRequests'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }



public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'country'     => 'required|string|max:255',
        'location'    => 'nullable|string|max:255',
        'description' => 'required|string',
        'tags'        => 'nullable|string',
        'latitude'    => 'nullable|numeric',
        'longitude'   => 'nullable|numeric',
        'weather_location' => 'nullable|string|max:255',
        'image'       => 'nullable|image|max:5120',
    ]);

    if ($request->hasFile('image')) {
        $uploaded = Cloudinary::upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'dora/destinations']
        );
        $validated['image_url'] = $uploaded->getSecurePath();
    }

    unset($validated['image']);
    $validated['is_approved'] = true;
    $validated['created_by']  = auth()->id();

    Destination::create($validated);

    return redirect()->route('admin.destinations.index')->with('success', 'Destination created.');
}

public function update(Request $request, Destination $destination)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'country'     => 'required|string|max:255',
        'location'    => 'nullable|string|max:255',
        'description' => 'required|string',
        'tags'        => 'nullable|string',
        'latitude'    => 'nullable|numeric',
        'longitude'   => 'nullable|numeric',
        'weather_location' => 'nullable|string|max:255',
        'image'       => 'nullable|image|max:5120',
    ]);

    if ($request->hasFile('image')) {
        $uploaded = Cloudinary::upload(
            $request->file('image')->getRealPath(),
            ['folder' => 'dora/destinations']
        );
        $validated['image_url'] = $uploaded->getSecurePath();
    }

    unset($validated['image']);
    $destination->update($validated);

    return redirect()->route('admin.destinations.index')->with('success', 'Destination updated.');
}

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function toggleApproval(Destination $destination)
    {
        $destination->update(['is_approved' => !$destination->is_approved]);
        $status = $destination->fresh()->is_approved ? 'approved' : 'unapproved';
        return back()->with('success', "Destination {$status}.");
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted.');
    }

    public function reviewRequest(Request $request, DestinationRequest $destinationRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'approve') {
            Destination::create([
                'name' => $destinationRequest->name,
                'country' => $destinationRequest->country,
                'location' => $destinationRequest->location,
                'description' => $destinationRequest->description,
                'image_path' => $destinationRequest->image_path,
                'image_url' => $destinationRequest->image_url,
                'tags' => $destinationRequest->tags,
                'latitude' => $destinationRequest->latitude,
                'longitude' => $destinationRequest->longitude,
                'weather_location' => $destinationRequest->weather_location,
                'is_approved' => true,
                'created_by' => $destinationRequest->agency_id,
            ]);
        }

        $destinationRequest->update([
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'admin_notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Destination request reviewed.');
    }
}
