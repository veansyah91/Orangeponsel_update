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
}
