<?php

namespace App\Http\Livewire;

use App\Model\Invoice;
use Livewire\Component;
use App\Helpers\InvoiceHelper;
use App\Helpers\CustomerHelper;
use Illuminate\Support\Facades\DB;

class InvoiceDetail extends Component
{
    public $invoiceId, $customer, $details;

    protected $listeners = [
        'getDetail' => 'showDetail'
    ];

    public function render()
    {
        return view('livewire.invoice-detail',
        [
            'data' => $this->details
        ]
    );
    }

    public function showDetail($id)
    {
        $detailInvoice = Invoice::find($id); 
        $this->invoiceId = $detailInvoice->id;
        $this->customer = CustomerHelper::getName($detailInvoice->customer_id)->nama;
        $this->details = DB::table('invoice_details')
                            ->join('products','invoice_details.product_id','=','products.id')
                            ->where('invoice_details.invoice_id', $detailInvoice->id)
                            ->select('products.kode','products.tipe','invoice_details.jumlah','invoice_details.jual')
                            ->get();
        // dd($this->details);
     }

}
