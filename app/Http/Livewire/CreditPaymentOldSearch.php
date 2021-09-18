<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreditPaymentOldSearch extends Component
{
    public $search;

    public $partnerId;

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
    }
    
    public function render()
    {
        $creditCustomers = DB::table('credit_application_olds')
                                ->where('credit_partner_id', $this->partnerId)
                                ->where('sisa', '>', 0) 
                                ->where(function($query) {
                                    $query->where('nama', 'like', '%' . $this->search . '%')
                                          ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                                })       
                                ->skip(0)
                                ->take(5)
                                ->get();
        return view('livewire.credit-payment-old-search', [
            'creditCustomers' => $creditCustomers
        ]);
    }

    public function backToIndex()
    {
        $this->emit('showInputPayment');
    }

    public function select($id)
    {
        $this->emit('selectId', $id);
    }
}
