<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\ItemEntry;
use App\Model\OutletUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ItemEntryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = [
        'showIndex' => 'handleShowIndex'
    ];
    
    public $create, $update, $detail;

    public $search;

    public function mount()
    {
        $this->resetValue();
    }

    public function resetValue()
    {
        $this->create = false;
        $this->update = false;
        $this->detail = false;
    }

    public function render()
    {
        $user = Auth::user();
        $outlet_user = OutletUser::where('user_id', $user['id'])->first();
        $itemEntries = ItemEntry::where('outlet_id', $outlet_user['outlet_id'])->where('nomor_nota', 'like', '%' . $this->search . '%')->paginate(10);
        return view('livewire.item-entry-index', [
            'itemEntries' => $itemEntries
        ]);
    }

    public function createData()
    {
        $this->create = true;
    }

    public function handleShowIndex()
    {
        $this->resetValue();
    }

    public function deleteConfirmation($id)
    {
        $delete = ItemEntry::find($id)->delete();
    }

    public function edit($id)
    {
        $this->update = true;
        $this->emit('showEdit', $id);
    }

    public function show($id)
    {
        $this->detail = true;
        $this->emit('showDetail', $id);
    }
}
