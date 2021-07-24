<?php

namespace App\Http\Livewire;

use App\Model\Product;
use Livewire\Component;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;

class CreditApplicationUpdate extends Component
{
    public $creditCustomerName, $creditCustomerId, $searchName, $showNameSearch, $merk, $searchType, $showTypeSearch, $tenor, $angsuran, $dp, $status, $email, $password, $outlet, $partnerId;

    public $dataId;

    protected $listeners = [
        'getUpdateData' => 'handleUpdateData'
    ];

    public function render()
    {
        $creditCustomers = CreditCustomer::where('no_ktp', 'like', '%' . $this->searchName . '%')
                                            ->skip(0)
                                            ->take(5)
                                            ->get();

        $products = Product::where('tipe', 'like', '%' . $this->searchType . '%') 
                            ->select('tipe')
                            ->distinct()
                            ->get();

        return view('livewire.credit-application-update',[
            'creditCustomers' => $creditCustomers,
            'products' => $products
        ]);
    }

    public function handleUpdateData($id)
    {
        $data = CreditApplication::find($id);

        $this->dataId = $data->id;
        $this->creditCustomerId = $data->credit_customer_id;
        $this->creditCustomerName = $data->creditCustomer->nama;
        $this->merk = $data->merk;
        $this->tenor = $data->tenor;
        $this->angsuran = $data->angsuran;
        $this->outlet = $data->outlet_id;
        $this->dp = $data->dp;

    }

    public function selectCustomer($id ,$name)
    {
        $this->creditCustomerName = $name;
        $this->creditCustomerId = $id;
        $this->showNameSearch = !$this->showNameSearch;
    }

    public function showNameSearch()
    {
        $this->showNameSearch = !$this->showNameSearch;
    }

    public function showTypeSearch()
    {
        $this->showTypeSearch = !$this->showTypeSearch;
    }

    public function selectProduct($name)
    {
        $this->merk = $name;
        $this->showTypeSearch = !$this->showTypeSearch;
    }

    public function update()
    {
        $total = $this->dp + $this->tenor * $this->angsuran;
        
        $update = CreditApplication::find($this->dataId)->update([
            'credit_customer_id' => $this->creditCustomerId,
            'merk' => $this->merk,
            'tenor' => $this->tenor,
            'dp' => $this->dp,
            'angsuran' => $this->angsuran,
            'total' => $total,
        ]);

        $this->emit('hasUpdated');
    }
}
