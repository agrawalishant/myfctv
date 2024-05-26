<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ComingSoon;
use Illuminate\Http\Request;
use Session;

class ComingsoonController extends Controller
{
    public function index(){
        Session::put('page','coming-soon');
        $coming = ComingSoon::orderBy('id', 'desc')->get();
        return view('front.coming-soon',compact('coming'));
    }

    public function single($id){
        Session::put('page','coming-soon');
        $coming = ComingSoon::findOrFail($id);
        return view('front.single-coming',compact('coming'));
    }
}
