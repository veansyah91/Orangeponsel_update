<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Outlet;
use App\Model\Account;
use App\Model\Expense;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountPayable;
use App\Model\AccountPayableDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(){
        $outletUser = OutletUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::find($outletUser->outlet_id);

        $cashAccounts = Account::where('outlet_id', $outletUser->outlet_id)
                                ->where(function($query) {
                                    $query->where('code', 'like', '110' . '%')
                                        ->orWhere('code','like', '111' . '%')
                                        ->orWhere('code','like', '112' . '%')
                                        ->orWhere('code','like', '113' . '%');
                                })
                                ->where('is_active', true)
                                ->get();

        $items = Account::where('outlet_id', $outletUser->outlet_id)
                            ->where('code', 'like', '510' . '%')
                            ->where('is_active', true)
                            ->get();

        return view('admin.expense.index', [
            'user' => Auth::user(),
            'outletUser' => $outletUser,
            'cashAccounts' => $cashAccounts,
            'items' => $items,
        ]);
    }

    public function getData()
    {
        $expense = Expense::filter(request(['outlet_id','search', 'date_from', 'date_to', 'this_week', 'end_week', 'this_month', 'end_month', 'this_year', 'end_year']))
                            ->orderBy('date', 'desc')
                            ->orderBy('id', 'desc')
                            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $expense
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

        $endExpense = Expense::where('outlet_id', request('outlet_id'))
                                ->where('no_ref', 'like', 'Ex/' . $first_characters_outlet_name . '/' . $date . '%')
                                ->get()->last();

        $newExpense = 'Ex/' . $first_characters_outlet_name . '/' . $date . '001';

        if ($endExpense) {
            $split_end_invoice = explode('/', $endExpense['no_ref']);

            $newNumber = (int)$split_end_invoice[2] + 1;

            $newExpense = 'Ex/' . $first_characters_outlet_name . '/' . $newNumber;
        }

        return response()->json([
            'status' => 'success',
            'data' => $newExpense
        ]);
    }

    protected function createOtherData($request)
    {
        //akun kas
        $account = Account::find($request->cashId);

        if ($account) {
            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => 0,
                'credit' => $request->value,
                'date' => date('Y-m-d'),
                'description' => 'Expense'
            ]);
        }
        else {
            $account = Account::where('outlet_id', $request->outletId)
                                ->where('code', '2100000')
                                ->where('is_active', true)
                                ->first();

            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => 0,
                'credit' => $request->value,
                'date' => date('Y-m-d'),
                'description' => 'Expense'
            ]);

            $accountPayable = AccountPayable::where('outlet_id', $request->outletId)
                                            ->where('supplier_id', $request->supplierId)
                                            ->first();

            if ($accountPayable) {
                $updateAccountPayableBalance = $accountPayable['balance'] + $request->value;

                $accountPayable->update([
                    'balance' => $updateAccountPayableBalance
                ]);
            }

            else {
                $accountPayable = AccountPayable::create([
                    'outlet_id' => $request->outletId,
                    'supplier_id' => $request->supplierId,
                    'supplier_name' => $request->supplierName,
                    'balance' => $request->value,
                ]);
            }

            AccountPayableDetail::create([
                'account_payable_id' => $accountPayable['id'],
                'debit' => $request->value,
                'ref' => $request->noRef,
                'date' => $request->date,
                'description' => $request->description,
            ]);
        }

        //akun beban
        $account = Account::find($request->itemId);

        if ($account) {
            $ledger = Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $account['id'],
                'account' => $account['name'],
                'no_ref' => $request->noRef,
                'debit' => $request->value,
                'credit' => 0,
                'date' => $request->date,
                'description' => 'Expense'
            ]);
        }

        //akun laba rugi
        $account = Account::where('outlet_id', request('outletId'))->where('code','3200001')->first();
        $ledger = Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $request->noRef,
            'debit' => $request->value,
            'credit' => 0,
            'date' => $request->date,
            'description' => 'Expense'
        ]);

        //laba ditahan
        $account = Account::where('outlet_id', request('outletId'))->where('code','6990000')->first();
        Ledger::create([
            'outlet_id' => $request->outletId,
            'account_id' => $account['id'],
            'account' => $account['name'],
            'no_ref' => $request->noRef,
            'debit' => 0,
            'credit' => $request->value,
            'date' => $request->date,
            'description' => 'Expense'
        ]);

    }

    protected function deleteOtherTable($expense)
    {
        //delete buku besar
        $ledgers = Ledger::where('outlet_id', $expense['outlet_id'])
                        ->where('no_ref', $expense['no_ref'])
                        ->get();

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        //delete di buku hutang jika ada
        $accountPayableDetail = AccountPayableDetail::where('ref', $expense['no_ref'])->first();

        if ($accountPayableDetail) {
            $accountPayable = AccountPayable::find($accountPayableDetail['account_payable_id']);

            $updateBalance = $accountPayable['balance'] - $expense['value'];

            $accountPayable->update([
                'balance' => $updateBalance
            ]);

            $accountPayableDetail->delete();
        }
    }

    public function postData(Request $request)
    {   
        //akun kas
        $account = Account::find($request->cashId);
        
        $expense = Expense::create([
            'outlet_id' => $request->outletId,
            'cash_id' => $account ? $request->cashId : null,
            'cash_name' => $account ? $account['name'] : '',
            'item_id' => $request->itemId,
            'item_name' => $request->itemName,
            'value' => $request->value,
            'date' => $request->date,
            'description' => $request->description,
            'no_ref' => $request->noRef,
            'supplier_id' => $request->supplierId > 0 ? $request->supplierId : null,
            'supplier_name' => $request->supplierName,
        ]);

        //buat di table buku besar (ledger)
        $this->createOtherData($request);

        return response()->json([
            'status' => 'success',
            'data' => $expense
        ]);
    }

    public function getSingleData($id)
    {   
        $expense = Expense::find($id);

        return response()->json([
            'status' => 'success',
            'data' => $expense 
        ]);
    }

    public function updateData(Request $request, $id)
    {
        //akun kas
        $account = Account::find($request->cashId);
        $expense = Expense::find($id);
        $expense->update([
            'cash_id' => $account ? $request->cashId : null,
            'cash_name' => $account ? $account['name'] : '',
            'item_id' => $request->itemId,
            'item_name' => $request->itemName,
            'value' => $request->value,
            'date' => $request->date,
            'description' => $request->description,
            'no_ref' => $request->noRef,
            'supplier_id' => $request->supplierId > 0 ? $request->supplierId : null,
            'supplier_name' => $request->supplierName,
        ]);

        $this->deleteOtherTable($expense);

        //buat di table buku besar (ledger)
        $this->createOtherData($request);

    }

    public function deleteData($id)
    {
        $expense = Expense::find($id);

        $this->deleteOtherTable($expense);

        $expense->delete();

        return response()->json([
            'status' => 'success',
            'data' => $expense 
        ]);
    }
}
