<?php
namespace App\Helpers;

use App\Model\CreditPartner;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use App\Model\CreditApplicationOld;
use App\Model\CreditApplicationInvoice;

class CreditPartnerHelper {
    public static function getPartner() {
      return $category= CreditPartner::all();
    }

    public static function getTotalInvoice($id) {
			return $total = DB::table('credit_invoice_claim_details')
									->join('credit_application_invoices','credit_application_invoices.id','=','credit_app_inv_id')
									->where('credit_invoice_claim_details.credit_partner_invoice_id', $id)
									->get()
									->sum('harga');
    }

    public static function getPrice($id)
    {
      return $result = CreditApplicationInvoice::where('credit_application_id',$id)->select('harga','created_at')->first();
    }

    public static function getBiodata($credit_app_id)
    {
      $detail = CreditApplication::find($credit_app_id);

      return $customer = CreditCustomer::find($detail['credit_customer_id']);

    }

    public static function getBiodataOld($credit_app_old_id)
    {
      return $detail = CreditApplicationOld::find($credit_app_old_id);
    }
}