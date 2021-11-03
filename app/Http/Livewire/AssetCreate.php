<?php

namespace App\Http\Livewire;

use App\Model\Asset;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class AssetCreate extends Component
{
    public $nama, $jumlah, $harga;

    public function mount()
    {
        $this->resetState();
    }

    public function resetState()
    {
        $this->nama = '';
        $this->jumlah = 1;
        $this->harga = 0;
    }

    public function render()
    {
        return view('livewire.asset-create');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $createData = Asset::create([
            'nama' => $this->nama,
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
            'outlet_id' => $outletUser['outlet_id'],
        ]);

        $this->resetState();

        $this->emit('showData');
    }
}
