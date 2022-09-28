<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountReceivable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\AccountReceivableDetail;
use App\Model\AccountReceivablePayment;

class AccountReceivablePaymentController extends Controller
{
    public function index(){
        $outletUser = OutletUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::find($outletUser->outlet_id);
        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%');
                                })
                                ->where('is_active', true)
                                ->get();
                                
        return view('admin.account-receivable-payment.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'outlet' => $outlet,
            'cashAccounts' => $cashAccounts,
        ]);
    }

    public function getData()
    {
        $accountReceivablePayment = AccountReceivablePayment::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' =>$accountReceivablePayment
        ]);
    }

    public function newInvoiceNumber()
    {
        $outlet = Outlet::find(request('outlet_id'));
        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $endAccountReceivablePayment = AccountReceivablePayment::where('outlet_id', request('outlet_id'))
                                ->where('invoice_number', 'like', 'ARP/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newAccountReceivablePayment = 'ARP/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endAccountReceivablePayment) {
            $split_end_invoice = explode('/', $endAccountReceivablePayment['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newAccountReceivablePayment = 'ARP/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        return response()->json([
            'status' => 'success',
            'data' => $newAccountReceivablePayment
        ]);
    }

    public function postData()
    {
        //buat AccountReceveivablePayment
        $createInvoice = AccountReceivablePayment::create([
            'outlet_id' => request('outletId'),
            'customer_id' => request('customerId'),
            'customer_name' => request('customerName'),
            'invoice_number' => request('invoiceNumber'),
            'value' => request('value'),
            'date' => date('Y-m-d')
        ]);

        // isi dahulu account receivable detail pada posisi credit utk pembayaran, jika selisih credit dan debit = 0 maka is_paid =  true 

        $accountReceivableDetails = AccountReceivableDetail::where('account_receivable_id', request('accountReceivableId'))->where('is_paid', false)->get();

        $total = request('value');
        $total_paid = 0;

        foreach ($accountReceivableDetails as $accountReceivableDetail) {
            if ($total > 0) {
                $balance = $accountReceivableDetail->debit - $accountReceivableDetail->credit;
                $payable = $total < $balance ? $total : $balance;
                $total_paid += $payable;

                $accountReceivableDetail->update([
                    'credit' => $payable,
                    'is_paid' => $payable < $balance ? false : true
                ]);

                $total = $total - $payable;
            }
        }

        $accountReceivable = AccountReceivable::find(request('accountReceivableId'));
        $oldBalance = $accountReceivable['balance'];

        $accountReceivable->update([
            'balance' => $oldBalance - request('value')
        ]);

        //masukkan ke database buku besar berdasarkan akun kas dan piutang
        //akun kas
        $account = Account::find(request('cashierId'));
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => $total_paid,
            'credit' => 0,
            'date' => date('Y-m-d'),
            'description' => 'Account Receivable Payment'
        ]);

        //account piutang
        $account = Account::where('outlet_id', request('outletId'))
                                ->where('code', '1200000')
                                ->first();
        Ledger::create([
            'outlet_id' => request('outletId'),
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => request('invoiceNumber'),
            'debit' => 0,
            'credit' => $total_paid,
            'date' => date('Y-m-d'),
            'description' => 'Account Receivable Payment'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $createInvoice
        ]);
    }
}
