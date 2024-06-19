<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    public function index()
    {
        $customer = Customer::all();
        // return response()->json($customer);
        return view('customer.index', compact('customer'));
    }

    public function store(Request $request)
    {
        $customer = Customer::create($request->all());

        return response()->json($customer);
    }
}
