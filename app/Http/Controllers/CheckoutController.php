<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Library\DraftOrderHelper;
use App\Order;
use App\Payment;
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

    public function charge(Request $request, $cart_id)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $cart = Cart::findOrFail($cart_id);
            if ($cart->status != 'open') {
                throw new \Exception('This cart is already draft or checkout mode, it can no longer be checked out');
            }

            // Create the Draft Order
            $name = $request->get('name');
            $notes = $request->get('notes');
            $response = DraftOrderHelper::create_order($cart, $name, $notes);
            dd($response);

            $customer = Customer::create(array(
                'email' => $request->email,
                'shipping' => array(
                    'name' => "Aakash Bansal",
                    'address' => array(
                        'line1' => "123 Yukon Ave.",
                        'line2' => "Suite B",
                        'city' => "Seattle",
                        'state' => "Washington",
                        'country' => "United States",
                        'postal_code' => "98144"
                    )
                ),
                'source' => $request->stripeToken
            ));

            // Charge the client
            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => floor($cart->total() * 100),
                'currency' => 'usd',
                'metadata["shipping_address"]' => $request->shipping_address
            ));

            if (!$charge->status == 'succeeded') {
                $message = "Payment failure: $charge->failure_message ($charge->failure_code)";
                throw new \Exception($message);
            }

            // Create the Draft Order
            $name = $request->get('name');
            $notes = $request->get('notes');
            $response = DraftOrderHelper::create_order($cart, $name, $notes);
            dd($response);
            $response = json_decode($response, true)['draft_order'];

            // Add draft order details to the cart
            $cart->draft_order_id = $response['id'];
            $cart->drafted_at = \Carbon\Carbon::now();
            $cart->status = 'draft';
            $cart->save();

            // Create the payment information
            $payment = new Payment();
            $payment->sub_total = 0;
            $payment->tax_total = 0;
            $payment->total = $charge->amount;
            $payment->payment_method_id = $charge->payment_method;
            if ($charge->source->object == 'card')
                $payment->payment_method_last_4 = $charge->source->last4;
            $payment->charge_id = $charge->id;
            $payment->transaction_id = $charge->balance_transaction;
            $payment->customer_id = $charge->customer;
            $payment->receipt_url = $charge->receipt_url;
            $payment->currency = $charge->currency;
            $payment->metadata = json_encode((array)$charge);
            $payment->cart_id = $cart_id;
            $payment->save();

            // Finally create the order information
            $order = new Order();
            $order->cart_id = $cart->id;
            $order->payment_id = $payment->id;
            $order->shopify_order_id = $response['id'];
            $order->status = 'pending';
            $order->save();

            return response()->json([
                'status' => 'ok',
                'message' => 'Charge was successful',
                'payment' => $payment->toArray(),
                'order' => $order->toArray()
            ]);

        } catch (\Exception $error) {
            return response($error->getTraceAsString(), 500);
        }
    }

    public function payments()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }
}
