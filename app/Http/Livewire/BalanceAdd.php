<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use App\Model\Balance;
use App\Model\Supplier;
use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\Auth;

class BalanceAdd extends Component
{
    public $outletId;

    public $supplier, $supplierId, $supplierInputLists, $supplierInputList;

    public $jumlah, $keterangan;

    public function mount()
    {
        $user = Auth::user();
        $this->user = Auth::user();
        $this->roleUser = RoleHelper::getRole($this->user->id);
        $this->outletUser = OutletUser::where('user_id', $this->user->id)->first();



        if ($this->roleUser->name == 'SUPER ADMIN') {
            $outlets = Outlet::all();
            $this->outletId = $outlets[0]->id;
        } else {
            $this->outletId = $this->outletUser->outlet_id;
        }      
        
        $this->resetListInput();
    }

    public function render()
    {
        $this->supplierInputLists = Supplier::where('nama', 'like', '%' . $this->supplier . '%')->skip(0)->take(5)->get();
        $outlets = Outlet::all();
        return view('livewire.balance-add',[
            'supplierInputLists' => $this->supplierInputLists,
            'outlets' => $outlets
        ]);
    }

    public function resetListInput()
    {
        $this->jumlah = 0;
        $this->supplier = '';
    }

    public function showSearchSupplier()
    {
        $this->supplierInputList = !$this->supplierInputList;
    }

    public function inputSearchSupplier()
    {
        $this->supplierInputList = true;
    }

    public function selectSupplier($supplierId, $nama)
    {
        $this->supplier = $nama;
        $this->supplierId = $supplierId;
        $this->supplierInputList = !$this->supplierInputList;
    }

    public function store()
    {
        $this->validate([
            'supplier' => 'required',
            'jumlah' => 'required|numeric',
        ]);

        $balanceCreate = Balance::create([
            'outlet_id' => $this->outletId,
            'supplier_id' => $this->supplierId,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan
        ]);       

        $this->resetListInput();

        $this->emit('getBalanceDetail');

    }

    
}
