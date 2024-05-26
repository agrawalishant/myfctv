<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Watchlist;
use Auth;
use Illuminate\Http\JsonResponse;

class WatchlistController extends Controller
{
    public function addToWatchlist(Request $request, $type, $itemId)
    {
        $userId = $request->user()->id;

        // Determine the appropriate column based on the item type
        $columnName = ($type === 'movie') ? 'movie_id' : 'series_season_episode_id';

        // Check if the item is already in the watchlist
        $existingWatchlistItem = Watchlist::where('user_id', $userId)
            ->where($columnName, $itemId)
            ->first();

        if ($existingWatchlistItem) {
            $typeName = ucfirst($type);
            return $this->isApiRequest($request)
                ? response()->json(['message' => "{$typeName} is already in the watchlist."], 400)
                : redirect('/')->with('error', "{$typeName} is already in the watchlist.");
        }

        // Create a new watchlist item
        $watchlistItem = Watchlist::create([
            'user_id' => $userId,
            $columnName => $itemId,
        ]);

        $typeName = ucfirst($type);

        $responseData = [
            'message' => "{$typeName} added to watchlist.",
            'watchlist_item' => $watchlistItem,
        ];

        return $this->isApiRequest($request)
            ? new JsonResponse($responseData, 201)
            : redirect('/')->with('success', $responseData['message']);
    }

    public function getWatchlist(Request $request)
    {
        // Ensure the user is authenticated
        if ($request->user()) {
            $userId = $request->user()->id;

            $watchlist = Watchlist::where('user_id', $userId)
                ->with(['movie', 'seriesSeasonEpisode'])
                ->get();

            return response()->json(['watchlist' => $watchlist]);
        }

        // Handle the case where the user is not authenticated
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function removeFromWatchlist(Request $request, $id)
    {
        // Ensure the user is authenticated
        if ($request->user()) {
            $userId = $request->user()->id;

            // Find the watchlist item by ID and user ID
            $watchlistItem = Watchlist::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            // Check if the watchlist item exists
            if ($watchlistItem) {
                // Delete the watchlist item
                $watchlistItem->delete();

                return response()->json(['message' => 'Item removed from watchlist successfully']);
            } else {
                return response()->json(['error' => 'Watchlist item not found'], 404);
            }
        }

        // Handle the case where the user is not authenticated
        return response()->json(['error' => 'Unauthorized'], 401);
    }



    private function isApiRequest(Request $request)
    {
        return $request->wantsJson() || $request->is('api/*');
    }

}
