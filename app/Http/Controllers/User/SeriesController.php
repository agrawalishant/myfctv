<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Series;

class SeriesController extends Controller
{
    public function index(Request $request)
    {
        $series = Series::with('seasons.episode', 'cast')->get();
        

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['moviestype' => 'webseries', 'series' => $series], 200);
        } else {
            // Web request
            return view('profile', ['series' => $series]);
        }
    }

    public function show(Request $request, $id)
    {
        $seriesdata = Series::with('seasons.episode', 'cast')->find($id);

        if (!$seriesdata) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Series not found'], 404);
            }
            abort(404, 'Series not found');
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['seriesdata' => $seriesdata]], 200);
        } else {
            // Web request
            return view('profile', ['seriesdata' => $seriesdata]);
        }
    }

}
