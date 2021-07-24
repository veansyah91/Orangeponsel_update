<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(){
        return view('admin.stock.index');
    }

    public function balance()
    {
        return view('admin.stock.balance');
    }
}
