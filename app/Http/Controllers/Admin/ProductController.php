<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(){
        return view('admin.master.product.index');
    }

    public function getProduct(){
        $products = DB::table('stocks')
                        ->join('products','products.id','=','stocks.product_id')
                        ->where('products.kode', 'like', '%' . request('kode') . '%')
                        ->where('stocks.jumlah','>',0)
                        ->where('stocks.outlet_id', request('outlet'))
                        ->select('products.kode','products.tipe','products.jual','stocks.product_id')
                        ->skip(0)
                        ->take(5)
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }
}
