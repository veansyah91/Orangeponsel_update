<?php

namespace App\Http\Livewire;

use App\Model\Outlet;
use Livewire\Component;
use App\Model\CreditCustomer;

class CreditCustomerUpdate extends Component
{
    public $no_ktp, $no_kk, $nama, $jenis_kelamin, $alamat, $no_hp, $outlet, $dataId;

    protected $listeners = [
        'getUpdateData' => 'handleUpdateData'
    ];

    public function render()
    {
        return view('livewire.credit-customer-update', [
            'outlets' => Outlet::all()
        ]);
    }

    public function handleUpdateData($id)
    {
        $data = CreditCustomer::find($id);

        $this->dataId = $data->id;
        $this->no_ktp = $data->no_ktp;
        $this->no_kk = $data->no_kk;
        $this->nama = $data->nama;
        $this->jenis_kelamin = $data->jenis_kelamin;
        $this->alamat = $data->alamat;
        $this->no_hp = $data->no_hp;
        $this->outlet = $data->outlet_id;        
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_kk' => 'required',
            'no_ktp' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $update = CreditCustomer::find($this->dataId)->update([
            'nama' => $this->nama,
            'no_ktp' => $this->no_ktp,
            'no_kk' => $this->no_kk,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'outlet_id' => $this->outlet
        ]);

        $this->emit('hasUpdatedData');
    }
}
