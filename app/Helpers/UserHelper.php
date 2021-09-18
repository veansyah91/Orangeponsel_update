<?php

namespace App\Helpers;

use App\User;
use App\Model\Outlet;
use App\Model\OutletUser;
use App\Model\CreditSales;
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

    public static function getOutletUserByOutletName($userId)
    {
        $outlet_user = OutletUser::where('user_id', $userId)->first();

        return $outlet = Outlet::find($outlet_user['outlet_id']);
    }

    public static function getUser($userId)
    {
        return $user = User::find($userId);
    }

    public static function getSalesDetail($userId)
    {
        return $user = CreditSales::where('user_id', $userId)->first();
    }
}