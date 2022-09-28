<?php

namespace App\Http\Controllers\Admin;

use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountReceivable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountReceivableController extends Controller
{
    public function index(){
        return view('admin.account-receivable.index', [
            'user' => Auth::user(),
            'outletUser' => OutletUser::where('user_id', Auth::user()->id)->first()
        ]);
    }

    public function getData()
    {
        $accountReceivable = AccountReceivable::filter(request(['outlet_id','search','is_paid']))  
                                                ->orderBy('balance', 'desc')   
                                                ->get();

        return response()->json([
            'status' => 'success',
            'data' =>$accountReceivable
        ]);
    }
}
