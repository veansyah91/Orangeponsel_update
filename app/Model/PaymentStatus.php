<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $fillable = ['outlet_id','invoice_id','sisa','total'];

    public function outlet(){
        return $this->belongsTo('App\Model\Outlet'); 
    }

    public function invoice(){
        return $this->belongsTo('App\Model\Invoice'); 
    }

    public function paymentStatus()
    {
        return $this->hasMany('App\Model\PaymentStatus');
    }
}
