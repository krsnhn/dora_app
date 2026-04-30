<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredDestinations = Destination::approved()
            ->withCount('tourPackages')
            ->latest()
            ->take(6)
            ->get();

        $totalDestinations = Destination::approved()->count();
        $totalPackages = TourPackage::active()->count();

        return view('home', compact('featuredDestinations', 'totalDestinations', 'totalPackages'));
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function about()
    {
        return view('pages.about');
    }
}
