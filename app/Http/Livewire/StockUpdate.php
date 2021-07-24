<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Product;
use App\Model\Outlet;
use App\Model\Stock;

class StockUpdate extends Component
{
    public $showProductInputList;
    public $product;
    public $productId;

    public $stockId;

    public $outletId;
    public $outlets;
    public $kode;
    public $tipe;

    public $productInputLists;
    public $jumlah;

    protected $listeners = [
        'getStock' => 'showStock',
    ];

    public function mount(){

        $this->outlets = Outlet::all();
        // $this->resetListInput();
    }

    public function render()
    {
        $this->productInputLists = Product::where('kode', 'like', '%' . $this->kode . '%')->skip(0)->take(5)->get();
        return view('livewire.stock-update',[
            'outlets' => $this->outlets,
            'productInputLists' => $this->productInputLists
        ]);
    }

    public function showSearchProduct()
    {
        $this->showProductInputList ? $this->showProductInputList = false : $this->showProductInputList = true;
    }

    public function inputSearchProduct()
    {
        $this->showProductInputList = true;
    }

    public function resetListInput()
    {
        $this->showProductInputList = false;
        $this->jumlah = 1;
        $this->outlets = Outlet::all();
        $this->outletId = $this->outlets[0]->id;
        $this->tipe = '';
    }

    public function selectProduct($id,$kode, $tipe)
    {
        $this->kode = $kode;
        $this->tipe = $tipe;
        $this->productId = $id;
        $this->showProductInputList = false;

    }

    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->validate([
            'kode' => 'required',
        ]);

        $stock = Stock::where('id', $this->stockId)->update([
            'jumlah' => $this->jumlah,
        ]);

        $this->resetListInput();

        $this->emit('stockUpdated');
        
    }

    public function showStock($stock){
        $this->stockId = $stock['id'];
        $this->outletId = $stock['outlet_id'];
        $this->jumlah = $stock['jumlah'];

        $product = Product::where('id', $stock['product_id'])->first();
        $this->tipe = $product['tipe'];
        $this->kode = $product['kode'];
    }

    public function cancelUpdate()
    {
        $this->emit('cancelUpdateStock');
    }
    
}
