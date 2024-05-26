<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::with(['movie.videoVersions', 'series.seasons.episode'])->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['sliders' => $sliders]], 200);
        } else {
            // Web request
            return view('profile', ['sliders' => $sliders]);
        }
    }
}
