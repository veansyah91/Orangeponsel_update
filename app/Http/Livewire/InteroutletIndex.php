<?php

namespace App\Http\Livewire;

use App\Model\Outlet;


use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Auth;

class InteroutletIndex extends Component
{

    public $outletId, $outlets, $outlet, $selectOutlet, $defaultOtherOutletId, $showModal;

    public function mount(){
        $this->outlets = Outlet::all();

        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        if ($roleUser->name == 'SUPER ADMIN') {
            # code...
            $this->outletId = $this->outlets[0]->id;
            $this->selectOutlet = $this->outlets[0]->id;
        } else {
            # code...
            $outletUser = OutletUser::where('user_id', $user->id)->first();
            $this->outletId = $outletUser->outlet_id;
            $this->selectOutlet = $outletUser->outlet_id;
        }      
        
        $otherOutlet = Outlet::where('id', '<>', $this->selectOutlet)->get();
        $this->defaultOtherOutletId = $otherOutlet[0]->id;
    }

    public function render()
    {
        $otherOutlet = Outlet::where('id', '<>', $this->selectOutlet)->get();
        return view('livewire.interoutlet-index',[
            'otherOutlet' => $otherOutlet,
        ]);
    }

    public function showOutletDetail($outletId)
    {
        
        $this->defaultOtherOutletId = $outletId;
        $this->emit('getOutlet', $this->selectOutlet, $outletId);
    }

    public function changeOutlet()
    {
        $otherOutlet = Outlet::where('id', '<>', $this->selectOutlet)->get();
        
        $this->showOutletDetail($otherOutlet[0]->id);
    }
}
