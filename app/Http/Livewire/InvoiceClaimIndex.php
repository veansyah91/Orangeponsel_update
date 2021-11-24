<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditPartner;
use Illuminate\Support\Facades\DB;
use App\Model\CreditPartnerInvoice;
use App\Model\CreditApplicationInvoice;
use App\Model\CreditInvoiceClaimDetail;

class InvoiceClaimIndex extends Component
{
    public $showUpdate, $showCreate;

    public $showPreviewStatus;

    public $partnerId;

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->showUpdate = false;
        $this->showCreate = false;

        $this->showPreviewStatus = false;
    }

    public function render()
    {
        $invoices = DB::table('credit_application_invoices')
                        ->join('credit_applications','credit_applications.id','=','credit_application_invoices.credit_application_id')
                        ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                        ->where('credit_applications.credit_partner_id', $this->partnerId)
                        ->where('credit_application_invoices.status','=','waiting')
                        ->select('credit_application_invoices.created_at','credit_application_invoices.harga as harga','credit_application_invoices.id','credit_application_invoices.product_id','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_hp','credit_applications.merk','credit_applications.email','credit_applications.password')
                        ->get();  

        $creditPartnerInvoices = CreditPartnerInvoice::where('credit_partner_id', $this->partnerId)->get();

        $lastInvoice = CreditPartnerInvoice::where('credit_partner_id', $this->partnerId)->get()->last();

        $lastInvoice = $lastInvoice ? $lastInvoice->nomor + 1 : 1;

        $creditPartner = CreditPartner::find($this->partnerId);
                        
        return view('livewire.invoice-claim-index', [
            "invoices" => $invoices,
            "invoiceNumber" => $lastInvoice,
            "creditPartnerInvoices" => $creditPartnerInvoices,
            "creditPartner" => $creditPartner,
        ]);
    }

    public function showPreviewFunc()
    {
        $this->showPreviewStatus = true;
    }

    public function updateStatus($id)
    {
        $update = CreditPartnerInvoice::find($id);

        $update->update([
                    'status' => 'paid'
                ]);
                    

        $credit_invoice_claim_details = CreditInvoiceClaimDetail::where('credit_partner_invoice_id', $update['id'])->get();

        // update status in credit application invoices table
        foreach ($credit_invoice_claim_details  as $credit_invoice_claim_detail) {
            $updateInvoice = CreditApplicationInvoice::find($credit_invoice_claim_detail->credit_app_inv_id)->update([
                'status' => 'paid'
            ]);
        }
    }
}
