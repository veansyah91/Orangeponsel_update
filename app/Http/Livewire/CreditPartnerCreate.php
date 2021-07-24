<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPartner;

class CreditPartnerCreate extends Component
{
    public $nama, $alamat, $alias;

    public function render()
    {
        return view('livewire.credit-partner-create');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
        ]);
        
        $creditPartner = CreditPartner::create([
            'nama_mitra' => strtoupper($this->nama),
            'alias' => strtoupper($this->alias),
            'alamat' => strtoupper($this->alamat)
        ]);

        // mengosongkan form input
        $this->resetInput();

        // auto reload
        $this->emit('getCreditPartner');
    }

    private function resetInput(){
        $this->nama = null;
        $this->alamat = null;
        $this->alias = null;
    }
}
