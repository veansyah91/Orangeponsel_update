<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\TopUpBalance;
use App\Model\Account;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountPayable;
use App\Model\AccountPayableDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TopUpBalanceController extends Controller
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

        $servers = Account::where('outlet_id', $outletUser->outlet_id)
                            ->where('code', 'like', '112' . '%')
                            ->where('is_active', true)
                            ->get();

        return view('admin.top-balance.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,
            'servers' => $servers,
        ]);
    }

    public function getData(){
        $topUpBalance = TopUpBalance::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))
                                        ->orderBy('id', 'desc')
                                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $topUpBalance
        ]);
    }

    public function getSingleData($id)
    {
        $topUpBalance = TopUpBalance::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $topUpBalance
        ]);
    }

    public function renewInvoice($outlet, $params)
    {
        $outlet = Outlet::find($outlet);

        $outletSplit = explode(' ', $outlet['nama']);
        $first_characters_outlet_name = '';

        foreach ($outletSplit as $key => $os) {
            $splitOs = str_split($os);
            $first_characters_outlet_name .= $splitOs[0];
        }

        $date = date('Ymd');

        $newTopUpBalance = 'TUB/' . $first_characters_outlet_name . '/' . $date . '001';

        $endTopUpBalance = '';

        if ($params) {
            $endTopUpBalance = TopUpBalance::where('invoice_number', $params)->first();
        } else {
            $endTopUpBalance = TopUpBalance::where('outlet_id', request('outlet_id'))
                                ->where('invoice_number', 'like', 'TUB/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();            
        }
        if ($endTopUpBalance) {
            $split_end_invoice = explode('/', $endTopUpBalance['invoice_number']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newTopUpBalance = 'TUB/' . $first_characters_outlet_name . '/' . $newNumber;
        }
        
        return $newTopUpBalance;
    }

    public function newInvoice(){
        
        return response()->json([
            'status' => 'success',
            'data' => $this->renewInvoice(request('outlet_id'), false)
        ]);
    }

    public function createOtherTable($request)
    {
        $account = Account::find($request->cashierId);
        //cek apakah lunas atau hutang
        if (!$request->cashierId) {
            $account = Account::where('outlet_id', $request->outletId)->where('code', '2100000')->first();

            //cek dahulu apakah pernah berhutang sebelumnya
            $accountPayable = AccountPayable::where('outlet_id', $request->outletId)->where('supplier_id', $request->supplierId)->first();

            if ($accountPayable) {
                $updateBalance = $accountPayable['balance'] + $request->value;

                $accountPayable->update([
                    'balance' => $updateBalance
                ]);
            } else {
                
                $accountPayable = AccountPayable::create([
                    'outlet_id' => $request->outletId,
                    'supplier_id' => $request->supplierId,
                    'supplier_name' => $request->supplierName,
                    'balance' => $request->value,
                ]);
                
            }

            $accountPayableDetail = AccountPayableDetail::create([
                'account_payable_id' => $accountPayable['id'],
                'debit' => $request->value,
                'credit' => 0,
                'ref' => $request->invoiceNumber,
                'date' => date('Y-m-d'),
            ]);
        } 

        $ledger = Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $request->invoiceNumber,
            'debit' => 0,
            'credit' => $request->value,
            'date' => date('Y-m-d'),
            'description' => 'Purchase Top Up Balance'
        ]);

        //akun saldo
        $account = Account::find($request->serverId);

        $ledger = Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $request->invoiceNumber,
            'debit' => $request->value,
            'credit' => 0,
            'date' => date('Y-m-d'),
            'description' => 'Purchase Top Up Balance'
        ]);

    }

    public function deleteOtherTable($topUpBalance)
    {
        //hapus pada tabel buku besar
        $ledgers = Ledger::where('outlet_id', $topUpBalance['outlet_id'])
                            ->where('no_ref', $topUpBalance['invoice_number'])
                            ->get();

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        //cek pada utang
        //cek detail utang dahulu
        if (!$topUpBalance['cashier_id']) {
            $accountPayable = AccountPayable::where('outlet_id', $topUpBalance['outlet_id'])
                                                ->where('supplier_id', $topUpBalance['supplier_id'])
                                                ->first();

            if ($accountPayable) {
                $updateBalance = $accountPayable['balance'] - $topUpBalance['value'];
                $accountPayable->update([
                    'value' => $updateBalance
                ]);

                // hapus data pada table rincian hutang
                $accountPayableDetails = AccountPayableDetail::where('account_payable_id', $accountPayable['id'])->where('ref', $topUpBalance['invoice_number'])->first();

                if ($accountPayableDetails) {
                    $accountPayableDetails->delete();
                }
            }
        }
    }

    public function postData(Request $request)
    {
        $newInvoiceNumber = $this->renewInvoice($request->outletId, $request->invoiceNumber);

        $cashier = Account::find($request->cashierId);
        $server = Account::find($request->serverId);

        $topUpBalance = TopUpBalance::create([
            'outlet_id' => $request->outletId,
            'supplier_id' => $request->supplierId,
            'server_id' => $request->serverId,
            'cashier_id' => $cashier ? $request->cashierId : null,
            'supplier_name' => $request->supplierName,
            'server_name' => $server['name'],
            'cashier_name' => $cashier ? $cashier['name'] : '',
            'invoice_number' => $request->invoiceNumber,
            'value' => $request->value,
            'date' => date('Y-m-d'),
        ]);

        $this->createOtherTable($request);

        return response()->json([
            'status' => 'success',
            'data' => $newInvoiceNumber
        ]);
    }

    public function updateData(Request $request, $id)
    {
        $topUpBalance = TopUpBalance::find($id);

        $cashier = Account::find($request->cashierId);
        $server = Account::find($request->serverId);

        //hapus table lain
        $this->deleteOtherTable($topUpBalance);

        $topUpBalance->update([
            'supplier_id' => $request->supplierId,
            'server_id' => $request->serverId,
            'cashier_id' => $cashier ? $request->cashierId : null,
            'supplier_name' => $request->supplierName,
            'server_name' => $server['name'],
            'cashier_name' => $cashier ? $cashier['name'] : '',
            'invoice_number' => $request->invoiceNumber,
            'value' => $request->value,
        ]);

        //tambah table lain
        $this->createOtherTable($request);

        return response()->json([
            'status' => 'success',
            'data' => $topUpBalance
        ]);
    }

    public function deleteData($id)
    {
        $topUpBalance = TopUpBalance::find($id);

        //hapus table lain
        $this->deleteOtherTable($topUpBalance);

        $topUpBalance->delete();

        return response()->json([
            'status' => 'success',
            'data' => $topUpBalance
        ]);
    }
}
