<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\DB;

class InvoiceBalanceEdit extends Component
{
    // public $outletId, $supplierId, $suppliers, $modal, $jual, $keterangan, $nomorId, $transactionId;

    // protected $listeners = [
    //     'getTransaction' => 'showTransaction'
    // ];

    public function render()
    {   
        // $servers = DB::table('balances')
        //                 ->join('suppliers', 'balances.supplier_id', '=', 'suppliers.id')
        //                 ->where('balances.outlet_id', $this->outletId)
        //                 ->select('balances.supplier_id','suppliers.nama')
        //                 ->distinct()
        //                 ->get();

        return view('livewire.invoice-balance-edit', [
            // 'servers' => $servers
        ]);
    }

    // public function showTransaction($id)
    // {
    //     $transaction = BalanceTransaction::find($id);

    //     $this->transactionId = $transaction->id;
    //     $this->outletId = $transaction->outlet_id;
    //     $this->supplierId = $transaction->supplier_id;
    //     $this->jual = $transaction->jual;
    //     $this->modal = $transaction->modal;
    //     $this->keterangan = $transaction->keterangan;
    //     $this->nomorId = $transaction->nomorId;
    // }

    // public function update()
    // {
    //     $update = BalanceTransaction::find($this->transactionId)->update([
    //         'outlet_id' => $this->outletId,
    //         'supplier_id' => $this->supplierId,
    //         'jual' => $this->jual,
    //         'modal' => $this->modal,
    //         'keterangan' => $this->keterangan,
    //         'nomorId' => $this->nomorId,
    //     ]);

    //     $this->emit('getTransactionDetail');
    // }
}
