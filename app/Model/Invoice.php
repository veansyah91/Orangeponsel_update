<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['no_nota','jumlah','jual','outlet_id','product_id','customer_id'];

    public function outlet(){
        return $this->belongsTo('App\Model\Outlet'); 
    }

    public function customer(){
        return $this->belongsTo('App\Model\Customer'); 
    }

    public function detail()
    {
        return $this->hasMany('App\Model\InvoiceDetail');
    }

    public function paymentStatus()
    {
        return $this->hasOne('App\Model\PaymentStatus');
    }

    public function income()
    {
        return $this->hasMany('App\Model\Income');
    }
    
    public function deptPayment()
    {
        return $this->hasMany('App\Model\DeptPayment');
    }

    public function interOutlet()
    {
        return $this->hasMany('App\Model\InterOutlet');
    }
}
