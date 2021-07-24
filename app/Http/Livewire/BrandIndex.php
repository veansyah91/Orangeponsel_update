<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination; 
use App\Model\Brand;

class BrandIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;
    public $statusUpdate = false;
    public $search = '';

    protected $listeners = [
        'brandStored' => 'handleBrandStored',
        'brandUpdated' => 'handleBrandUpdated',
        'cancelBrandUpdate' => 'handleBrandCancelUpdate',
    ];

    public function render()
    {
        return view('livewire.brand-index', [
            'data' => $this->search == '' ? 
                      Brand::paginate($this->paginate) :
                      Brand::where('nama', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function getBrand($id){
        $this->statusUpdate = true;
        $brand = Brand::find($id);
        $this->emit('getBrand', $brand);
    }

    public function destroy($id){
        if ($id) {
            $brand = Brand::find($id);
            $brand->delete();
            session()->flash('success', "Brand Berhasil Dihapus");
        }
    }

    public function handleBrandStored(){
        session()->flash('success', "Brand Berhasil Ditambah");
    }

    public function handleBrandUpdated(){
        $this->statusUpdate = false;
        session()->flash('success', "Brand Berhasil Diubah");
    }

    public function handleBrandCancelUpdate(){
        $this->statusUpdate = false;
    }
}
