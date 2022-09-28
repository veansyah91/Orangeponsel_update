<?php

namespace App\Http\Controllers\Admin;

use App\Model\SalesReturn;
use Illuminate\Http\Request;
use App\Model\SalesReturnDetail;
use App\Http\Controllers\Controller;

class SalesReturnDetailController extends Controller
{
    public function getData()
    {
        $salesReturn = SalesReturn::find(request('sales_return_id'));

        $salesReturnDetail = SalesReturnDetail::where('sales_return_id', request('sales_return_id'))
                                                ->get();
                                                
        // return json
        return response()->json([
            'status' => 'success',
            'data' =>   [
                            'salesReturn' => $salesReturn,
                            'salesReturnDetail' => $salesReturnDetail,
                        ]   
        ]);
    }
}
