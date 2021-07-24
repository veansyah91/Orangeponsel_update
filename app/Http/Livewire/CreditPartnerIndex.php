<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPartner;

class CreditPartnerIndex extends Component
{
    public $showUpdate;

    protected $listeners = [
        'getCreditPartner' => 'showCreditPartner',
        'getCreditPartnerStatus' => 'handleCreditPartnerUpdate'
    ];

    public function mount()
    {
        $this->showUpdate = false;
    }

    public function render()
    {
        $data = CreditPartner::all();
        return view('livewire.credit-partner-index',[
            'data' => $data
        ]);
    }

    public function showCreditPartner()
    {
        session()->flash('success', "Mitra Berhasil Ditambah");
    }

    public function updateCreditPartner($id)
    {
        $this->showUpdate = true;
        $this->emit('getCreditPartnerUpdate', $id);
    }

    public function cancelUpdate()
    {
        $this->showUpdate = false;    
    }

    public function delete($id)
    {
        $delete = CreditPartner::find($id)->delete();
        session()->flash('success', "Mitra Berhasil Dihapus");
    }

    public function handleCreditPartnerUpdate()
    {
        session()->flash('success', "Mitra Berhasil Diubah");
        $this->showUpdate = false; 
    }
}
