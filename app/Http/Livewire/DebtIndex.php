<?php

namespace App\Http\Livewire;

use App\Model\Outlet;

use Livewire\Component;

use App\Model\OutletUser;
use App\Model\DebtPayment;
use App\Helpers\RoleHelper;
use App\Model\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DebtIndex extends Component
{
    public $outletId, $outlets, $outlet, $selectOutlet;

    protected $listeners = [
        'getDebtPayment' => 'showDebtPayment'
    ];

    public function mount(){

        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        if ($roleUser->name == 'SUPER ADMIN') {
            # code...
            $this->outlets = Outlet::all();
            $this->selectOutlet = $this->outlets[0]->id;
        } else {
            $outletUser = OutletUser::where('user_id', $user->id)->first();
            $this->selectOutlet = $outletUser->outlet_id;
        }
        
    }

    public function render()
    {
        
        $remains = DB::table('payment_statuses')
                        ->join('invoices','invoices.id','=','payment_statuses.invoice_id')
                        ->join('customers','customers.id','=','invoices.customer_id')
                        ->where('invoices.outlet_id', $this->selectOutlet)
                        ->where('payment_statuses.sisa', '<', 0)->get();
        // dd($remains);
        return view('livewire.debt-index',[
            'remains' => $remains,
        ]);
    }

    public function debtPay($invoiceId)
    {
        $payment = PaymentStatus::where('invoice_id', $invoiceId)->first();
    }

    public function showDebtPayment()
    {

    }

}
