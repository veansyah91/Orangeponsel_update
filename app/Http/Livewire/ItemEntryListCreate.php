<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use App\Model\Outlet;
use App\Model\Product;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class ItemEntryListCreate extends Component
{
    
    protected $listeners = [
        'showCreate' => 'handleShowCreate'
    ];

    public $showSearch;

    public $jumlah, $productSearch, $kode, $productName, $itemEntryId, $productId;

    public function mount()
    {
        $this->resetData();
    }

    public function resetData()
    {
        $this->jumlah = 1;
        $this->productSearch = '';
        $this->kode = '';
        $this->productName = '';
        $this->showSearch = false;
    }

    public function render()
    {
        $products = Product::where('kode', 'like', '%' . $this->productSearch . '%')->skip(0)
        ->take(5)->get();
        return view('livewire.item-entry-list-create', [
            'products' => $products
        ]);
    }

    public function showSearchFunc()
    {
        $this->showSearch = !$this->showSearch;
    }

    public function selectProduct($id)
    {
        $product = Product::find($id);
        $this->productName= $product['tipe'];
        $this->kode = $product['kode'];
        $this->productId = $product['id'];

        $this->showSearch = !$this->showSearch;

        
    }

    public function store()
    {
        $this->validate([
            'kode' => 'required',
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $addStock = Stock::create([
            'product_id' => $this->productId,
            'outlet_id' => $outletUser['outlet_id'],
            'jumlah' => $this->jumlah,
            'item_entry_id' => $this->itemEntryId
        ]);

        $this->resetData();
        $this->emit('showDetail', $this->itemEntryId);
    }

    public function handleShowCreate($id)
    {
        $this->itemEntryId = $id;
    }
}
