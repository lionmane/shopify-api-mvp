<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartItem;
use App\User;
use App\Services\Shipping;
use App\Library\CustomerHelper;
use App\Library\ProductHelper;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function index()
    {
        $carts = Cart::query()->whereNull('order_id')->get();
        $user = new User;
            // Try and validate the address
            $validate = Shipping::validateAddress($user);

            // Make sure it's not an invalid address this
            // could also be moved to a custom validator rule
            if ($validate->object_state == 'INVALID') {
                return back()->withMessages($validate->messages);
            }
            $product = array(
                'length'=> '5',
                'width'=> '5',
                'height'=> '5',
                'distance_unit'=> 'in',
                'weight'=> '2',
                'mass_unit'=> 'lb',
            );
            $rates = Shipping::rates($user, $product);
        return view('carts.index', [
            'carts' => $carts,
            'rates' => $rates->rates
        ]);
    }

    public function create_cart(Request $request, $customer_id)
    {
        $customer = CustomerHelper::customer_by_id($customer_id);
        $cart = new Cart();
        $cart->customer_id = $customer['id'];
        $cart->customer_first_name = $customer['first_name'];
        $cart->customer_last_name = $customer['last_name'];
        $cart->customer_email = $customer['email'];
        $cart->save();

        return response()->json([
            'customer_id' => $customer_id,
            'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
            'customer_email' => $customer['email'],
            'cart_id' => $cart->id,
            'cart_name' => "$cart->customer_first_name $cart->customer_last_name [ " . \Carbon\Carbon::parse($cart->created_at)->toFormattedDateString() . " ]"
        ]);
    }

    public function add_item_to_cart(Request $request, $cart_id, $variant_id, $quantity)
    {
        try {
            $cart = Cart::findOrFail($cart_id);
            $cart_item = CartItem::query()
                ->where('cart_id', $cart_id)
                ->where('variant_id', $variant_id)
                ->first();
            if (!$cart_item) {
                $product = ProductHelper::product_by_variant_id($variant_id);
                $cart_item = new CartItem();
                $cart_item->cart_id = $cart->id;
                $cart_item->product_id = $product['id'];
                $cart_item->variant_id = $variant_id;
                $cart_item->quantity = 0; // Intentionally start at zero, so we can easily ADD quantity
                $cart_item->product_name = $product['name'];
                $cart_item->variant_name = $product['variant_name'];
                $cart_item->unit_price = $product['price'];
                $cart_item->total_price = 0;
                $cart_item->shipping = 0;
                $cart_item->tax = 0;
            }


            // Add the new quantity and update the item total
            $cart_item->quantity += $quantity;
            $cart_item->total_price = $cart_item->quantity * $cart_item->unit_price;

            // Save the cart item
            $cart_item->save();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $error) {
            return response($error->getMessage(), 500);
        }
    }

    public function get_cart_items(Request $request, $cart_id)
    {
        try {
            $cart = Cart::findOrFail($cart_id);
            return $cart->items;
        } catch (\Exception $error) {
            return response('ERROR: ' . $error->getMessage(), 500);
        }
    }

    public function get_cart_info(Request $request, $cart_id)
    {
        try {
            $cart = Cart::findOrFail($cart_id);
            $user = new User;
            
            $client = \TaxJar\Client::withApiKey($_ENV['TAXJAR_API_KEY']);
            $client->setApiConfig('headers', [
              'X-TJ-Expected-Response' => 422
            ]);
            $tax = $client->taxForOrder([
              'from_country' => 'US',
              'from_zip' => '10001',
              'from_state' => 'NY',
              'from_city' => 'New York',
              'from_street' => 'Hudson Yards',
              'to_country' => 'US',
              'to_zip' => '07306',
              'to_state' => 'NJ',
              'to_city' => 'Jersey City',
              'to_street' => '54 Journal Square Plaza',
              'amount' => $cart->items[0]->total_price,
              'shipping' => 10,
              'line_items' => [
                [
                  'quantity' => $cart->items[0]->quantity,
                  'unit_price' => $cart->items[0]->unit_price
                ]
              ]
            ]);
            return [
                'id' => $cart->id,
                'cart' => $cart->toArray(),
                'customer' => $cart->customer_first_name . ' ' . $cart->customer_last_name,
                'items' => $cart->items,
                'total' => $cart->total()
                
            ];
        } catch (\Exception $error) {
            return response('ERROR: ' . $error->getMessage(), 500);
        }
    }
}
