<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(){
        return view('admin.stock.index');
    }

    public function balance()
    {
        return view('admin.stock.balance');
    }

    public function pdf()
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();
        $stocks = DB::table('stocks')->join('outlets','outlets.id','=','stocks.outlet_id')
                                    ->join('products','products.id','=','stocks.product_id')
                                    ->join('categories','categories.id','=','products.category_id')
                                    ->where('stocks.outlet_id','like', '%' . $outletUser['outlet_id'] . '%') 
                                    ->where('stocks.jumlah', '>' ,0)                                   
                                    ->select('stocks.id','stocks.updated_at','stocks.jumlah','products.modal','products.tipe', 'products.kode','products.category_id','stocks.outlet_id','stocks.item_entry_id', 'categories.nama as category_name')
                                    ->orderBy('categories.id')
                                    ->orderBy('products.tipe')
                                    ->get();
        $pdf = PDF::loadView('admin.stock.pdf', [
            'stocks' => $stocks
        ]);

        return $pdf->download('stock-report.pdf');
    }
}
