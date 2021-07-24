<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Brand;

class BrandUpdate extends Component
{
    public $nama;
    public $brandId;

    protected $listeners = [
        'getBrand' => "showBrand" 
    ];

    public function render()
    {
        return view('livewire.brand-update');
    }

    public function showBrand($brand){
        $this->nama = $brand['nama'];
        $this->brandId = $brand['id'];
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $brand = Brand::find($this->brandId)->update([
            'nama' => strtoupper($this->nama)
        ]);

        // auto reload
        $this->emit('brandUpdated');
    }

    public function cancelUpdate(){
        $this->emit('cancelBrandUpdate');
    }
}
