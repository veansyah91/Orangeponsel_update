<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditApplicationOld;

class CreditApplicationOldCreate extends Component
{
    public $nomor_akad;
    public $nama;
    public $no_hp;
    public $tipe;
    public $tanggal_akad;
    public $tgl_terakhir_bayar;
    public $dp;
    public $angsuran;
    public $tenor;
    public $total;
    public $total_bayar;
    public $sisa;
    public $partnerId;

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
    }

    public function render()
    {
        return view('livewire.credit-application-old-create');
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'tipe' => 'required',
            'tanggal_akad' => 'required|date',
            'tgl_terakhir_bayar' => 'required|date',
            'dp' => 'required|numeric',
            'angsuran' => 'required|numeric',
            'tenor' => 'required|numeric',
            'total' => 'required|numeric',
            'total_bayar' => 'required|numeric',
            'sisa' => 'required|numeric',
        ]);

        $createData = CreditApplicationOld::create([
            'nama' => strtoupper($this->nama),
            'no_hp' => $this->no_hp,
            'tipe' => strtoupper($this->tipe),
            'tanggal_akad' => $this->tanggal_akad,
            'tgl_terakhir_bayar' => $this->tgl_terakhir_bayar,
            'dp' => $this->dp,
            'angsuran' => $this->angsuran,
            'tenor' => $this->tenor,
            'total' => $this->total,
            'total_bayar' => $this->total_bayar,
            'sisa' => $this->sisa,
            'nomor_akad' => $this->nomor_akad,
            'credit_partner_id' => $this->partnerId
        ]);

        $this->emit('showIndex');
    }
}
