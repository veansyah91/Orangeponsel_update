<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Customer;

class CustomerController extends Controller
{
    public function index(){
        return view('admin.master.customer.index');
    }

    public function getCustomer()
    {
        $customers = Customer::where('nama', 'like', '%' . request('pelanggan') . '%')->limit(5)->get();

        return response()->json([
            'status' => 'success',
            'data' => $customers
        ]);
    }
}
