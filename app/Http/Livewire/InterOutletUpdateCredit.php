<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Model\InterOutlet;

class InterOutletUpdateCredit extends Component
{

    public $dataId, $nominal, $keterangan;

    protected $listeners = [
        'getUpdateCredit' => "showUpdateCredit",
    ];

    public function render()
    {
        return view('livewire.inter-outlet-update-credit');
    }

    public function backTo()
    {
        $this->emit('getOutletDetail');
    }

    public function showUpdateCredit($dataId)
    {
        $interOutlet = InterOutlet::find($dataId);

        $this->dataId = $interOutlet->id;
        $this->nominal = $interOutlet->jumlah;
        $this->keterangan = $interOutlet->keterangan;
    }

    public function update()
    {
        $update = InterOutlet::where('id', $this->dataId)->update([
            'jumlah' => $this->nominal,
            'keterangan' => $this->keterangan,
            'konfirmasi' => "check"
        ]);

        $this->emit('getOutletDetail');
    }
}
