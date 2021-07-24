<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Model\Supplier;

class SupplierIndex extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;

    public $showSupplier = false;
    public $showCreate = false;
    public $showUpdate = false;
    public $search = '';

    protected $listeners = [
        'supplierStored' => 'handleStored',
        'supplierUpdated' => 'handleUpdated',
    ];

    public function render()
    {
        $this->showSupplier = true;
        return view('livewire.supplier-index', [
            'data' => $this->search == '' ? 
                      Supplier::paginate($this->paginate) :
                      Supplier::where('nama', 'like', '%' . $this->search . '%')->orWhere('alamat', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function showCreate(){
        $this->showSupplier = false;
        $this->showCreate = true;
    }

    public function showSupplier(){
        $this->showSupplier = true;
        $this->showCreate = false;
        $this->showUpdate= false;
    }

    public function getSupplier($id){
        $this->showSupplier = false;
        $this->showUpdate = true;
        $supplier = Supplier::find($id);
        $this->emit('getSupplier', $supplier);
    }

    public function destroy($id){
        if ($id) {
            $supplier = Supplier::find($id);
            $supplier->delete();
            session()->flash('success', "Pemasok Berhasil Dihapus");
        }
    }

    public function handleStored(){
        session()->flash('success', "Pemasok Berhasil Ditambah");
        $this->showSupplier();
    }

    public function handleUpdated(){
        session()->flash('success', "Pemasok Berhasil Diubah");
        $this->showSupplier();
    }
}
