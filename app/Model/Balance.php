<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = ['outlet_id','supplier_id','jumlah','keterangan'];

    public function supplier()
    {
        return $this->belongsTo('App\Model\Supplier');
    }

    public function outlet(){
        return $this->belongsTo('App\Model\Outlet'); 
    }
}
