<?php

namespace App\Http\Livewire;

use App\Model\Product;
use Livewire\Component;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use App\Model\CreditApplicationInvoice;

class CreditApplicationDetail extends Component
{
    public $credit_app_id;

    protected $listeners = [
        'showDetail' => 'handleShowDetail'
    ];

    public function render()
    {
        $creditApplication = CreditApplication::find($this->credit_app_id);

        $creditCustomer = CreditCustomer::find($creditApplication["credit_customer_id"]);

        $creditApplicationInvoice = CreditApplicationInvoice::where('credit_application_id', $creditApplication["id"])->first();

        $product = Product::find($creditApplicationInvoice['product_id']);

        return view('livewire.credit-application-detail', [
            'creditApplication' => $creditApplication,
            'creditCustomer' => $creditCustomer,
            'creditApplicationInvoice' => $creditApplicationInvoice,
            'product' => $product
        ]);
    }

    public function backButton()
    {
        $this->emit('showHistories');
    }

    public function handleShowDetail($id)
    {
        $this->credit_app_id = $id;    }
}
