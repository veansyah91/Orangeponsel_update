<?php

namespace App\Http\Controllers\Admin;

use App\Model\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index(){
        return view('admin.master.supplier.index');
    }

    public function getSupplier()
    {
        $supplier = Supplier::where('nama', 'like', '%' . request('supplier') . '%')->limit(5)->get();

        return response()->json([
            'status' => 'success',
            'data' => $supplier
        ]);
    }
}
