<?php

namespace App\Http\Livewire;

use App\Model\Service;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class ServiceCreate extends Component
{

    public $nama, $keterangan, $no_hp, $tanggal_masuk, $tipe, $nomor;

    public function mount()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->tanggal_masuk = Date('Y-m-d');
    }
    
    public function render()
    {
        return view('livewire.service-create');
    }

    public function cancelFunc()
    {
        $this->emit('showIndex');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'nomor' => 'required',
            'keterangan' => 'required',
            'no_hp' => 'required',
            'tipe' => 'required',
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $storeData = Service::create([
            'nama' => $this->nama,
            'nomor' => $this->nomor,
            'keterangan' => $this->keterangan,
            'no_hp' => $this->no_hp,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tipe' => $this->tipe,
            'status' => 'pending',
            'outlet_id' => $outletUser['outlet_id'],
        ]);

        $this->emit('showIndex');
    }
}
