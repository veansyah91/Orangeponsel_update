<?php

namespace App\Http\Livewire;

use App\Model\Balance;
use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BalanceIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $editTransaksiSaldo;

    protected $listeners = [
        'getBalanceDetail' => 'showBalanceDetail'
    ];

    public function mount()
    {
        $this->editTransaksiSaldo = false;
    }

    public function render()
    {
        $user = Auth::user();
        $this->user = Auth::user();
        $this->roleUser = RoleHelper::getRole($this->user->id);
        $this->outletUser = OutletUser::where('user_id', $this->user->id)->first();
        
        $balanceDetails = $this->roleUser->name == 'SUPER ADMIN' ? 
                            Balance::orderBy('updated_at', 'desc')->paginate(10):
                            Balance::where('outlet_id', $this->outletUser->outlet_id)->orderBy('updated_at', 'desc')->paginate(10);
                            
        return view('livewire.balance-index', [
            'balanceDetails' => $balanceDetails
        ]);
    }

    public function deleteConfirmation($id)
    {
        $delete = Balance::find($id)->delete();
    }

    public function editTransaksiSaldo($id)
    {
        $this->editTransaksiSaldo = !$this->editTransaksiSaldo;
        $this->emit('getBalance', $id);
    }

    public function showBalanceDetail()
    {
        $this->editTransaksiSaldo = false;
    }


}
