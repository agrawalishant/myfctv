<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ComingSoon;
use Illuminate\Http\Request;

class ComingSoonController extends Controller
{
    public function allData(Request $request){
        $coming = ComingSoon::get();

        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['coming-soon' => $coming]], 200);
        } else {
            // Web request
            return view('profile', ['coming-soon' => $coming]);
        }
    }
}
