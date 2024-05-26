<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function privacyPolicy(){
        return view("front.policy.privacypolicy");
    }

    public function terms(){
        return view("front.policy.termsofuse");
    }

    public function refund(){
        return view("front.policy.refundpolicy");
    }

    public function grievance(){
        return view("front.policy.grievances");
    }
}
