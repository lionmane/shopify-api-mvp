<?php

namespace App\Http\Controllers;

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
}
