<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InterOutletController extends Controller
{
    public function index()
    {
        return view('admin.inter-outlet.index');
    }

    public function detail($pihak1, $pihak2)
    {
        return view('admin.inter-outlet.detail', compact('pihak1','pihak2'));
    }
}
