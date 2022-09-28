<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AccountReceivableDetail;

class AccountReceivableDetailController extends Controller
{
    public function detail($detail)
    {
        $accountReceivableDetail = AccountReceivableDetail::filter(request(['is_paid']))
                                                            ->where('account_receivable_id', $detail)
                                                            ->orderBy('date', 'desc')
                                                            ->get();                    
        return response()->json([
            'status' => 'success',
            'data' =>$accountReceivableDetail
        ]);
    }
}
