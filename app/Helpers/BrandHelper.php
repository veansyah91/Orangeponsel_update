<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Model\Brand;

class BrandHelper {
    public static function getName($id) {
        return $brand= Brand::where('id', $id)->first();
    }
}