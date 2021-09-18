<?php

namespace App\Http\Livewire;

use App\Model\Customer;
use Livewire\Component;
use App\Model\CreditPayment;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;

class CreditPaymentDetail extends Component
{
    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public $credit_payment_id;

    public function render()
    {
        $creditPayment = CreditPayment::find($this->credit_payment_id);

        $creditAppDetail = CreditApplication::find($creditPayment['credit_application_id']);

        $customer = CreditCustomer::find($creditAppDetail['credit_customer_id']);
        
        return view('livewire.credit-payment-detail', [
            'creditPayment' => $creditPayment, 
            'creditAppDetail' => $creditAppDetail,
            'customer' => $customer,
        ]);
    }

    public function backToIndex()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->credit_payment_id = $id;
    }
}
