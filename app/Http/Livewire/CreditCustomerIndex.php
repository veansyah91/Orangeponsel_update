<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Model\CreditCustomer;

class CreditCustomerIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $showUpdate, $showCreate;

    public $paginate, $search;

    protected $listeners = [
        'hasStoredData' => 'handleStoredData',
        'hasUpdatedData' => 'handleUpdatedData'
    ];

    public function mount()
    {
        $this->showUpdate = false;
        $this->showCreate = false;
        $this->paginate = 5;
    }

    public function render()
    {
        $data = CreditCustomer::where('nama', 'like', '%' . $this->search . '%')
                                ->orWhere('no_ktp', 'like', '%' . $this->search . '%')
                                ->orWhere('no_kk', 'like', '%' . $this->search . '%')
                                ->orWhere('alamat', 'like', '%' . $this->search . '%')
                                ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                                ->paginate($this->paginate);

        return view('livewire.credit-customer-index', [
            'data' => $data
        ]);
    }

    public function showCreate()
    {
        $this->showCreate = true;
    }

    public function cancelCreate()
    {
        $this->showCreate = false;
    }

    public function handleStoredData()
    {
        $this->showCreate = false;
        session()->flash('success', "Data Calon Konsumen Kredit Berhasil Ditambah");
    }

    public function delete($id)
    {
        $delete = CreditCustomer::find($id)->delete();
        session()->flash('success', "Data Konsumen Kredit Berhasil Dihapus");
    }

    public function update($id)
    {
        $this->showUpdate = true;
        $this->emit("getUpdateData", $id);
    }

    public function cancelUpdate()
    {
        $this->showUpdate = false;
    }

    public function handleUpdatedData()
    {
        $this->showUpdate = false;
        session()->flash('success', "Data Konsumen Kredit Berhasil Diubah");
    }
}
