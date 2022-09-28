<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    protected $fillable = ['product_id', 'product_name', 'qty', 'value', 'value_approvement', 'purchase_return_id'];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }
}
