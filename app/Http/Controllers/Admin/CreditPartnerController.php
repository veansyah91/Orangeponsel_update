<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Model\CreditPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CreditPartnerInvoice;
use App\Http\Controllers\Controller;
use App\Model\CreditApplicationInvoice;
use App\Model\CreditInvoiceClaimDetail;

class CreditPartnerController extends Controller
{
    public function index()
    {
        return view('admin.credit.credit-partner');
    }

    public function customer($partner)
    {
        return view('admin.credit.credit-customer', ['partner' => $partner]);
    }

    public function proposal($partner)
    {
        return view('admin.credit.credit-application', ['partner' => $partner]);
    }

    public function invoice($partner)
    {
        return view('admin.credit.invoice-application', ['partner' => $partner]);
    }

    public function invoiceClaim($partner)
    {
        return view('admin.credit.invoice-claim', ['partner' => $partner]);
    }

    public function invoiceClaimToPdf($partner)
    {
        $invoices = DB::table('credit_application_invoices')
                        ->join('credit_applications','credit_applications.id','=','credit_application_invoices.credit_application_id')
                        ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                        ->where('credit_application_invoices.status','=','waiting')
                        ->select('credit_application_invoices.created_at','credit_application_invoices.id','credit_application_invoices.product_id','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_hp','credit_applications.merk')
                        ->get();  

        $lastInvoice = CreditPartnerInvoice::get()->last();

        $lastInvoice = $lastInvoice ? $lastInvoice->nomor + 1 : 1;

        // create new invoice 
        $creditPartnerInvoice = CreditPartnerInvoice::create([
            'nomor' => $lastInvoice,
            'credit_partner_id' => $partner,
            'status' => 'waiting'
        ]);

        // create detail invoice 
        foreach ($invoices as $invoice) {
            $creditInvoiceClaimDetails = CreditInvoiceClaimDetail::create([
                "credit_partner_invoice_id"  => $creditPartnerInvoice->id,
                "credit_app_inv_id" => $invoice->id
            ]);
        }

        // change status colomn in credit application invoices to paid
        foreach ($invoices as $invoice) {
            $updateCreditApplicationInvoiceStatus = CreditApplicationInvoice::find($invoice->id)->update([
                'status' => 'claiming'
            ]);
        }

        $pdf = PDF::loadview('admin.credit.invoice-claim-pdf',['invoices' => $invoices]);
	    return $pdf->download('invoice-claim');
    }

    public function history($partner)
    {
        return view('admin.credit.history', ['partner' => $partner]);
    }

    public function creditPayment($partner)
    {
        return view('admin.credit.credit-payment', ['partner' => $partner]);
    }
}
