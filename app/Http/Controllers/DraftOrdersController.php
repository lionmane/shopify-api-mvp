<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Library\CustomerHelper;
use App\Library\DraftOrderHelper;
use Illuminate\Http\Request;

class DraftOrdersController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => DraftOrderHelper::get_orders()
        ]);
    }

    public function create(Request $request, $cart_id)
    {
        try {
            $cart = Cart::find($cart_id);
            if ($cart->status != 'open') {
                throw new \Exception('This cart is already draft or checkout mode, it can no longer be checked out');
            }

            $name = $request->get('name');
            $notes = $request->get('notes');
            $response = DraftOrderHelper::create_order($cart, $name, $notes);
            $response = json_decode($response, true)['draft_order'];

            $cart->draft_order_id = $response['id'];
            $cart->drafted_at = \Carbon\Carbon::now();
            $cart->status = 'draft';
            $cart->save();

            return response()->json([
                'status' => 'ok',
                'message' => "Draft Order created successfully for Cart #$cart->id",
                'cart' => $cart->toArray(),
                'draft_order' => $response
            ]);
        } catch (\Exception $error) {
            response($error->getMessage(), 500);
        }
    }
}
