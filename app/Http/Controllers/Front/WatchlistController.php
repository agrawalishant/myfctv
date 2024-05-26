<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Watchlist;
use Session;

class WatchlistController extends Controller
{
    public function add(Request $request)
    {
        $userId = auth('user')->user()->id;
        $movieId = $request->input('movie_id', null);
        $seriesId = $request->input('series_id', null);

        // Check if the user has already added the exact movie or series to the watchlist
        $existingEntry = Watchlist::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->where('series_id', $seriesId)
            ->first();

        if ($existingEntry) {
            // User already added this exact movie or series to the watchlist
            return response()->json(['status' => 0, 'message' => 'Already in Watchlist']);
        }

        // If not, add it to the watchlist
        Watchlist::create([
            'user_id' => $userId,
            'movie_id' => $movieId,
            'series_id' => $seriesId,
        ]);

        return response()->json(['status' => 1, 'message' => 'Added to Watchlist']);
    }


    public function view()
    {
        Session::put('page', 'watchlist');
        $user = auth('user')->user();
        $watchlist = $user->watchlist;
        return view('front.watchlist', compact('watchlist'));
    }

    public function remove($id)
    {
        $userId = auth('user')->user()->id;

        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$watchlist) {
            return response()->json(['status' => 0, 'message' => 'Watchlist item not found']);
        }

        $watchlist->delete();
        return response()->json(['status' => 1, 'message' => 'Item removed from Watchlist']);

    }
}
