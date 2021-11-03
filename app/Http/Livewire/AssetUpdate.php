<?php

namespace App\Http\Livewire;

use App\Model\Asset;
use Livewire\Component;

class AssetUpdate extends Component
{
    protected $listeners = [
        'editData' => 'handleEditData'
    ];

    public $nama, $jumlah, $harga, $dataId;

    public function render()
    {
        return view('livewire.asset-update');
    }

    public function handleEditData($id)
    {
        $data = Asset::find($id);
        $this->dataId = $data['id'];
        $this->nama = $data['nama'];
        $this->jumlah = $data['jumlah'];
        $this->harga = $data['harga'];
    }

    public function cancelEdit()
    {
        $this->emit('showData');
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric|min:1',
        ]);

        $update = Asset::find($this->dataId)->update([
            'nama' => $this->nama,
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
        ]);

        $this->emit('showData');
    }
}
