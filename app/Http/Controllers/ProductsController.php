<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Library\CustomerHelper;
use App\Library\ProductHelper;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = ProductHelper::get_products();
        $carts = Cart::whereNull('order_id')->get();
        $customers = CustomerHelper::get_customers();
        return view('products.index', [
            'title' => 'Hello World',
            'products' => $products,
            'carts' => $carts,
            'customers' => $customers
        ]);
    }
}
