<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\Outlet;

class OutletIndex extends Component
{
    public $showUpdate = false;
    public $showInput= false;

    protected $listeners = [
        'outletStored' => 'handleStored',
        'outletUpdated' => 'handleUpdated',
        'cancelOutletUpdate' => 'handleCancelUpdate',
    ];

    public function render()
    {
        return view('livewire.outlet-index',[
            'data' => Outlet::all()
        ]);
    }

    public function getOutlet($id){
        $this->showUpdate = true;
        $outlet = Outlet::find($id);
        $this->emit('getOutlet', $outlet);
    }

    public function destroy($id){
        if ($id) {
            $outlet = Outlet::find($id);
            $outlet->delete();
            session()->flash('success', "Outlet Berhasil Dihapus");
        }
    }

    public function handleStored(){
        session()->flash('success', "Outlet Berhasil Ditambah");
    }

    public function handleUpdated(){
        $this->showUpdate = false;
        session()->flash('success', "Outlet Berhasil Diubah");
    }

    public function handleCancelUpdate(){
        $this->showUpdate = false;
    }
    
}
