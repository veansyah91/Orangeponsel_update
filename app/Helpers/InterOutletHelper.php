<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Model\InterOutlet;
use App\Model\DebtPayment;
use App\Model\Invoice;

class InterOutletHelper{

    public static function getDetail($pihak1, $pihak2)
    {
        return $data = InterOutlet::where('pihak_1', $pihak1)
                                    ->where('pihak_2', $pihak2)
                                    ->orderBy('updated_at', 'desc')
                                    ->take(20)
                                    ->get();
    }
}  