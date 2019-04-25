<?php

namespace App\Http\Controllers;

use App\Library\CustomerHelper;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Braintree_Transaction;
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
            'shipping'=>array(
		      'name'=>"Aakash Bansal",
		      'address'=> array(
		        'line1'=> "123 Yukon Ave.",
		        'line2'=> "Suite B",
		        'city'=> "Seattle",
		        'state'=> "Washington",
		        'country'=>"United States",
		        'postal_code'=> "98144"
		      )
		    ),
            'source'  => $request->stripeToken
        ));

        $charge = Charge::create(array(
            'customer' => $customer->id,
            'amount'   => 1999,
            'currency' => 'usd',
            'metadata["shipping_address"]'=>$request->shipping_address
            
        ));

        return 'Charge successful';
    }

    public function braintreePaymentprocess(Request $request){
    	$payload = $request->input('payload', false);
    	$nonce = $payload['nonce'];
	    $status = Braintree_Transaction::sale([
		'amount' => '10.00',
		'paymentMethodNonce' => $nonce,
		'customer' => [
		    'firstName' => $request->formdata['first_name'],
		    'lastName' => $request->formdata['last_name'],
		    'email' => $request->formdata['email']
		  ],
		  'shipping' => [
		    'firstName' => $request->formdata['first_name'],
		    'lastName' => $request->formdata['last_name'],
		    'company' => 'conservatory',
		    'streetAddress' => '1 E 1st St',
		    'extendedAddress' => 'Suite 403',
		    'locality' => 'Bartlett',
		    'region' => 'IL',
		    'postalCode' => '60103',
		    'countryCodeAlpha2' => 'US'
		  ],
		'options' => [
		    'submitForSettlement' => true,
		    'storeInVaultOnSuccess'=>true
		]
	    ]);

	    return response()->json($status);
	    }
}
