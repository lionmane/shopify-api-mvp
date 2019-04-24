<?php

namespace App\Http\Controllers;

use App\Library\CustomerHelper;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
class CheckoutController extends Controller
{
    public function checkout(Request $request, $cart_id)
    {
        // Initiate checkout logic here
    }
    public function charge(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = Customer::create(array(
            'email' => $request->email,
            'source'  => $request->stripeToken
        ));

        $charge = Charge::create(array(
            'customer' => $customer->id,
            'amount'   => 1999,
            'currency' => 'usd',
            'metadata[shipping_address]'=>$request->shipping_address
        ));
        return 'Charge successful';
    }

}
