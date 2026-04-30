<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Favorite;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $query = Destination::approved()->withCount('tourPackages');

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($country = $request->get('country')) {
            $query->where('country', $country);
        }

        $destinations = $query->latest()->paginate(12)->withQueryString();
        $countries = Destination::approved()->distinct()->pluck('country')->sort()->values();

        return view('destinations.index', compact('destinations', 'countries'));
    }

    public function show(Destination $destination, Request $request)
    {
        if (!$destination->is_approved) {
            abort(404);
        }

        $destination->load(['tourPackages.agency', 'memories' => fn($q) => $q->latest()->take(6)]);
        $weather = null;

        if ($destination->weather_location || $destination->location) {
            $weather = $this->weatherService->getWeather(
                $destination->weather_location ?: $destination->location . ',' . $destination->country
            );
        }

        $isFavorited = auth()->check()
            ? Favorite::where('user_id', auth()->id())->where('destination_id', $destination->id)->exists()
            : false;

        $feedback = \App\Models\Feedback::with('user')
            ->where('status', 'approved')
            ->whereHas('package', fn($q) => $q->where('destination_id', $destination->id))
            ->latest()
            ->take(5)
            ->get();

        return view('destinations.show', compact('destination', 'weather', 'isFavorited', 'feedback'));
    }
}
