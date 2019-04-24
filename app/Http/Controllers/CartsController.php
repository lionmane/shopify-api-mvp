<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartItem;
use App\Library\CustomerHelper;
use App\Library\ProductHelper;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function index()
    {
        $carts = Cart::query()->whereNull('order_id')->get();
        return view('carts.index', [
            'carts' => $carts
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
