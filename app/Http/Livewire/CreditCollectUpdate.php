<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditCollect;
use Illuminate\Support\Facades\Auth;

class CreditCollectUpdate extends Component
{

    protected $listeners = [
        'showUpdate' => 'handleShowUpdate'
    ];

    public $dataId, $nama, $no_hp, $tenggang, $keterangan, $terlambat;

    public function render()
    {
        return view('livewire.credit-collect-update');
    }

    public function back()
    {
        $this->emit('showIndex');
    }

    public function handleShowUpdate($id)
    {
        $this->dataId = $id;
        $data = CreditCollect::find($id);

        $this->nama = $data['nama'];
        $this->no_hp = $data['no_hp'];
        $this->tenggang = $data['tenggang'];
        $this->keterangan = $data['keterangan'];
        $this->terlambat = $data['terlambat'];
    }

    public function update()
    {
        $this->validate([
            'tenggang' => 'required',
            'keterangan' => 'required',
        ]);

        $user = Auth::user();
        $today = Date('Y-m-d');

        $update = CreditCollect::find($this->dataId)->update([
            'tenggang' => $this->tenggang,
            'keterangan' => $this->keterangan,
            'tanggal_penagihan' => $today,
            'collector' => $user->name,
        ]);

        $this->emit('showIndex');
    }
}
