<?php
namespace App\Helpers;

use App\Model\Stock;
use App\Model\ItemEntry;
use Illuminate\Support\Facades\DB;

class ItemEntryHelper {
    public static function show($id) {
        return $itemEntry= ItemEntry::where('id', $id)->first();
    }

    public static function countData($id)
    {
        return $countStock = Stock::where('item_entry_id', $id)->get()->count();
    }
}