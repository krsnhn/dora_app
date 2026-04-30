<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Destination;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        // Get favorites with their destinations
        $favorites = auth()->user()
            ->favorites() // This uses the relationship you need to add to User model
            ->with(['destination' => function($query) {
                $query->approved()->withCount('tourPackages');
            }])
            ->paginate(12);
        
        return view('traveler.favorites.index', compact('favorites'));
    }

    public function toggle(Destination $destination)
    {
        $user = auth()->user();
        $existing = Favorite::where('user_id', $user->id)
            ->where('destination_id', $destination->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from favorites.';
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->id, 
                'destination_id' => $destination->id
            ]);
            $message = 'Added to favorites!';
            $favorited = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['favorited' => $favorited, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}