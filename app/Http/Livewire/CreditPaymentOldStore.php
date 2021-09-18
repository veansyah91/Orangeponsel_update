<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use Livewire\Component;
use App\Model\OutletUser;
use App\Model\CreditSales;
use App\Model\CreditPaymentOld;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditPaymentOldStore extends Component
{

    public $outlet;

    public $sales_name; 

    public $partnerId;

    public function mount($partnerId)
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user->id)->first();
        $this->outlet = Outlet::find($outletUser['outlet_id']);
        $this->partnerId = $partnerId;
    }

    public function render()
    {
        $creditPayments = DB::table('credit_payment_olds')
                            ->join('credit_application_olds','credit_application_olds.id','=','credit_payment_olds.credit_app_old_id')
                            ->where('credit_application_olds.credit_partner_id', $this->partnerId)
                            ->where('credit_payment_olds.outlet', $this->outlet['nama'])
                            ->where('credit_payment_olds.status', '0')
                            ->get();

        $creditSales = CreditSales::where('credit_partner_id', $this->partnerId)->get();
        
        return view('livewire.credit-payment-old-store', [
            'creditPayments' => $creditPayments,
            'creditSales' => $creditSales
        ]);
    }

    public function backToIndex()
    {
        $this->emit('showIndex');
    }

    public function store()
    {
        $this->validate([
            'sales_name' => 'required',
        ]);

        $creditPayments = DB::table('credit_payment_olds')
                            ->join('credit_application_olds','credit_application_olds.id','=','credit_payment_olds.credit_app_old_id')
                            ->where('credit_application_olds.credit_partner_id', $this->partnerId)
                            ->where('credit_payment_olds.outlet', $this->outlet['nama'])
                            ->where('credit_payment_olds.status', '0')
                            ->select('credit_payment_olds.id')
                            ->get();

        foreach ($creditPayments as $creditPayment) {
            $update = CreditPaymentOld::find($creditPayment->id)->update([
                'status' => '1',
                'kolektor' => $this->sales_name
            ]);
        }
        
        $this->emit('showIndex');
    }
}
