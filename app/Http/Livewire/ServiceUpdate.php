<?php

namespace App\Http\Livewire;

use App\Model\Service;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class ServiceUpdate extends Component
{
    protected $listeners = [
        'showUpdate' => 'handleShowUpdate'
    ];

    public $nama, $keterangan, $no_hp, $tanggal_masuk, $tipe, $dataId, $nomor;

    public function render()
    {
        return view('livewire.service-update');
    }

    public function cancelFunc()
    {
        $this->emit('showIndex');
    }

    public function handleShowUpdate($id)
    {
        $service = Service::find($id);
        $this->nama = $service['nama'];
        $this->keterangan = $service['keterangan'];
        $this->no_hp = $service['no_hp'];
        $this->tanggal_masuk = $service['tanggal_masuk'];
        $this->tipe = $service['tipe'];
        $this->dataId = $service['id'];
        $this->nomor = $service['nomor'];
    }

    public function update()
    {
        $this->validate([
            'nomor' => 'required',
            'nama' => 'required',
            'keterangan' => 'required',
            'no_hp' => 'required',
            'tipe' => 'required',
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $storeData = Service::find($this->dataId)->update([
            'nomor' => $this->nomor,
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
            'no_hp' => $this->no_hp,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tipe' => $this->tipe,
        ]);

        $this->emit('showIndex');
    }
}
