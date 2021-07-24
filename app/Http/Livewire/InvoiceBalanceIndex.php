<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use App\Model\Supplier;

use Livewire\Component;

use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Livewire\WithPagination; 
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\Auth;

class InvoiceBalanceIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $showUpdate;

    public $user, $roleUser, $outletUser, $outletId, $outlets;

    public $delete;

    protected $listeners = [
        'getTransactionDetail' => 'handleShowTransactionDetail',
        'getCancelUpdate' => 'handleCancelTransactionUpdate'
    ];

    public function mount()
    {
        $this->showUpdate = false;

        $this->user = Auth::user();
        $this->roleUser = RoleHelper::getRole($this->user->id);
        $this->outletUser = OutletUser::where('user_id', $this->user->id)->first();

        $this->outlets = Outlet::all();

        $this->delete = false;

        if($this->roleUser->name == 'SUPER ADMIN'){
            $this->outletId = $this->outlets[0]->id;
        } else{
            $this->outletId = $this->outletUser->outlet_id;
        }
    }

    public function render()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->tanggal = Date('Y-m-d');

        $data = BalanceTransaction::where('outlet_id', $this->outletId)->where('updated_at', 'like', $this->tanggal . '%')->paginate(10);
        $servers = Supplier::all();

        return view('livewire.invoice-balance-index',[
            // 'outlets' => $this->outlets,
            'data' => $data
        ]);
    }

    public function changeOutlet()
    {
        $this->emit('getOutlet', $this->outletId);
    }

    public function handleShowTransactionDetail()
    {
        $this->showUpdate = false;
        $this->emit('getRemains');
    }

    public function cancel()
    {
        $this->showUpdate = false;
    }

    public function deleteConfirmation($id)
    {
        $delete = BalanceTransaction::where('id',$id)->delete();
    }

    public function editTransaksiSaldo($id)
    {
        $this->showUpdate = true;
        // $this->emit('getTransaction', $id);        
    }

    public function editTransaction()
    {
        $this->showUpdate = !$this->showUpdate;
        $this->nilai = 2;
    }

   
}
