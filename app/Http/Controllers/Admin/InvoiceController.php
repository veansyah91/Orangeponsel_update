<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.daily.invoice.index');
    }

    public function balance()
    {
        return view('admin.daily.invoice.balance');
    }
}
