<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SalesReturnDetail extends Model
{
    protected $fillable = ['sales_return_id', 'product_id', 'product_name', 'value', 'qty'];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
