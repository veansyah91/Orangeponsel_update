<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditCollect;

class CreditCollectDetail extends Component
{
    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public $data;

    public function render()
    {
        return view('livewire.credit-collect-detail');
    }

    public function back()
    {
        $this->emit('showIndex');
    }

    public function handleShowDetail($id)
    {
        $this->data = CreditCollect::find($id);
        
        $dataId = $this->data['credit_application_id'];

        if ($this->data['pengambilan_lama'] == 1) {
            $dataId = $this->data['credit_app_old_id'];
        }

        $this->emit('showDetail', $dataId);
    }
}
