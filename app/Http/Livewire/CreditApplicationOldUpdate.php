<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditApplicationOld;

class CreditApplicationOldUpdate extends Component
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
    public $dataId;

    protected $listeners = [
        'show' => 'handleShow'
    ];

    public function render()
    {
        return view('livewire.credit-application-old-update');
    }

    public function backButton()
    {
        $this->emit('showIndex');
    }

    public function handleShow($id)
    {
        $data = CreditApplicationOld::find($id);
        $this->dataId = $data['id'];
        $this->nomor_akad = $data['nomor_akad'];
        $this->nama = $data['nama'];
        $this->no_hp = $data['no_hp'];
        $this->tipe = $data['tipe'];
        $this->tanggal_akad = $data['tanggal_akad'];
        $this->tgl_terakhir_bayar = $data['tgl_terakhir_bayar'];
        $this->dp = $data['dp'];
        $this->angsuran = $data['angsuran'];
        $this->tenor = $data['tenor'];
        $this->total = $data['total'];
        $this->sisa = $data['sisa'];
        $this->total_bayar = $data['total_bayar'];
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

        $updateData = CreditApplicationOld::find($this->dataId)->update([
            'nama' => strtoupper($this->nama),
            'no_hp' => $this->no_hp,
            'tipe' => $this->tipe,
            'tanggal_akad' => $this->tanggal_akad,
            'tgl_terakhir_bayar' => $this->tgl_terakhir_bayar,
            'dp' => $this->dp,
            'angsuran' => $this->angsuran,
            'tenor' => $this->tenor,
            'total' => $this->total,
            'total_bayar' => $this->total_bayar,
            'sisa' => $this->sisa,
            'nomor_akad' => $this->nomor_akad,
        ]);

        $this->emit('showIndex');
    }
}
