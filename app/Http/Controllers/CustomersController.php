<?php

namespace App\Http\Controllers;

use App\Library\CustomerHelper;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        return view('customers.index', [
            'customers' => CustomerHelper::get_customers()
        ]);
    }
}
