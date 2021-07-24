<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination; 

use App\Model\Outlet;
use App\Model\InterOutlet;

class InterOutletDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $outletId, $outletDetail, $selectOutlet, $invoiceId, $showDebt, $showCredit, $createCredit, $updateCredit, $showAll;

    public $totalCredit, $totalDebt, $totalShowCredit, $totalShowDebt;

    public function mount($selectOutlet, $outletId)
    {
        $this->outletDetail = Outlet::find($outletId);
        $this->outletId = $outletId;
        $this->selectOutlet = $selectOutlet;
        $this->invoiceDetail = '';
        $this->showDebt = false;
        $this->showCredit = false;
        $this->createCredit = false;
        $this->updateCredit = false;
        $this->showAll =  true;
        $this->totalShowCredit = 10;
        $this->totalShowDebt = 10;
    }

    protected $listeners = [
        'getOutlet' => "showOutlet",
        'getOutletDetail' => "showOutletDetail"
    ];

    public function render()
    {
        $credits = InterOutlet::where('pihak_1',$this->outletId)
                                ->where('pihak_2', $this->selectOutlet)
                                ->orderBy('updated_at', 'desc')
                                ->take($this->totalShowCredit)->get();

        $debts = InterOutlet::where('pihak_1',$this->selectOutlet)
                            ->where('pihak_2', $this->outletId)
                            ->orderBy('updated_at', 'desc')
                            ->take($this->totalShowDebt)->get();

        $countCredits = InterOutlet::where('pihak_1',$this->outletId)
                                    ->where('pihak_2', $this->selectOutlet)
                                    ->orderBy('updated_at', 'desc')
                                    ->count();

        $countDebts = InterOutlet::where('pihak_1',$this->selectOutlet)
                                    ->where('pihak_2', $this->outletId)
                                    ->orderBy('updated_at', 'desc')
                                    ->count();

        $sumDebts = InterOutlet::where('pihak_1',$this->selectOutlet)
                                    ->where('pihak_2', $this->outletId)
                                    ->orderBy('updated_at', 'desc')
                                    ->get()->sum('jumlah');

        $sumCredits = InterOutlet::where('pihak_1',$this->outletId)
                                    ->where('pihak_2', $this->selectOutlet)
                                    ->orderBy('updated_at', 'desc')
                                    ->get()->sum('jumlah');
                                    // dd($sumCredits);

        return view('livewire.inter-outlet-detail',[
                    'credits' => $credits,
                    'debts' => $debts,
                    'countCredits' => $countCredits,
                    'countDebts' => $countDebts,
                    'sumCredits' => $sumCredits,
                    'sumDebts' => $sumDebts,
        ]);
    }

    public function showOutlet($outletId, $selectOutlet)
    {   
        $this->totalShowCredit = 10;
        $this->totalShowDebt = 10;
        $this->outletId = $outletId;
        $this->selectOutlet = $selectOutlet;
    }

    public function showDebt($invoiceId)
    {
        $this->showDebt = true;
        $this->showAll =  false;
        $this->emit('getInvoiceDetail', $invoiceId);
    }

    public function showCredit($invoiceId)
    {
        $this->showCredit = true;
        $this->showAll =  false;
        $this->emit('getInvoiceDetail', $invoiceId);
    }

    public function showOutletDetail()
    {
        $this->showDebt = false;
        $this->showCredit = false;
        $this->createCredit = false;
        $this->updateCredit = false;
        $this->showAllCredit =  true;
    }

    public function checked($id)
    {
        $update = InterOutlet::where('id',$id)->update([
            'konfirmasi' => 'checked'
        ]);
    }

    public function createCredit()
    {
        $this->createCredit = true;
        $this->emit('getCreateCredit', $this->outletId, $this->selectOutlet);
    }

    public function updateCredit($dataId)
    {
        $this->updateCredit = true;
        $this->emit('getUpdateCredit', $dataId);
    }

    public function showOtherDebts()
    {
        $this->totalShowDebt += 10;
    }

    public function showOtherCredits()
    {
        $this->totalShowCredit += 10;
    }
}
