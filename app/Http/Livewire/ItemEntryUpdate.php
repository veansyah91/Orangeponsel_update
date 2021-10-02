<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\ItemEntry;

class ItemEntryUpdate extends Component
{
    protected $listeners = [
        'showEdit' => 'handleShowEdit'
    ];

    public $nomor_nota, $tanggal_masuk, $dataId;

    public function render()
    {
        return view('livewire.item-entry-update');
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function handleShowEdit($id)
    {
        $data = ItemEntry::find($id);
        $this->dataId = $data['id'];
        $this->nomor_nota = $data['nomor_nota'];
        $this->tanggal_masuk = $data['tanggal_masuk'];
    }

    public function update()
    {
        $this->validate([
            'nomor_nota' => 'required',
            'tanggal_masuk' => 'required|date',
        ]);

        $update = ItemEntry::find($this->dataId)->update([
            'nomor_nota' => $this->nomor_nota,
            'tanggal_masuk' => $this->tanggal_masuk,
        ]);

        $this->emit('showIndex');
    }
}
