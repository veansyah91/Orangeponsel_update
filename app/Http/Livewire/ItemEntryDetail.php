<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\ItemEntry;

class ItemEntryDetail extends Component
{
    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public $dataId;

    public function render()
    {
        $itemEntry = ItemEntry::find($this->dataId);

        return view('livewire.item-entry-detail', [
            'itemEntry' => $itemEntry
        ]);
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->dataId = $id;
    }
}
