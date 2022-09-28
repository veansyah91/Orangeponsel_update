<?php

use App\Model\Outlet;
use App\Model\Account;
use App\Model\Category;
use Illuminate\Database\Seeder;

class CategoryToAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get category product
        $categories = Category::all();

        //get outlets
        $outlets = Outlet::all();

        foreach ($outlets as $outlet) {
            foreach ($categories as $category) {
                //classification persediaan 
                $merchantInventory = Account::where('outlet_id', $outlet->id)
                                            ->where('code', 'like', '13%')->get()->last();

                $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '1300000';

                //new Account base on categoty product
                Account::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Persediaan ' . ucfirst($category->nama),
                    'code' => $newCode,
                    'classification' => 'Persediaan Barang Dagang',
                    'is_active' => 1
                ]);

                //classification penjualan barang dagang 
                $merchantInventory = Account::where('outlet_id', $outlet->id)
                                            ->where('code', 'like', '41%')->get()->last();

                $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '4100000';

                //new Account base on categoty product
                Account::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Penjualan ' . ucfirst($category->nama),
                    'code' => $newCode,
                    'classification' => 'Penjualan Barang Dagang',
                    'is_active' => 1
                ]);

                //classification penjualan barang dagang 
                $merchantInventory = Account::where('outlet_id', $outlet->id)
                                            ->where('code', 'like', '43%')->get()->last();

                $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '4300000';

                //new Account base on categoty product
                Account::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Retur Penjualan ' . ucfirst($category->nama),
                    'code' => $newCode,
                    'classification' => 'Retur Penjualan Barang Dagang',
                    'is_active' => 1
                ]);

                //classification penjualan barang dagang 
                $merchantInventory = Account::where('outlet_id', $outlet->id)
                                            ->where('code', 'like', '52%')->get()->last();

                $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '5200000';

                //new Account base on categoty product
                Account::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Harga Pokok Penjualan ' . ucfirst($category->nama),
                    'code' => $newCode,
                    'classification' => 'Harga Pokok Penjualan',
                    'is_active' => 1
                ]);

                //classification penjualan barang dagang 
                $merchantInventory = Account::where('outlet_id', $outlet->id)
                                            ->where('code', 'like', '53%')->get()->last();

                $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '5300000';

                //new Account base on categoty product
                Account::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Return Pembelian ' . ucfirst($category->nama),
                    'code' => $newCode,
                    'classification' => 'Retur Pembelian',
                    'is_active' => 1
                ]);
            }
        }
    }
}
