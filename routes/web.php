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
Route::post('cart/{cart_id}/checkout', 'DraftOrdersController@create');
