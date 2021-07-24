<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\DB;

class BalanceRemain extends Component
{
    public $outletId;

    protected $listeners = [
        'getRemains' => 'showRemains'
    ];

    public function mount($outletId)
    {
        $this->outletId = $outletId;    
    }

    public function render()
    {
        $servers = DB::table('balances')
                                ->join('suppliers', 'balances.supplier_id', '=', 'suppliers.id')
                                ->where('balances.outlet_id', $this->outletId)
                                ->select('balances.supplier_id','suppliers.nama')
                                ->distinct()
                                ->get();
        return view('livewire.balance-remain', [
            'servers' => $servers
        ]);
    }

    public function showRemains()
    {
        
    }
}
