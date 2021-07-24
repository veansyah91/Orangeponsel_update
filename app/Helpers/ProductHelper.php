<?php
namespace App\Helpers;

use App\Model\Product;
use Illuminate\Support\Facades\DB;

class ProductHelper {
    public static function show($id) {
        return $product= Product::where('id', $id)->first();
    }
}