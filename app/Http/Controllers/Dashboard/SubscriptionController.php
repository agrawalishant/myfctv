<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subscriptionPlan;
use App\Models\AdminRole;
use App\Models\UserPayment;
use Session;
use Auth;
use Validator;

class SubscriptionController extends Controller
{
    public function index()
    {
        Session::put('page', 'subscription');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $subscription = subscriptionPlan::all();
            $subscriptionModule['view_access'] = 1;
            $subscriptionModule['edit_access'] = 1;
            $subscriptionModule['full_access'] = 1;
            return view('dashboard.pages.subscription.index',compact('subscription','subscriptionModule'));
        }else{
            $subscription = subscriptionPlan::all();
            $subscriptionModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'subscription'])->count();

            if ($subscriptionModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $subscriptionModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'subscription'])->first()->toArray();
            } 
            return view('dashboard.pages.subscription.index',compact('subscription','subscriptionModule'));
        }
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required',
                'price' => 'required|numeric', // Example custom rule
                'duration_quantity' => 'required|integer|min:1',
                'duration_unit' => 'required',
            ], [
                'name.required' => 'The name field is required.',
                'price.required' => 'The price field is required.',
                'price.numeric' => 'The price must be a number.',
                'duration_quantity.required' => 'The duration quantity field is required.',
                'duration_quantity.integer' => 'The duration quantity must be an integer.',
                'duration_quantity.min' => 'The duration quantity must be at least 1.',
                'duration_unit.required' => 'The duration unit field is required.',
            ]);

            $validator->sometimes('price', 'regex:/^\d+(\.\d{1,2})?$/', function ($input) {
                return $input->duration_unit == 'hourly';
            });

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $subscription = new subscriptionPlan;
            $subscription->name = $data['name'];
            $subscription->price = $data['price'];
            $subscription->duration_unit = $data['duration_unit'];
            $subscription->duration_quantity = $data['duration_quantity'];
            $subscription->status = $data['status'];
            $subscription->save();

            return response()->json(['status' => 1, 'message' => 'Subscription Plan Added Successfully']);
        }
    }

    public function edit(Request $request, $id){
        $subscription = subscriptionPlan::find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required',
                'price' => 'required|numeric', // Example custom rule
                'duration_quantity' => 'required|integer|min:1',
                'duration_unit' => 'required',
            ], [
                'name.required' => 'The name field is required.',
                'price.required' => 'The price field is required.',
                'price.numeric' => 'The price must be a number.',
                'duration_quantity.required' => 'The duration quantity field is required.',
                'duration_quantity.integer' => 'The duration quantity must be an integer.',
                'duration_quantity.min' => 'The duration quantity must be at least 1.',
                'duration_unit.required' => 'The duration unit field is required.',
            ]);

            $validator->sometimes('price', 'regex:/^\d+(\.\d{1,2})?$/', function ($input) {
                return $input->duration_unit == 'hourly';
            });

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            }

            $subscription->name = $data['name'];
            $subscription->price = $data['price'];
            $subscription->duration_unit = $data['duration_unit'];
            $subscription->duration_quantity = $data['duration_quantity'];
            $subscription->status = $data['status'];
            $subscription->save();

            return response()->json(['status' => 1, 'message' => 'Subscription plan updated successfully']);
        }
    }

    public function delete($id){
        $subscription = subscriptionPlan::find($id);

        if (!$subscription) {
            return response()->json(['status' => false, 'message' => 'Subscription not found']);
        }
        $subscription->delete();
        return response()->json(['status' => true]); 
    }

    public function transaction(){
        Session::put('page', 'transaction');
        if (Auth::guard('admin')->user()->type == "super-admin") {
            $userPayments = UserPayment::with('user')->get();
            $transactionModule['view_access'] = 1;
            return view('dashboard.pages.subscription.transaction',compact('userPayments','transactionModule'));
        }else{
            $userPayments = UserPayment::with('user')->get();
            $transactionModuleCount = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'transaction'])->count();

            if ($transactionModuleCount == 0) {
                $message = "This Feature is restricted for you!";
                Session::flash('error_message', $message);
                return redirect('/dashboard');
            }else{
                $transactionModule = AdminRole::where(['admin_id' => Auth::guard('admin')->user()->id, 'module' => 'transaction'])->first()->toArray();
            } 
            return view('dashboard.pages.subscription.transaction',compact('userPayments'));
        }
    }
}
