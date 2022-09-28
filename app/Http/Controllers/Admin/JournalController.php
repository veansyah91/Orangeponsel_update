<?php

namespace App\Http\Controllers\Admin;

use App\Model\Ledger;
use App\Model\Journal;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        return view('admin.journal.index', 
            [
                'user' => Auth::user(),
                'outletUser' => OutletUser::where('user_id', Auth::user()->id)
                ->first()
            ]);
    }

    public function checkDeskription()
    {
        $ref_no = explode("-", request('ref_no'));

        $journal = Journal::where('reference_no', 'like',$ref_no[0] . '%')
            ->get()->last();

        if($journal){
            $split_journal_ref_no = explode("-", $journal->reference_no);
            $old_ref_no = (int)$split_journal_ref_no[1];
            $new_ref_no = 1000000 + $old_ref_no + 1;
            $new_ref_no_string = strval($new_ref_no);
            $new_ref_no_string_without_first_digit = substr($new_ref_no_string, 1);
            $fix_ref_no = $ref_no[0] . '-' . $new_ref_no_string_without_first_digit;
        }else{
            $fix_ref_no = $ref_no[0] . '-000001';
        }

        // return json
        return response()->json([
            'status' => 'success',
            'data' => $fix_ref_no
        ]);
    }

    public function create(Request $request)
    {

        $value = 0;
        foreach ($request->accountList as $accountList) {
            $value += $accountList['debit'];
        }

        $journal = Journal::create([
            'date' => $request->dateInput,
            'reference_no' => $request->reference_no,
            'description' => $request->descriptionInput,
            'outlet_id' => $request->outletId,
            'value' => $value,
            'detail' => $request->detail
        ]);

        foreach ($request->accountList as $accountList) {
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $accountList['account_id'],
                'account' => $accountList['account'],
                'no_ref' => $request->reference_no,
                'debit' => $accountList['debit'],
                'credit' => $accountList['credit'],
                'date' => $request->dateInput,
                'description' => $request->descriptionInput,
            ]);
        }

         // return json
         return response()->json([
            'status' => 'success',
            'data' => $journal
        ]);
    }

    public function countJournal()
    {
        $count_journal = Journal::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))
                                ->get()->count();

        return response()->json([
            'status' => 'success',
            'data' => $count_journal
        ]);
    }

    public function getJournals()
    {
        $journal = Journal::filter(request(['outlet_id','search','date_from','date_to','this_week','this_month','this_year']))
                            ->orderBy('date', 'desc')
                            ->orderBy('id', 'desc')
                            ->skip(request('page')*15)
                            ->take(15)
                            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $journal,
        ]);
    }

    public function getJournal(Journal $journal)
    {
        $ledger = Ledger::where('no_ref', $journal->reference_no)
                            ->with('account')
                            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                        'journal' => $journal,
                        'ledgers' => $ledger
                    ]
        ]);
    }
    
    public function delete(Journal $journal){

        // hapus dahulu ledger
        $ledgers = Ledger::where('no_ref', $journal->reference_no)->get();

        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        // hapus journal
        $journal->delete();
        
        return response()->json([
            'status' => 'success',
            'data' => $ledgers
        ]);
    }

    public function edit(Journal $journal){
        $ledgers = Ledger::where('no_ref', $journal->reference_no)->get();

        $result = [
            'journal' => $journal,
            'ledgers' => $ledgers
        ];
        

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function update(Request $request, Journal $journal)
    {
        $value = 0;
        foreach ($request->accountList as $accountList) {
            $value += $accountList['debit'];
        }

        $journal->update([
            'date' => $request->dateInput,
            'reference_no' => $request->reference_no,
            'description' => $request->descriptionInput,
            'detail' => $request->detail,
            'outlet_id' => $request->outletId,
            'value' => $value
        ]);

        // hapus dahulu ledger
        $ledgers = Ledger::where('no_ref', $journal->reference_no)->get();
        foreach ($ledgers as $ledger) {
            $ledger->delete();
        }

        //buat ulang ledger
        foreach ($request->accountList as $accountList) {
            Ledger::create([
                'outlet_id' => $request->outletId,
                'account_id' => $accountList['account_id'],
                'account' => $accountList['account'],
                'no_ref' => $request->reference_no,
                'debit' => $accountList['debit'],
                'credit' => $accountList['credit'],
                'date' => $request->dateInput,
                'description' => $request->descriptionInput,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $journal
        ]);
    }
}
