<?php

namespace App\Http\Controllers\Admin;

use App\Model\PurchaseGoods;
use Illuminate\Http\Request;
use App\Model\PurchaseGoodsDetail;
use App\Http\Controllers\Controller;

class PurchaseGoodsDetailController extends Controller
{
    public function getData($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => 'data'
        ]);
        $purchaseGoods = PurchaseGoods::find($id);
        $purchaseGoodsDetail = PurchaseGoodsDetail::where('account_payable_id', $purchaseGoods)->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                        'purchaseGoods' => $purchaseGoods,
                        'purchaseGoodsDetail' => $purchaseGoodsDetail,

                      ]
        ]);
    }
}
