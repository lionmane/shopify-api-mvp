<?php

use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cart = new \App\Cart();
        $cart->customer_id = '1763158818880';
        $cart->customer_first_name = 'Adam';
        $cart->customer_last_name = 'Katz';
        $cart->customer_email = 'natasha.kuperman@gmail.com';
        $cart->save();

        $products = [
            '28013130580032' => 1,
            '28038792183872' => 2,
            '28084649951296' => 2
        ];

        foreach ($products as $id => $quantity) {
            $cart_item = new \App\CartItem();
            $cart_item->cart_id = $cart->id;
            $cart_item->variant_id = $id;
            $cart_item->quantity = $quantity;

            $product = \App\Library\ProductHelper::product_by_variant_id($id);
            $cart_item->product_id = $product['product_id'];
            $cart_item->product_name = $product['name'];
            $cart_item->variant_name = $product['variant_name'];
            $cart_item->unit_price = $product['price'];
            $cart_item->total_price = $product['price'] * $quantity;
            $cart_item->save();
        }
    }
}
