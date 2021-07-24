<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination; 

use App\Model\InterOutlet;
use App\Model\Outlet;

class InterOutletAllDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $pihak1, $pihak2, $namaPihak1, $namaPihak2, $totalCredit;

    public function mount(Outlet $pihak1, Outlet $pihak2)
    {
        $this->pihak1 = $pihak1->id;
        $this->pihak2 = $pihak2->id;
        $this->namaPihak1 = $pihak1->nama;
        $this->namaPihak2 = $pihak2->nama;
        
    }

    protected $listeners = [
        'getAllDetail' => "showAllDetail"
    ];
    
    public function render()
    {
        $data = InterOutlet::where('pihak_1', $this->pihak1)
                            ->where('pihak_2', $this->pihak2)
                            ->orderBy('updated_at', 'desc')
                            ->paginate(10);

        return view('livewire.inter-outlet-all-detail', [
            'data' => $data
        ]);
    }

    public function backTo()
    {
        $this->emit('getOutletDetail');
    }
}
