<?php

namespace App\Helpers;

use App\Model\Outlet;
use Illuminate\Support\Facades\DB;

class OutletHelper {
    public static function getOutlet($id)
    {
        return $outlet = Outlet::find($id);
        
    }
}