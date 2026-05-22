<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        abort_unless($user && ($user->isAdmin() || $user->isTraveler()), 403);

        $query = trim((string) $request->get('q', ''));
        $isAdmin = $user->isAdmin();

        $destinations = collect();
        $packages = collect();
        $agencies = collect();

        if ($query !== '') {
            $destinations = Destination::query()
                ->when(!$isAdmin, fn ($destinationQuery) => $destinationQuery->approved())
                ->withCount([
                    'tourPackages' => fn ($tourPackageQuery) => $isAdmin
                        ? $tourPackageQuery
                        : $tourPackageQuery->publiclyListed(),
                ])
                ->search($query)
                ->latest()
                ->limit(8)
                ->get();

            $packages = TourPackage::query()
                ->with(['agency', 'destination'])
                ->when($isAdmin, fn ($packageQuery) => $packageQuery->where('status', '!=', 'deleted'))
                ->when(!$isAdmin, fn ($packageQuery) => $packageQuery->publiclyListed())
                ->when(!$isAdmin, fn ($packageQuery) => $packageQuery->whereHas('destination', fn ($destinationQuery) => $destinationQuery->approved()))
                ->where(function ($packageQuery) use ($query) {
                    $packageQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('duration', 'like', "%{$query}%")
                        ->orWhere('inclusions', 'like', "%{$query}%")
                        ->orWhereHas('destination', function ($destinationQuery) use ($query) {
                            $destinationQuery->search($query);
                        })
                        ->orWhereHas('agency', function ($agencyQuery) use ($query) {
                            $agencyQuery->where('name', 'like', "%{$query}%")
                                ->orWhere('business_name', 'like', "%{$query}%");
                        });
                })
                ->latest()
                ->limit(8)
                ->get();

            $agencies = User::query()
                ->where('role', 'agency')
                ->when(!$isAdmin, function ($agencyQuery) {
                    $agencyQuery->where('agency_status', 'approved')
                        ->where('status', 'active');
                })
                ->where(function ($agencyQuery) use ($query) {
                    $agencyQuery->where('name', 'like', "%{$query}%")
                        ->orWhere('business_name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('address', 'like', "%{$query}%")
                        ->orWhereHas('tourPackages', function ($packageQuery) use ($query) {
                            $packageQuery->where('name', 'like', "%{$query}%");
                        });
                })
                ->withCount([
                    'tourPackages as listed_packages_count' => fn ($packageQuery) => $isAdmin
                        ? $packageQuery
                        : $packageQuery->publiclyListed(),
                ])
                ->latest()
                ->limit(8)
                ->get();
        }

        return view('search.index', compact('query', 'destinations', 'packages', 'agencies', 'isAdmin'));
    }
}
