<?php

namespace App\Http\Controllers;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Category;
use App\Model\OutletUser;
use App\Model\CreditSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $outletUser = OutletUser::where('user_id', Auth::user()->id)->first();

        if (!$outletUser) {
            $creditSales = CreditSales::where('user_id', Auth::user()->id)->first();
            if ($creditSales) {
                return redirect('/credit-partner/partner=' . $creditSales['credit_partner_id'] . '/customer');
            }

        }

        $outlet = Outlet::find($outletUser->outlet_id);

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();
        return view('home',
            ['user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,]
        );
    }

    public function lostProfit()
    {
        $data = Ledger::filter(request(['outlet_id', 'month', 'year']))
                        ->whereHas('account', function($query){
                            $query->where('code', 'like', '4%')
                                  ->orWhere('code', 'like', '5%');
                        })
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                        'lost_profit' => $data->sum('credit') - $data->sum('debit'),
                        'income' => $data->sum('credit'),
                        'expense' => $data->sum('debit')
                      ] 
        ]);
    }

    public function asset()
    {
        $data = Ledger::filter(request(['outlet_id', 'month_selected', 'year_selected']))
                        ->whereHas('account', function($query){
                            $query->where('code', 'like', '1%');
                        })
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data->sum('debit') - $data->sum('credit')
        ]);
    }

    public function liability()
    {
        $data = Ledger::filter(request(['outlet_id', 'month_selected', 'year_selected']))
                        ->whereHas('account', function($query){
                            $query->where('code', 'like', '2%');
                        })
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data->sum('credit') - $data->sum('debit')
        ]);
    }

    public function equity()
    {
        $data = Ledger::filter(request(['outlet_id', 'month_selected', 'year_selected']))
                        ->whereHas('account', function($query){
                            $query->where('code', 'like', '3%');
                        })
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data->sum('credit') - $data->sum('debit')
        ]);
    }

    public function getStockPerCategory()
    {
        $categories = Category::all();
        $outlets = Outlet::all();

        $stocks = [];
        $quantity = [];
        $value = [];

        foreach ($categories as $key => $category) {
            //dapatkan persediaan barang dagang berdasarkan kategori
            $stocks[$key] = DB::table('stocks')
                        ->join('products', 'products.id','=','stocks.product_id')
                        ->join('categories','categories.id','=','products.category_id')
                        ->where('stocks.outlet_id', request('outlet_id'))
                        ->where('categories.id', $category['id'])
                        ->where('stocks.jumlah', '>', 0)
                        ->select('products.modal as modal', 'stocks.jumlah as jumlah')
                        ->get();

            $temp_value = 0;
            foreach ($stocks[$key] as $stock) {
                $temp_value += $stock->modal * $stock->jumlah;
            }

            $value[$key] = [
                'value' => $temp_value,
                'category' => $category['nama']
            ];
        }
        return response()->json([
            'status' => 'success',
            'data' => $value
        ]);
    }
}
