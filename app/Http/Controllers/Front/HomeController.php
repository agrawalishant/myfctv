<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\MovieCategory;
use App\Models\Movie;
use Session;

class HomeController extends Controller
{
    public function index()
    {
        Session::put('page','home');
        $sliders = Slider::with('movie', 'series')->get();
        // dd($slider->toArray());

        $categories = MovieCategory::all();
        $moviesWithCategory = Movie::with('category')->orderBy('id', 'desc')->get();
        // dd($moviesWithCategory);
        

        return view('front.index', compact('sliders', 'moviesWithCategory', 'categories'));
    }

}
