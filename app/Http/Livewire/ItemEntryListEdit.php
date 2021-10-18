<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use App\Model\Product;
use Livewire\Component;

class ItemEntryListEdit extends Component
{
    protected $listeners = [
        'showEdit' => 'handleShowEdit'
    ];

    public $showSearch;

    public $jumlah, $productSearch, $kode, $productName, $itemEntryId, $productId, $dataId;

    public function mount()
    {
        $this->productSearch = '';
    }

    public function render()
    {
        $products = Product::where('kode', 'like', '%' . $this->productSearch . '%')->skip(0)
        ->take(5)->get();
        return view('livewire.item-entry-list-edit', [
            'products' => $products
        ]);
    }

    public function handleShowEdit($id)
    {
        $stock = Stock::find($id);
        $this->productName = $stock->product->tipe;
        $this->kode= $stock->product->kode;
        $this->dataId = $stock['id'];
        $this->productId = $stock['product_id'];
        $this->jumlah = $stock['jumlah'];
        $this->itemEntryId = $stock['item_entry_id'];
    }

    public function showIndex()
    {
        $this->emit('showDetail', $this->itemEntryId);
    }

    public function update()
    {
        $this->validate([
            'kode' => 'required',
        ]);

        $addStock = Stock::find($this->dataId)->update([
            'product_id' => $this->productId,
            'jumlah' => $this->jumlah,
        ]);

        $this->emit('showDetail', $this->itemEntryId);
    }
}
