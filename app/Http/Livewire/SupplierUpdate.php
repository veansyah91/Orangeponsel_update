<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Supplier;

class SupplierUpdate extends Component
{
    public $nama;
    public $alamat;
    public $hp;
    public $supplierId;

    protected $listeners = [
        'getSupplier' => "showSupplier" 
    ];

    public function render()
    {
        return view('livewire.supplier-update');
    }

    public function showSupplier($supplier){
        $this->nama = $supplier['nama'];
        $this->hp = $supplier['hp'];
        $this->alamat = $supplier['alamat'];
        $this->supplierId = $supplier['id'];
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $supplier = Supplier::find($this->supplierId)->update([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        // auto reload
        $this->emit('supplierUpdated');
    }
}
