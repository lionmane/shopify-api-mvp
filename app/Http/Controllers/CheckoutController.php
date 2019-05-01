<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Library\DraftOrderHelper;
use App\Order;
use App\Payment;
use App\ShopifyOrder;
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

            $name = $request->get('name');
            $notes = $request->get('notes');
            $response = DraftOrderHelper::create_order($cart, $name, $notes);
            $draft_orders = [];
            foreach ($response as $vendor_id => $order) {
                $shopify_order = new ShopifyOrder();
                $shopify_order->vendor_id = $vendor_id;
                $shopify_order->shopify_id = $order['draft_order']['id'];
                $shopify_order->cart_id = $cart->id;
                $shopify_order->url = '';
                $shopify_order->save();
                $draft_orders[] = $shopify_order->toArray();
            }

            // Add draft order details to the cart
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
            $order->shopify_order_id = 0;
            $order->status = 'pending';
            $order->save();

            return response()->json([
                'status' => 'ok',
                'message' => 'Charge was successful',
                'payment' => $payment->toArray(),
                'order' => $order->toArray(),
                'draft_orders' => $draft_orders
            ]);

        } catch (\Exception $error) {
            return response([
                'trace' => $error->getTraceAsString(),
                'message' => $error->getMessage()
            ], 500, ['content-type' => 'application/json']);
        }
    }

    public function create_draft_orders()
    {

    }

    public function payments()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }
}
