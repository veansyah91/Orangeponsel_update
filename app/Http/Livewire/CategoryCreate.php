<?php

namespace App\Http\Livewire;

use App\Model\Account;
use App\Model\Category;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;


class CategoryCreate extends Component
{
    public $nama;
    public $outletUser;

    public function render()
    {

        return view('livewire.category-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required'
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $category = Category::create([
            'nama' => strtoupper($this->nama)
        ]);

        //create chart of account 
        //classification persediaan 
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('code', 'like', '13%')->get()->last();

        $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '1300000';

        //new Account base on categoty product
        Account::create([
            'outlet_id' => $outletUser['outlet_id'],
            'name' => 'Persediaan ' . ucfirst($category->nama),
            'code' => $newCode,
            'classification' => 'Persediaan Barang Dagang',
            'is_active' => 1
        ]);

        //classification penjualan barang dagang 
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                ->where('code', 'like', '41%')->get()->last();

        $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '4100000';

        //new Account base on categoty product
        Account::create([
            'outlet_id' => $outletUser['outlet_id'],
            'name' => 'Penjualan ' . ucfirst($category->nama),
            'code' => $newCode,
            'classification' => 'Penjualan Barang Dagang',
            'is_active' => 1
        ]);

        //classification penjualan barang dagang 
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                ->where('code', 'like', '43%')->get()->last();

        $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '4300000';

        //new Account base on categoty product
        Account::create([
            'outlet_id' => $outletUser['outlet_id'],
            'name' => 'Retur Penjualan ' . ucfirst($category->nama),
            'code' => $newCode,
            'classification' => 'Retur Penjualan Barang Dagang',
            'is_active' => 1
        ]);

        //classification penjualan barang dagang 
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('code', 'like', '52%')->get()->last();

        $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '5200000';

        //new Account base on category product
        Account::create([
            'outlet_id' => $outletUser['outlet_id'],
            'name' => 'Harga Pokok Penjualan ' . ucfirst($category->nama),
            'code' => $newCode,
            'classification' => 'Harga Pokok Penjualan',
            'is_active' => 1
        ]);

        //classification penjualan barang dagang 
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                ->where('code', 'like', '53%')->get()->last();

        $newCode = $merchantInventory ? (int)$merchantInventory->code + 1 : '5300000';

        //new Account base on category product
        Account::create([
            'outlet_id' => $outletUser['outlet_id'],
            'name' => 'Return Pembelian ' . ucfirst($category->nama),
            'code' => $newCode,
            'classification' => 'Retur Pembelian',
            'is_active' => 1
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('categoryStored', $category);
    }

    private function resetInput(){
        $this->nama = null;
    }
}
