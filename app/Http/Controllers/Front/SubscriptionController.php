<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subscriptionPlan;
use App\Models\UserSubscription;
use App\Models\UserPayment;
use Razorpay\Api\Api;
use Session;

class SubscriptionController extends Controller
{
    public function show()
    {
        Session::put('page', 'subscription-plan');
        $subscription = subscriptionPlan::where('status', 1)->get();
        return view('front.subscription', compact('subscription'));
    }

    public function subscribe(Request $request)
    {
        $user = auth('user')->user();

        if (!$user) {
            return response()->json(['error' => 'You need to Login first!'], 401);
        }
    
        if ($user->hasActiveSubscription()) {
            return response()->json(['error' => 'User already has an active subscription.'], 400);
        }

        $planId = $request->input('plan_id');
        $plan = SubscriptionPlan::findOrFail($planId);

        // Initialize Razorpay API
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        // Create a Razorpay order
        $orderData = [
            'amount' => $plan->price * 100, // Convert to paisa/cents
            'currency' => 'INR',
            'receipt' => 'order_' . uniqid(),
        ];

        // Log the order data for debugging
        \Log::info('Razorpay Order Data:', ['order_data' => $orderData]);

        try {
            $order = $api->order->create($orderData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create Razorpay order'], 500);
        }

        // Log the response for debugging
        \Log::info('Razorpay Order Response:', ['order' => $order->toArray()]);

        // Return the order ID to the frontend
        $orderId = $order->id;

        return response()->json(['order_id' => $orderId]);
    }


    private function processRazorpayPayment($orderId, $paymentId, $user, $plan)
    {
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        // Fetch the payment
        $payment = $api->payment->fetch($paymentId);

        // Check if the payment has already been captured
        if ($payment->captured) {
            // Log a message or handle the case where the payment has already been captured
            \Log::warning('Payment already captured', ['payment_id' => $paymentId]);
            return $payment->id; // Return the existing payment ID
        }

        try {
            // Capture the payment
            $payment->capture(['amount' => $payment->amount]);

            // Return the Razorpay payment ID
            return $payment->id;
        } catch (\Exception $e) {
            // Log the error and handle it accordingly
            \Log::error('Error capturing payment', ['payment_id' => $paymentId, 'error' => $e->getMessage()]);
            // You might want to return an error response or throw an exception here
            throw new \Exception('Error capturing payment');
        }
    }


    public function handleRazorpayPayment(Request $request)
    {
        $user = auth('user')->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $planId = $request->input('plan_id');

        try {
            $plan = SubscriptionPlan::findOrFail($planId);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Subscription plan not found.'], 404);
        }

        $orderId = $request->input('order_id');
        $paymentId = $request->input('payment_id');

        if ($paymentId) {
            $processedPaymentId = $this->processRazorpayPayment($orderId, $paymentId, $user, $plan);

            $userSubscription = UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => now()->add($plan->duration_quantity, $plan->duration_unit),
                'payment_status' => 'success',
                'payment_id' => $processedPaymentId,
            ]);

            $userPayment = UserPayment::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => 'INR',
                'payment_method' => 'razorpay',
                'payment_status' => 'success',
                'payment_id' => $processedPaymentId,
            ]);

            // \Log::info('Processed Payment ID:', ['processed_payment_id' => $processedPaymentId]);
            // \Log::info('User Subscription:', ['user_subscription' => $userSubscription]);
            // \Log::info('User Payment:', ['user_payment' => $userPayment]);

            return response()->json(['message' => 'Subscription and payment successful']);
        } else {
            return response()->json(['error' => 'Payment failed'], 400);
        }
    }

    public function checkSubscriptionStatus(Request $request)
    {
        $user = auth('user')->user();

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
