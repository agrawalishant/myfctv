<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subscriptionPlan;
use App\Models\UserSubscription;
use App\Models\UserPayment;
use Razorpay\Api\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Auth;


class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $plans = SubscriptionPlan::where('status', 1)->get();
        if ($request->wantsJson() || $request->is('api/*')) {
            // API request
            return response()->json(['data' => ['plans' => $plans]], 200);
        } else {
            // Web request
            return view('profile', ['plans' => $plans]);
        }
    }


    // public function subscribe(Request $request)
    // {
    //     // Check if the user is authenticated
    //     if (!$user = auth()->user()) {
    //         return response()->json(['error' => 'User not authenticated'], 401);
    //     }

    //     if ($user->hasActiveSubscription()) {
    //         return response()->json(['error' => 'Cannot purchase a new plan while the current plan is active.'], 400);
    //     }

    //     // Retrieve the subscription plan
    //     $planId = $request->input('plan_id');
    //     try {
    //         $plan = SubscriptionPlan::findOrFail($planId);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Subscription plan not found.'], 404);
    //     }

    //     // Calculate the end date based on the subscription plan duration
    //     $endDate = now()->add($plan->duration_quantity, $plan->duration_unit);

    //     // Add the user to the user_subscriptions table without processing a payment
    //     try {
    //         UserSubscription::create([
    //             'user_id' => $user->id,
    //             'subscription_plan_id' => $plan->id,
    //             'start_date' => now(), // Automatically set to the current date
    //             'end_date' => $endDate, // Calculate the end date
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Error creating UserSubscription.'], 500);
    //     }

    //     return response()->json(['message' => 'User added to UserSubscription without processing payment']);
    // }

    public function subscribe(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        if ($user->hasActiveSubscription()) {
            return response()->json(['error' => 'Cannot purchase a new plan while the current plan is active.'], 400);
        }

        // Retrieve the subscription plan
        $planId = $request->input('plan_id');
        $plan = SubscriptionPlan::findOrFail($planId);

        // Initialize Razorpay API
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));


        // Create a Razorpay order
        $orderData = [
            'amount' => $plan->price * 100,
            'currency' => 'INR',
            'receipt' => 'order_' . uniqid(),
        ];

        $order = $api->order->create($orderData);

        // Return the order ID to the frontend
        $orderId = $order->id;

        return response()->json(['order_id' => $orderId]);
    }

    private function processRazorpayPayment($orderId, $paymentId, $user, $plan)
    {
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));



        // Capture the payment
        $payment = $api->payment->fetch($paymentId);
        $payment->capture(['amount' => $payment->amount]);

        // Return the Razorpay payment ID
        return $payment->id;
    }

    public function handleRazorpayPayment(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Retrieve the subscription plan based on your logic
        $planId = $request->input('plan_id');

        try {
            $plan = SubscriptionPlan::findOrFail($planId);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Subscription plan not found.'], 404);
        }

        // Handle Razorpay payment confirmation on the frontend
        $orderId = $request->input('order_id');
        $paymentId = $request->input('payment_id');

        // Check if payment was successful
        if ($paymentId) {
            // Call the processRazorpayPayment method to capture the payment
            $processedPaymentId = $this->processRazorpayPayment($orderId, $paymentId, $user, $plan);

            // Continue with subscription creation after successful payment
            $userSubscription = UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => now()->add($plan->duration_quantity, $plan->duration_unit),
                'payment_status' => 'success',
                'payment_id' => $processedPaymentId,
            ]);

            // Store payment details in user_payments table
            UserPayment::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => 'INR',
                'payment_method' => 'razorpay',
                'payment_status' => 'success',
                'payment_id' => $processedPaymentId,
            ]);

            return response()->json(['message' => 'Subscription and payment successful']);
        } else {
            // Handle the case where payment was not successful
            return response()->json(['error' => 'Payment failed'], 400);
        }
    }

    public function checkSubscriptionStatus(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->hasActiveSubscription()) {
            $subscription = $user->latestSubscription()->first();
            $remainingDays = now()->diffInDays($subscription->end_date);

            return response()->json([
                'status' => 'active',
                'remaining_days' => $remainingDays,
            ], 200);
        } else {
            return response()->json(['status' => 'inactive'], 200);
        }
    }
}
