<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPartner;

class CreditPartnerUpdate extends Component
{
    public $nama, $alamat, $idCreditPartner, $alias;

    protected $listeners = [
        'getCreditPartnerUpdate' => 'showCreditPartnerUpdate'
    ];

    public function render()
    {
        return view('livewire.credit-partner-update');
    }

    public function showCreditPartnerUpdate($id)
    {
        $creditPartner = CreditPartner::find($id);
        $this->idCreditPartner = $creditPartner->id;
        $this->nama = $creditPartner->nama_mitra;
        $this->alias = $creditPartner->alias;
        $this->alamat = $creditPartner->alamat;
    }

    public function update()
    {
        $update = CreditPartner::find($this->idCreditPartner)->update([
            'nama_mitra' => $this->nama,
            'alias' => $this->alias,
            'alamat' => $this->alamat,
        ]);

        $this->emit('getCreditPartnerStatus');
    }
}
