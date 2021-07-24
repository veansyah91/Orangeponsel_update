<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class InvoiceClaimIndex extends Component
{
    public $showUpdate, $showCreate;

    public function mount()
    {
        $this->showUpdate = false;
        $this->showCreate = false;
    }

    public function render()
    {
        $invoices = DB::table('credit_application_invoices')
                        ->join('credit_applications','credit_applications.id','=','credit_application_invoices.credit_application_id')
                        ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                        ->where('credit_application_invoices.status','=','waiting')
                        ->select('credit_application_invoices.created_at','credit_application_invoices.id','credit_application_invoices.product_id','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_hp','credit_applications.merk','credit_applications.email','credit_applications.password')
                        ->get();  


        return view('livewire.invoice-claim-index', [
            "invoices" => $invoices
        ]);
    }
}
