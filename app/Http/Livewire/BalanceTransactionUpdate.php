<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\DB;

class BalanceTransactionUpdate extends Component
{
    public $outletId, $supplierId, $suppliers, $modal, $jual, $keterangan, $nomorId, $transactionId;

    protected $listeners = [
        'updateTransaksiSaldo' => 'handleUpdateTransaksiSaldo'
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

        return view('livewire.balance-transaction-update', [
            'servers' => $servers
        ]);
    }

    public function handleUpdateTransaksiSaldo($id)
    {
        $transaction = BalanceTransaction::find($id);
        $this->transactionId = $transaction->id;
        $this->nomorId = $transaction->nomorId;
        $this->keterangan = $transaction->keterangan;
        $this->modal = $transaction->modal;
        $this->jual = $transaction->jual;
        $this->supplierId = $transaction->supplier_id;
    }

    public function update()
    {
        $update = BalanceTransaction::where('id', $this->transactionId)->update([
            'supplier_id' => $this->supplierId,
            'modal' => $this->modal,
            'jual' => $this->jual,
            'keterangan' => $this->keterangan,
            'nomorId' => $this->nomorId,
        ]);

        $this->emit('finishUpdateTransaction');
    }
}
