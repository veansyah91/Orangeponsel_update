<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    protected $fillable = ['outlet_id','supplier_id','modal','jual','keterangan','nomorId'];

    public function supplier()
    {
        return $this->belongsTo('App\Model\Supplier');
    }

    public function outlet(){
        return $this->belongsTo('App\Model\Outlet'); 
    }
}
