<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Model\InterOutlet;

class InterOutletCreateCredit extends Component
{
    public $person1, $person2;

    public $nominal, $keterangan;

    protected $listeners = [
        'getCreateCredit' => "showCreateCredit",
    ];

    public function render()
    {
        return view('livewire.inter-outlet-create-credit');
    }

    public function showCreateCredit($person1, $person2)
    {
        $this->person1 = $person1;
        $this->person2 = $person2;
        $this->nominal = 0;
    }

    public function backTo()
    {
        $this->emit('getOutletDetail');
    }

    public function store()
    {
        $store = InterOutlet::create([
            'pihak_1' => $this->person1,
            'pihak_2' => $this->person2,
            'jumlah' => $this->nominal,
            'keterangan' => $this->keterangan,
            'konfirmasi' => 'check',
        ]);

        $this->emit('getOutletDetail');
    }
}
