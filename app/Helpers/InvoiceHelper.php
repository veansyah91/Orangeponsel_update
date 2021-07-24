<?php
namespace App\Helpers;

use App\Model\Income;
use App\Model\Balance;
use App\Model\Invoice;
use App\Model\DebtPayment;
use App\Model\InvoiceDetail;
use App\Model\PaymentStatus;
use App\Model\BalanceTransaction;
use Illuminate\Support\Facades\DB;

class InvoiceHelper {
    public static function getTotal($invoiceId) {
        $total = Income::where('invoice_id', $invoiceId)->first();
        return $total ? $total->jumlah : 0;
    }

    public static function getDebtPaymentDetail($invoiceId) {
        return $payments = DebtPayment::where('invoice_id', $invoiceId)->get();
    }

    public static function getDebtPaymentTotal($invoiceId) {
        
        $payment = DebtPayment::where('invoice_id', $invoiceId)->get()->sum('bayar');
        // dd($payment);
        return $payment ? $payment : 0;

    }

    public static function getDetail($id){
        return $invoice = Invoice::find($id);
    }

    public static function getDetailInvoicePayment($id)
    {
        return $detail = PaymentStatus::where('invoice_id', $id)->first();
        
    }

    public static function getBalanceBalance($outletId, $supplierId)
    {
        $totalBalanceCredit = Balance::where('outlet_id', $outletId)->where('supplier_id', $supplierId)->get()->sum('jumlah');

        $totalBalanceDebit = BalanceTransaction::where('outlet_id', $outletId)->where('supplier_id', $supplierId)->get()->sum('modal');

        return $totalBalanceCredit - $totalBalanceDebit;
    }
}  