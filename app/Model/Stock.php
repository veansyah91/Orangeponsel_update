<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['jumlah','outlet_id','product_id','created_at','updated_at', 'item_entry_id'];

    public function outlet(){
        return $this->belongsTo('App\Model\Outlet'); 
    }

    public function product(){
        return $this->belongsTo('App\Model\Product'); 
    }

    public function ItemEntry(){
        return $this->belongsTo('App\Model\ItemEntry'); 
    }

    static function checkStock($product, $outlet)
    {
        return self::where('product_id', $product)->where('outlet_id', $outlet)->first();
    }

    static function reduceStock($product, $outlet, $sisa)
    {
        return self::where('product_id', $product)
                    ->where('outlet_id', $outlet)
                    ->update(['jumlah' => $sisa]);
    }
}
