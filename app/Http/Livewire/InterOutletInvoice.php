<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Model\InvoiceDetail;

class InterOutletInvoice extends Component
{
    public $invoiceId;

    protected $listeners = [
        'getInvoiceDetail' => "showInvoiceDetail"
    ];

    public function render()
    {
        $data = InvoiceDetail::where('invoice_id', $this->invoiceId)->get();
        return view('livewire.inter-outlet-invoice',[
            'data' => $data
        ]);
    }

    public function showInvoiceDetail($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function backTo()
    {
        $this->emit('getOutletDetail');
    }
}
