<?php

namespace App\Http\Livewire;

use App\Model\Account;
use App\Model\Category;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class CategoryUpdate extends Component
{
    public $nama;
    public $categoryId;

    protected $listeners = [
        'getCategory' => "showCategory" 
    ];


    public function render()
    {
        return view('livewire.category-update');
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $category = Category::find($this->categoryId);
        $old_category_name = $category['nama'];

        $category->update([
            'nama' => strtoupper($this->nama)
        ]);        

        //ubah account
        //persediaan
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('name', 'like', '%' . $old_category_name . '%')
                                ->where('code', 'like', '13%')
                                ->first();

        $merchantInventory->update([
            'name' => 'Persediaan ' . ucfirst($this->nama),
        ]);

        //penjualan
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('name', 'like', '%' . $old_category_name . '%')
                                ->where('code', 'like', '41%')
                                ->first();

        $merchantInventory->update([
            'name' => 'Penjualan ' . ucfirst($this->nama),
        ]);

        //Retur Penjualan
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('name', 'like', '%' . $old_category_name . '%')
                                ->where('code', 'like', '43%')
                                ->first();

        $merchantInventory->update([
            'name' => 'Retur Penjualan ' . ucfirst($this->nama),
        ]);

        //Harga Pokok Penjualan
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('name', 'like', '%' . $old_category_name . '%')
                                ->where('code', 'like', '52%')
                                ->first();

        $merchantInventory->update([
            'name' => 'Harga Pokok Penjualan ' . ucfirst($this->nama),
        ]);

        //Retur Pembelian
        $merchantInventory = Account::where('outlet_id', $outletUser['outlet_id'])
                                ->where('name', 'like', '%' . $old_category_name . '%')
                                ->where('code', 'like', '53%')
                                ->first();

        $merchantInventory->update([
            'name' => 'Retur Pembelian ' . ucfirst($this->nama),
        ]);

        // auto reload
        $this->emit('categoryUpdated');
    }

    public function showCategory($category){
        $this->nama = $category['nama'];
        $this->categoryId = $category['id'];
    }

    public function cancelUpdate(){
        $this->emit('cancelCategoryUpdate');
    }
}
