<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Customer;

class CustomerUpdate extends Component
{
    public $nama;
    public $alamat;
    public $hp;
    public $customerId;

    protected $listeners = [
        'getCustomer' => "showCustomer" 
    ];

    public function render()
    {
        return view('livewire.customer-update');
    }

    public function showCustomer($customer){
        $this->nama = $customer['nama'];
        $this->hp = $customer['hp'];
        $this->alamat = $customer['alamat'];
        $this->customerId = $customer['id'];
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $customer = Customer::find($this->customerId)->update([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        // auto reload
        $this->emit('customerUpdated');
    }
}
