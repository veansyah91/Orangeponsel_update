<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPaymentOld;
use App\Model\CreditApplicationOld;

class CreditPaymentOldDetail extends Component
{
    public $dataId;
    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public function render()
    { 
        $data = CreditPaymentOld::find($this->dataId);
        $customer = CreditApplicationOld::find($data['credit_app_old_id']);
        
        return view('livewire.credit-payment-old-detail', [
            'data' => $data,
            'customer' => $customer
        ]);
    }

    public function backToIndex()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->dataId = $id;
    }
}
