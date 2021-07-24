<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\DB;

class InvoiceBalanceCreate extends Component
{
    public $outletId, $supplierId, $suppliers, $modal, $jual, $keterangan, $nomorId;

    protected $listeners = [
        'getOutlet' => 'showOutlet'
    ];

    public function mount($outletId)
    {
        $this->outletId = $outletId;
        $this->resetInput();
    }

    public function render()
    {
        $servers = DB::table('balances')
                                ->join('suppliers', 'balances.supplier_id', '=', 'suppliers.id')
                                ->where('balances.outlet_id', $this->outletId)
                                ->select('balances.supplier_id','suppliers.nama')
                                ->distinct()
                                ->get();
                                
        return view('livewire.invoice-balance-create',[
            'servers' => $servers
        ]);
    }

    public function showOutlet($outletId)
    {
        $this->outletId = $outletId;
    }

    public function store()
    {
        $storeBalanceTransaction = BalanceTransaction::create([
            'outlet_id' => $this->outletId,
            'supplier_id' => $this->supplierId,
            'jual' => $this->jual,
            'modal' => $this->modal,
            'keterangan' => $this->keterangan,
            'nomorId' => $this->nomorId
        ]);

        $this->resetInput();
        $this->emit('getTransactionDetail');
    }


    public function resetInput()
    {
        $this->nomorId = '';
        $this->keterangan = '';
        $this->modal = 0;
        $this->jual = 0;

        $suppliers = DB::table('balances')
                                ->join('suppliers', 'balances.supplier_id', '=', 'suppliers.id')
                                // ->where('balances.outlet_id', $this->outletId)
                                ->select('balances.supplier_id','suppliers.nama')
                                ->distinct()
                                ->get();

        $this->supplierId = $suppliers[0]->supplier_id;        
    }
}
