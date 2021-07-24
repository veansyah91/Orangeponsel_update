<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Outlet;
use App\Model\Customer;

class OutletUpdate extends Component
{
    public $nama;
    public $alamat;
    public $hp;
    public $outletId;

    protected $listeners = [
        'getOutlet' => "showOutlet" 
    ];

    public function render()
    {
        return view('livewire.outlet-update');
    }

    public function showOutlet($outlet){
        $this->nama = $outlet['nama'];
        $this->hp = $outlet['hp'];
        $this->alamat = $outlet['alamat'];
        $this->outletId = $outlet['id'];
    }

    public function update(){
        $this->validate([
            'nama' => 'required'
        ]);

        $outlet = Outlet::find($this->outletId)->update([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        $customer = Customer::where('outlet_id', $this->outletId)->update([
            'nama' => strtoupper($this->nama),
            'hp' => $this->hp,
            'alamat' => strtoupper($this->alamat)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('outletUpdated');
    }

    public function cancelUpdate(){
        $this->emit('cancelOutletUpdate');
    }

    private function resetInput(){
        $this->nama = null;
    }
}
