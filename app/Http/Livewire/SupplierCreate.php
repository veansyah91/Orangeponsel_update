<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Supplier;

class SupplierCreate extends Component
{
    public $nama;
    public $hp;
    public $alamat;

    public function render()
    {
        return view('livewire.supplier-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required',
        ]);

        $supplier = Supplier::create([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('supplierStored', $supplier);
    }

    private function resetInput(){
        $this->nama = null;
        $this->hp = null;
        $this->alamat = null;
    }
}
