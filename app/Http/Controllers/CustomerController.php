<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    //

    public function index(Request $request)
    {
        // $customer = Customer::where('name', 'like', '%' . $request->name . '%')->orWhere('email', 'like', '%' . $request->email . '%')->get();
        $filters = ['name', 'email', 'tags'];
        $customer = Customer::where(function ($query) use ($request, $filters) {
            foreach ($filters as $filter) {
                if ($request->has('search')) {
                    $query->orWhere($filter, 'like', '%' . $request->search . '%');
                }
            }
        })->paginate(10);
        // return response()->json($customer);
        return view('customer.index', compact('customer'));
    }

    public function store(Request $request)
    {
        $customer = Customer::create($request->all());

        return response()->json($customer);
    }


    public function downloadExcel()
    {
        return Excel::download(new CustomersExport, 'customer.xlsx');
    }
}
