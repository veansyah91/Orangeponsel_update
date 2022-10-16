<?php

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockToModalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $outlets = Outlet::all();
        $value = [];

        foreach ($categories as $category) {
            foreach ($outlets as $outlet) {
                //dapatkan persediaan barang dagang berdasarkan kategori
                $stocks = DB::table('stocks')
                            ->join('products', 'products.id','=','stocks.product_id')
                            ->join('categories','categories.id','=','products.category_id')
                            ->where('stocks.outlet_id', $outlet['id'])
                            ->where('categories.id', $category['id'])
                            ->where('stocks.jumlah', '>', 0)
                            ->select('products.modal as modal', 'stocks.jumlah as jumlah')
                            ->get();

                $temp_value = 0;
                foreach ($stocks as $stock) {
                    $temp_value += $stock->modal * $stock->jumlah;
                }

                if ($temp_value > 0) {
                    //update pada buku besar
                    //account per category
                    $account_name = 'Persediaan ' . $category['nama'];
                    $account = Account::where('outlet_id', $outlet['id'])
                                        ->where('name', $account_name)
                                        ->first();

                    if ($account) {
                        Ledger::create([
                            'outlet_id' => $outlet['id'],
                            'account_id' => $account['id'],
                            'account' => $account['name'],
                            'no_ref' => '',
                            'debit' => $temp_value,
                            'credit' => 0,
                            'date' => date('Y-m-d'),
                            'description' => $account_name
                        ]);

                    }

                    //account modal
                    $account = Account::where('outlet_id', $outlet['id'])
                                        ->where('code', '3100000')
                                        ->first();

                    if ($account) {
                        Ledger::create([
                            'outlet_id' => $outlet['id'],
                            'account_id' => $account['id'],
                            'account' => $account['name'],
                            'no_ref' => '',
                            'debit' => 0,
                            'credit' => $temp_value ,
                            'date' => date('Y-m-d'),
                            'description' => $account_name
                        ]);
                    }
                }

            }
        }
    }
}
