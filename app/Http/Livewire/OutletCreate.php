<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Outlet;
use App\Model\Customer;

class OutletCreate extends Component
{
    public $nama;
    public $hp;
    public $alamat;

    public function render()
    {
        return view('livewire.outlet-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required',
        ]);

        $outlet = Outlet::create([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        $customer = Customer::create([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat),
            'outlet_id' => $outlet
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('outletStored', $outlet);
    }

    private function resetInput(){
        $this->nama = null;
        $this->hp = null;
        $this->alamat = null;
    }
}
