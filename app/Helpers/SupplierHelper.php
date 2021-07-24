<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Model\Supplier;

class SupplierHelper {
    public static function getName($id) {
        return $supplier= Supplier::where('id', $id)->first();
    }
}