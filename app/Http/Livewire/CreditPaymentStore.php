<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use Livewire\Component;
use App\Model\OutletUser;
use App\Model\CreditSales;
use App\Model\CreditPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditPaymentStore extends Component
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
        $creditPayments = DB::table('credit_payments')
                            ->join('credit_applications','credit_applications.id','=','credit_payments.credit_application_id')
                            ->where('credit_applications.credit_partner_id', $this->partnerId)
                            ->where('credit_payments.outlet', $this->outlet['nama'])
                            ->where('credit_payments.status', '0')
                            ->get();

        $creditSales = CreditSales::where('credit_partner_id', $this->partnerId)->get();
        
        return view('livewire.credit-payment-store', [
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

        $creditPayments = DB::table('credit_payments')
                            ->join('credit_applications','credit_applications.id','=','credit_payments.credit_application_id')
                            ->where('credit_applications.credit_partner_id', $this->partnerId)
                            ->where('credit_payments.outlet', $this->outlet['nama'])
                            ->where('credit_payments.status', '0')
                            ->select('credit_payments.id')
                            ->get();

        foreach ($creditPayments as $creditPayment) {
            $update = CreditPayment::find($creditPayment->id)->update([
                'status' => '1',
                'sales_name' => $this->sales_name
            ]);
        }
        
        $this->emit('showIndex');
    }
}
