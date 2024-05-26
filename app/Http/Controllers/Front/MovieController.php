<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\MovieCategory;
use Session;

class MovieController extends Controller
{
    public function show($id)
    {
        Session::put('page', 'movies');
        $movie = Movie::with('videoVersions', 'castAndCrew')->findOrFail($id);

        // Assuming your Backblaze B2 base URL
        $backblazeBaseUrl = 'https://Dreamzz.s3.us-east-005.backblazeb2.com';

        // Construct the full URL using the base URL and trailer filepath
        $backblazeNativeUrl = $backblazeBaseUrl . '/' . $movie->trailer;

        // Check if the user is logged in
        if (auth('user')->check()) {
            $user = auth('user')->user();

            // Check the 'access' field to determine if the movie is restricted
            if ($movie->access == 'restricted') {
                // Check if the user has an active subscription
                if ($user->hasActiveSubscription()) {
                    // Allow access to the movie
                    $relatedMovies = Movie::where('category_id', $movie->category_id)
                        ->where('id', '!=', $movie->id)
                        ->limit(4)
                        ->get();

                    return view('front.single_movie', compact('movie', 'backblazeNativeUrl', 'relatedMovies'));
                } else {
                    // Flash a subscription error message
                    return redirect('/')->with('subscriptionError', 'This premium content requires an active subscription.');
                }
            } elseif ($movie->access == 'public') {
                // If the movie is public, allow access
                $relatedMovies = Movie::where('category_id', $movie->category_id)
                    ->where('id', '!=', $movie->id)
                    ->limit(4)
                    ->get();

                return view('front.single_movie', compact('movie', 'backblazeNativeUrl', 'relatedMovies'));
            }
        } else {
            // Redirect the user to the login page with a flashed message
            return redirect()->route('login')->with('subscriptionError', 'Please log in to access this content.');
        }
    }






    public function moviesByCategory($categoryId)
    {
        Session::put('page', 'movies');
        $category = MovieCategory::find($categoryId);

        if (!$category) {
            abort(404); // Category not found
        }

        $movies = Movie::where('category_id', $categoryId)
            ->orderBy('id', 'desc')
            ->get();

        return view('front.category-wise', compact('category', 'movies'));
    }

    public function allMovie()
    {
        Session::put('page', 'movies');
        $movies = Movie::orderBy('id', 'desc')->get();

        return view('front.allmovie', compact('movies'));
    }
}
