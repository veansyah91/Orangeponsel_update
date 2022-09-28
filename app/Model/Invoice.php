<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['cashier_id','no_nota','jumlah','jual','outlet_id','product_id','customer_id','account_name'];

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

    public function accountReceivable()
    {
        return $this->hasOne('App\Model\AccountReceivable');
    }

    public function invoiceDetail()
    {
        return $this->hasMany('App\Model\InvoiceDetail');
    }
}
