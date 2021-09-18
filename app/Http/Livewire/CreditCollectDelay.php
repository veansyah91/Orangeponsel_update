<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreditCollectDelay extends Component
{
    public $partnerId;

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
    }

    public function render()
    {
        $data = DB::table('credit_collects')
                    ->where('credit_partner_id', $this->partnerId)
                    ->whereNotNull('tenggang')
                    ->where('terlambat', '>', 0)
                    ->orderBy('tanggal_penagihan','desc')
                    ->paginate(10);

        return view('livewire.credit-collect-delay', [
            'data' => $data
        ]);
    }

    
}
