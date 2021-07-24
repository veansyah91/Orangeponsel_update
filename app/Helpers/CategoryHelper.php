<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Model\Category;

class CategoryHelper {
    public static function getName($id) {
        return $category= Category::where('id', $id)->first();
    }
}