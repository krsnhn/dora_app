<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Destination;
use Illuminate\Http\Request;

class TourPackageController extends Controller
{
   /* public function __construct()
    {
        $this->middleware(['auth', 'agency']);
    } */

    public function index(Request $request)
    {
        $query = auth()->user()->tourPackages()
            ->with('destination')
            ->where('status', '!=', 'deleted');

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('destination', function($destQuery) use ($search) {
                      $destQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('country', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Destination filter
        if ($destination = $request->get('destination')) {
            $query->where('destination_id', $destination);
        }

        // Price range filter
        if ($minPrice = $request->get('min_price')) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice = $request->get('max_price')) {
            $query->where('price', '<=', $maxPrice);
        }

        $packages = $query->latest()->paginate(15)->withQueryString();

        // Get unique destinations for filter dropdown
        $destinations = auth()->user()->tourPackages()
            ->with('destination')
            ->where('status', '!=', 'deleted')
            ->get()
            ->pluck('destination')
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('agency.packages.index', compact('packages', 'destinations'));
    }

    public function create()
    {
        // Check if agency is approved
        if (!auth()->user()->isApprovedAgency()) {
            return redirect()->route('agency.dashboard')
                ->with('error', 'Your agency account must be approved to create packages.');
        }
        $destinations = Destination::approved()->orderBy('name')->get();
        return view('agency.packages.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        // Check if agency is approved
        if (!auth()->user()->isApprovedAgency()) {
            return redirect()->route('agency.dashboard')
                ->with('error', 'Your agency account must be approved to create packages.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'destination_id' => 'required|exists:destinations,id',
            'price'          => 'required|numeric|min:0',
            'duration'       => 'required|integer|min:1',
            'description'    => 'required|string',
            'inclusions'     => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ]);

        $validated['agency_id'] = auth()->id();

        $package = TourPackage::create($validated);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Package created successfully.',
                'package' => $package
            ]);
        }

        return redirect()->route('agency.packages.index')->with('success', 'Package created.');
    }
    public function edit(TourPackage $package)
        {
            $this->authorizePackage($package);

            $destinations = \App\Models\Destination::where('is_approved', true)
                ->orderBy('name')
                ->get();

            return view('agency.packages.edit', compact('package', 'destinations'));
        }

       public function update(Request $request, TourPackage $package)
        {
            $this->authorizePackage($package);

            $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'destination_id' => 'required|exists:destinations,id',
                'price'          => 'required|numeric|min:0',
                'duration'       => 'required|integer|min:1',
                'description'    => 'required|string',
                'inclusions'     => 'nullable|string',
                'status'         => 'required|in:active,inactive',
            ]);

            $package->update($validated);

            return redirect()->route('agency.packages.index')->with('success', 'Package updated.');
        }

            
            public function destroy(TourPackage $package)
            {
                $this->authorizePackage($package);
                $package->update(['status' => 'deleted']);
                return back()->with('success', 'Package removed.');
            }

            private function authorizePackage(TourPackage $package): void
            {
                if ($package->agency_id !== auth()->id()) {
                    abort(403);
                }
            }
        }
    
