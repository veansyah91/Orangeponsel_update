<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\AccountPayableDetail;
use App\Http\Controllers\Controller;

class AccountPayableDetailController extends Controller
{
    public function detail($detail)
    {
        $accountPayableDetail = AccountPayableDetail::filter(request(['is_paid']))
                                                            ->where('account_payable_id', $detail)
                                                            ->orderBy('date', 'desc')
                                                            ->get();                    
        return response()->json([
            'status' => 'success',
            'data' =>$accountPayableDetail
        ]);
    }
}
