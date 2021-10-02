<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\ItemEntry;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class ItemEntryCreate extends Component
{

    public $nomor_nota, $tanggal_masuk;

    public function render()
    {
        return view('livewire.item-entry-create');
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function store()
    {
        $this->validate([
            'nomor_nota' => 'required',
            'tanggal_masuk' => 'required|date',
        ]);

        $user = Auth::user();
        $outlet_user = OutletUser::where('user_id', $user['id'])->first();

        $createData = ItemEntry::create([
            'nomor_nota' => $this->nomor_nota,
            'tanggal_masuk' => $this->tanggal_masuk,
            'outlet_id' => $outlet_user['outlet_id'],
        ]);

        $this->emit('showIndex');
    }
}
