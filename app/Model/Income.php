<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['invoice_id','dept_payment_id','jumlah'];

    public function invoice(){
        return $this->belongsTo('App\Model\Invoice'); 
    }

    public function deptPayment(){
        return $this->belongsTo('App\Model\DeptPayment'); 
    }
}
