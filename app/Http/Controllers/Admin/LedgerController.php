<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Account;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LedgerController extends Controller
{
    public function index()
    {
        return view('admin.ledger.index', 
            [
                'user' => Auth::user(),
                'outletUser' => OutletUser::where('user_id', Auth::user()->id)
                ->first()
            ]);
    }

    public function getLedger()
    {
        $ledgers = Ledger::filter(request(['outlet_id','account_id','search','date_from','date_to','this_week','this_month','this_year']))
                            ->orderBy('date', 'desc')
                            ->orderBy('id', 'desc')
                            ->skip(request('page')*15)
                            ->take(15)
                            ->get();
                            
        return response()->json([
            'status' => 'success',
            'data' => $ledgers
        ]);
    }

    public function countLedger()
    {
        $countLedgers = Ledger::filter(request(['outlet_id','account_id','search','date_from','date_to','this_week','this_month','this_year']))
                            ->get()->count();
                            
        return response()->json([
            'status' => 'success',
            'data' => $countLedgers
        ]);
    }

    public function balance()
    {
        //get all
        $amountLedgerAll =Ledger::filter(request(['outlet_id','account_id','date_to', 'end_week', 'end_month', 'end_year']))
                                ->get();

        //get base on filtering by date or time
        $amountLedger =Ledger::filter(request(['outlet_id','account_id','search','date_from','date_to','this_week','this_month','this_year']))
                                ->where('account_id', request('account_id'))
                                ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                        'saldo_akhir' => $amountLedgerAll->sum('debit') - $amountLedgerAll->sum('credit'),
                        'total_credit' => $amountLedger->sum('credit'),
                        'total_debit' => $amountLedger->sum('debit'),
                    ]
        ]);
    }

    public function topUpBalance(){
        $accounts = Account::where('outlet_id', request('outlet_id'))
                            ->where('code', 'like', '112%')
                            ->get();

        $data = [];

        foreach ($accounts as $key => $account) {
            $ledger = Ledger::where('outlet_id', request('outlet_id'))
                            ->where('account_id', $account->id)
                            ->get();

           $data[$key] = [
                'accountId' => $account->id,
                'accountName' => $account->name,
                'balance' => $ledger->sum('debit') - $ledger->sum('credit')
           ];
        }
            
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);

    }
}
