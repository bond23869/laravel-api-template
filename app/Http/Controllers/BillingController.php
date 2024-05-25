<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Subscription;
use Stripe\PaymentIntent;

class BillingController extends Controller
{
    public function createCustomer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'payment_method' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create([
            'email' => $request->email,
            'payment_method' => $request->payment_method,
            'invoice_settings' => ['default_payment_method' => $request->payment_method],
        ]);

        return response()->json($customer);
    }

    public function chargeCustomer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100, // amount in cents
            'currency' => 'usd',
            'customer' => $request->customer_id,
            'payment_method' => $request->payment_method,
            'off_session' => true,
            'confirm' => true,
        ]);

        return response()->json($paymentIntent);
    }

    public function createSubscription(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'price_id' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $subscription = Subscription::create([
            'customer' => $request->customer_id,
            'items' => [['price' => $request->price_id]],
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        return response()->json($subscription);
    }
}
