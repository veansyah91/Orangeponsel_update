<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use Livewire\Component;
use App\Model\ItemEntry;

class ItemEntryDetail extends Component
{
    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public $showUpdate;

    public $dataId;

    public function mount()
    {
        $this->showUpdate = false;
    }

    public function render()
    {
        $itemEntry = ItemEntry::find($this->dataId);

        $stocks = Stock::where('item_entry_id', $this->dataId)->get();

        return view('livewire.item-entry-detail', [
            'itemEntry' => $itemEntry,
            'stocks' => $stocks
        ]);
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->dataId = $id;
        $this->showUpdate = false;
        $this->emit('showCreate', $id);
    }

    public function deleteConfirmation($id)
    {
        $delete = stock::find($id)->delete();
    }

    public function edit($id)
    {
        $this->showUpdate = true;
        $this->emit('showEdit', $id);
    }
}
