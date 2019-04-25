<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    return view('welcome');
});
Route::get('products', 'ProductsController@index');
Route::get('customers', 'CustomersController@index');
Route::get('draft_orders', 'DraftOrdersController@index');

// Cart
Route::get('carts', 'CartsController@index');
Route::post('cart/{customer_id}', 'CartsController@create_cart');
Route::post('cart/{cart_id}/product/{variant_id}/quantity/{quantity_id}', 'CartsController@add_item_to_cart');
Route::get('cart/{cart_id}/items', 'CartsController@get_cart_items');
Route::get('cart/{cart_id}/info', 'CartsController@get_cart_info');
Route::post('cart/{cart_id}/checkout', 'CheckoutController@checkout');
Route::post('charge', 'CheckoutController@charge');
Route::get('/payment/process', 'CheckoutController@braintreePaymentprocess')->name('payment.process');
//Route::post('cart/{customer_id}', function($customer_id) {
//    $customer = \App\Library\CustomerHelper::customer_by_id($customer_id);
//    $cart = new \App\Cart();
//    $cart->customer_id = $customer['id'];
//    $cart->customer_first_name = $customer['first_name'];
//    $cart->customer_last_name = $customer['last_name'];
//    $cart->customer_email = $customer['email'];
//    $cart->save();
//
//    return response()->json([
//        'customer_id' => $customer_id,
//        'customer_name' => $customer['first_name'] . ' ' . $customer['last_name'],
//        'customer_email' => $customer['email'],
//        'cart_id' => $cart->id,
//        'cart_name' => "$cart->customer_first_name $cart->customer_last_name [ " . \Carbon\Carbon::parse($cart->created_at)->toFormattedDateString() . " ]"
//    ]);
//});
//Route::post('cart/{cart_id}/product/{variant_id}/quantity/{quantity_id}', function($cart_id, $variant_id, $quantity) {
//    try {
//        $cart = \App\Cart::findOrFail($cart_id);
//        $cart_item = \App\CartItem::query()
//            ->where('cart_id', $cart_id)
//            ->where('variant_id', $variant_id)
//            ->first();
//        if (!$cart_item) {
//            $product = \App\Library\ProductHelper::product_by_variant_id($variant_id);
//            $cart_item = new \App\CartItem();
//            $cart_item->cart_id = $cart->id;
//            $cart_item->product_id = $product['id'];
//            $cart_item->variant_id = $variant_id;
//            $cart_item->quantity = 0; // Intentionally start at zero, so we can easily ADD quantity
//            $cart_item->product_name = $product['name'];
//            $cart_item->variant_name = $product['variant_name'];
//        }
//
//        // Add the new quantity
//        $cart_item->quantity += $quantity;
//
//        // Save the cart item
//        $cart_item->save();
//
//        return response()->json([
//            'status' => 'success'
//        ]);
//    } catch (\Exception $error) {
//        return response($error->getMessage(), 500);
//    }
//});
//Route::get('cart/{cart_id}/items', function($cart_id) {
//    try {
//        $cart = \App\Cart::findOrFail($cart_id);
//        return $cart->items;
//    } catch (\Exception $error) {
//        return response('ERROR: ' . $error->getMessage(), 500);
//    }
//});
//Route::post('cart/{cart_id}/checkout', function($cart_id) {
//    try {
//        $cart = \App\Cart::findOrFail($cart_id);
//        $order = \App\Library\DraftOrderHelper::create_order($cart->items, $cart->customer_id);
//    } catch (\Exception $error) {
//    }
//});