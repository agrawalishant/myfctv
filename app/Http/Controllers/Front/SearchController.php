<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Series;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $movies = Movie::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        $series = Series::where('title', 'like', '%' . $query . '%')
            ->orWhere('short_description', 'like', '%' . $query . '%')
            ->get();

        return view('front.search', compact('movies', 'series', 'query'));
    }
}
