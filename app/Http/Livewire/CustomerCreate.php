<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Customer;

class CustomerCreate extends Component
{
    public $nama;
    public $hp;
    public $alamat;

    public function render()
    {
        return view('livewire.customer-create');
    }

    public function store(){
        $this->validate([
            'nama' => 'required',
        ]);

        $customer = Customer::create([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('customerStored', $customer);
    }

    private function resetInput(){
        $this->nama = null;
        $this->hp = null;
        $this->alamat = null;
    }
}
