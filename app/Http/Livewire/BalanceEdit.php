<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use App\Model\Balance;
use App\Model\Supplier;
use Livewire\Component;

class BalanceEdit extends Component
{
    public $balance;

    public $supplierInputList, $supplierInputLists, $supplierId, $supplier;

    public $balanceId;

    public $outletId;

    public $jumlah, $keterangan;

    public function mount()
    {
        $this->supplierInputList = false;
    }

    protected $listeners = [
        'getBalance' => 'showBalance'
    ];

    public function render()
    {
        $this->supplierInputLists = Supplier::where('nama', 'like', '%' . $this->supplier . '%')->skip(0)->take(5)->get();
        $outlets = Outlet::all();
        return view('livewire.balance-edit', [
            'outlets' => $outlets,
            'supplierInputLists' => $this->supplierInputLists,
        ]);
    }

    public function showBalance($id)
    {
        $balance = Balance::find($id);
        $this->balanceId = $balance->id;
        $this->outletId = $balance->outlet_id;
        $this->supplierId = $balance->supplier_id;
        $supplier = Supplier::find($balance->supplier_id);
        $this->supplier = $supplier->nama;
        $this->jumlah = $balance->jumlah;
    }   

    public function showSearchSupplier()
    {
        $this->supplierInputList = !$this->supplierInputList;
    }

    public function inputSearchSupplier()
    {
        $this->supplierInputList = true;
    }

    public function selectSupplier($supplierId, $nama)
    {
        $this->supplier = $nama;
        $this->supplierId = $supplierId;
        $this->supplierInputList = !$this->supplierInputList;
    }

    public function update()
    {
        $balanceCreate = Balance::find($this->balanceId)->update([
            'outlet_id' => $this->outletId,
            'supplier_id' => $this->supplierId,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan
        ]);       

        $this->emit('getBalanceDetail');
    }

    public function cancelUpdate()
    {
        $this->emit('getBalanceDetail');
    }
}
