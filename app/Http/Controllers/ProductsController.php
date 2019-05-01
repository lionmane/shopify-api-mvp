<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Library\CustomerHelper;
use App\Library\ProductHelper;
use App\Product;
use App\ProductVariant;
use App\Vendor;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products_db = ProductVariant::select(['vendor_id', 'metadata'])->get();
        $products = [];
        $vendors = [];
        foreach ($products_db as $product) {
            if (!array_key_exists($product->vendor_id, $vendors)) {
                $vendors[$product->vendor_id] = $product->vendor;
            }
            $data = json_decode($product->metadata, true);
            $data['vendor'] = $vendors[$product->vendor_id]->display_name;
            $products[] = $data;
        }
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
