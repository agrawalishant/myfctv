<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\Series;
use App\Models\SeriesSeasonEpisode;

class SeriesController extends Controller
{
    public function allSeries()
    {
        Session::put('page', 'series');
        $series = Series::orderBy('id', 'desc')->get();
        return view('front.allseries', compact('series'));
    }

    public function show($id)
    {
        Session::put('page', 'series');

        // Eager load both 'seasons' and 'seasons.episodes' relationships
        $series = Series::with('seasons', 'seasons.episode', 'cast')->findOrFail($id);

        // Check if the user is logged in
        if (auth('user')->check()) {
            $user = auth('user')->user();

            // Check the 'access' field to determine if the series is restricted
            if ($series->content_type == 'restricted') {
                // Check if the user has an active subscription
                if ($user->hasActiveSubscription()) {
                    // Allow access to the series
                    return view('front.single_series', compact('series'));
                } else {
                    // Flash a subscription error message
                    return redirect('/')->with('subscriptionError', 'This premium content requires an active subscription.');
                }
            } elseif ($series->content_type == 'public') {
                // If the series is public, allow access
                return view('front.single_series', compact('series'));
            }
        } else {
            // Redirect the user to the login page with a flashed message
            return redirect()->route('login')->with('subscriptionError', 'Please log in to access this content.');
        }
    }


    public function episode($id)
    {
        Session::put('page', 'series');
        $episode = SeriesSeasonEpisode::find($id);
        return view('front.episode', compact('episode'));
    }
}
