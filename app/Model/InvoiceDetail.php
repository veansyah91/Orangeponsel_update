<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = ['jumlah','jual','invoice_id','product_id'];

    public function product(){
        return $this->belongsTo('App\Model\Product'); 
    }

    public function invoice(){
        return $this->belongsTo('App\Model\Invoice'); 
    }
}
