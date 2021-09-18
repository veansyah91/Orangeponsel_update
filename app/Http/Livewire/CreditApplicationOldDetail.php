<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPaymentOld;
use App\Model\CreditApplicationOld;

class CreditApplicationOldDetail extends Component
{
    public $dataId;

    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];
    
    public function render()
    {
        $data = CreditApplicationOld::find($this->dataId);
        $creditPayments = CreditPaymentOld::where('credit_app_old_id', $this->dataId)->get();

        return view('livewire.credit-application-old-detail', [
            'data' => $data,
            'creditPayments' => $creditPayments
        ]);
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->dataId = $id;
    }
}
