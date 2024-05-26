<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MovieCategory;
use App\Models\Movie;
use Illuminate\Http\Request;
use Auth;

class MovieController extends Controller
{
    public function category(Request $request)
    {
        $category = MovieCategory::get();

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['category' => $category]], 200);
        } else {
            // Web request
            return view('profile', ['category' => $category]);
        }
    }

    public function moviesUnderCategory(Request $request, $categoryId)
    {
        $viewcategory = MovieCategory::with('movies')->find($categoryId);

        if (!$viewcategory) {
            // Handle case when category is not found
            abort(404, 'Category not found');
        }
        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['viewcategory' => $viewcategory]], 200);
        } else {
            // Web request
            return view('profile', ['viewcategory' => $viewcategory]);
        }
    }

    public function viewMovieDetails(Request $request, $categoryId, $movieId)
    {
        // Retrieve details of the specific movie under the specified category
        $movie = Movie::with('videoVersions', 'castAndCrew')->find($movieId);

        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['movie' => $movie]], 200);
        } else {
            // Web request
            return view('profile', ['movie' => $movie]);
        }
    }

    // public function allData(Request $request)
    // {
    //     $categories = MovieCategory::with('movies', 'series')->get();

    //     // Check if the user is authenticated and has an active subscription
    //     $user = Auth::user();

    //     if ($user && $user->hasActiveSubscription()) {
    //         // User has an active subscription, no need to filter premium content
    //         $data = ['categories' => $categories];
    //     } else {
    //         // Filter out premium content
    //         $filteredCategories = $categories->map(function ($category) {
    //             $category->movies = $category->movies->where('access', '!=', 'premium');
    //             $category->series = $category->series->where('access', '!=', 'premium');
    //             return $category;
    //         });

    //         $data = ['categories' => $filteredCategories];
    //     }

    //     if ($request->wantsJson() || $request->is('api/*')) {
    //         // API request
    //         return response()->json(['data' => $data], 200);
    //     } else {
    //         // Web request
    //         return view('profile', $data);
    //     }
    // }

    public function allData(Request $request)
    {
        $categories = MovieCategory::with('movies.videoVersions', 'series')->get();

        // Allow access to all content, but flag restricted content for logged-out users
        $data = $categories->map(function ($category) {
            $category->movies = $category->movies->map(function ($movie) {
                // Add a flag indicating if the user needs to log in or subscribe
                $movie->login_or_subscribe = $movie->access === 'restricted';
                return $movie;
            });

            $category->series = $category->series->map(function ($series) {
                // Add a flag indicating if the user needs to log in or subscribe
                $series->login_or_subscribe = $series->access === 'restricted';
                return $series;
            });

            return $category;
        });

        $data = ['categories' => $data];

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => $data], 200);
        } else {
            // Web request
            return view('profile', $data);
        }
    }
}
