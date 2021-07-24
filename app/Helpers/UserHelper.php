<?php

namespace App\Helpers;

use App\Model\OutletUser;
use App\Model\CreditPartner;
use Illuminate\Support\Facades\DB;

class UserHelper {
    public static function getOutletUser($userId)
    {
        return $outlet = OutletUser::where('user_id', $userId)->first();
    }

    public static function getCreditSales($id)
    {
        return $sales = CreditPartner::find($id);
    }
}