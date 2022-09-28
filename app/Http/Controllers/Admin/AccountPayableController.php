<?php

namespace App\Http\Controllers\Admin;

use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountPayable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountPayableController extends Controller
{
    public function index(){
        return view('admin.account-payable.index', [
            'user' => Auth::user(),
            'outletUser' => OutletUser::where('user_id', Auth::user()->id)->first()
        ]);
    }

    public function getData(){
        $accountPayable = AccountPayable::filter(request(['outlet_id','search','is_paid']))  
                                                ->orderBy('balance', 'desc')   
                                                ->get();

        return response()->json([
            'status' => 'success',
            'data' =>$accountPayable
        ]);
    }
}
