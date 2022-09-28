<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseGoodsDetail extends Model
{
    protected $fillable = ['purchase_goods_id', 'purchase_goods_id', 'product_name', 'value', 'qty', 'product_id'];

    public function purchaseGoods()
    {
        return $this->belongsTo(PurchaseGoods::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
