<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Model\Customer;

class CustomerIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $paginate = 5;

    public $showCustomer = false;
    public $showCreate = false;
    public $showUpdate = false;
    public $search = '';

    protected $listeners = [
        'customerStored' => 'handleStored',
        'customerUpdated' => 'handleUpdated',
    ];

    public function mount(){
        $this->showCustomer();
    }

    public function render()
    {
        return view('livewire.customer-index',[
            'data' => $this->search == '' ? 
                      Customer::paginate($this->paginate) :
                      Customer::where('nama', 'like', '%' . $this->search . '%')->paginate($this->paginate)
        ]);
    }

    public function showCustomer(){
        $this->showCustomer = true;
        $this->showCreate = false;
        $this->showUpdate = false;
    }

    public function getCustomer($id){
        $this->showCustomer = false;
        $this->showUpdate = true;
        $customer = Customer::find($id);
        $this->emit('getCustomer', $customer);
    }

    public function showCreate(){
        $this->showCustomer = false;
        $this->showCreate = true;
    }

    public function destroy($id){
        if ($id) {
            $customer = Customer::find($id);
            $customer->delete();
            session()->flash('success', "Pelanggan Berhasil Dihapus");
        }
    }

    public function handleStored(){
        session()->flash('success', "Pelanggan Berhasil Ditambah");
        $this->showCustomer();
    }

    public function handleUpdated(){
        session()->flash('success', "Pelanggan Berhasil Diubah");
        $this->showCustomer();
    }
}
