<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination; 
use App\Model\Product;


class ProductIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;
    public $showProduct = false;
    public $showCreate = false;
    public $showUpdate = false;
    public $search = '';

    public function mount(){
        $this->showProduct();
    }

    protected $listeners = [
        'productUpdated' => 'handleUpdated',
        'showProduct' => 'showProduct',
    ];

    public function render()
    {
        
        return view('livewire.product-index',[
            'data' => $this->search == '' ? 
                      Product::paginate($this->paginate) :
                      Product::where('kode', 'like', '%' . $this->search . '%')
                                ->orWhere('tipe', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function showProduct()
    {
        $this->showProduct = true;
        $this->showCreate = false;
        $this->showUpdate = false;
    }

    public function showCreate()
    {
        $this->showProduct = false;
        $this->showCreate = true;
        $this->showUpdate = false;
    }

    public function getProduct($productId)
    {
        $this->showProduct = false;
        $this->showCreate = false;
        $this->showUpdate = true;

        $product = Product::where('id', $productId)->first();

        $this->emit('getProduct', $product);
    }

    public function handleUpdated()
    {
        $this->showProduct();
        session()->flash('success', "Produk Berhasil Diubah");
    }

    public function destroy($id)
    {
        if ($id) {
            $product = Product::find($id);
            $product->delete();
            session()->flash('success', "Produk Berhasil Dihapus");
        }
    }

}
