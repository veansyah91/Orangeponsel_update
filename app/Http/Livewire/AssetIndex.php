<?php

namespace App\Http\Livewire;

use App\Model\Asset;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class AssetIndex extends Component
{
    protected $listeners = [
        'showData' => 'handleShowData'
    ];
    public $showUpdate;

    public function resetState()
    {
        $this->showUpdate = false; 
    }

    public function render()
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $assets = Asset::where('outlet_id', $outletUser['outlet_id'])->orderByDesc('id')->paginate(10);
        return view('livewire.asset-index',[
            'assets' => $assets
        ]);
    }

    public function handleShowData()
    {
        $this->resetState();
    }

    public function deleteConfirmation($id)
    {
        $delete = Asset::find($id)->delete();
    }

    public function edit($id)
    {
        $this->showUpdate = true;
        $this->emit('editData', $id);
    }
}
