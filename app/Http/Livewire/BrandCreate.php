<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Brand;

class BrandCreate extends Component
{
    public $nama;
    
    public function render()
    {
        return view('livewire.brand-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required'
        ]);

        $brand = Brand::create([
            'nama' => strtoupper($this->nama)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('brandStored', $brand);
    }

    private function resetInput(){
        $this->nama = null;
    }
}
