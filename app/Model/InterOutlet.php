<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InterOutlet extends Model
{
    protected $fillable = ['pihak_1','pihak_2','invoice_id','jumlah','keterangan','konfirmasi'];

    public function invoice(){
        return $this->belongsTo('App\Model\Invoice'); 
    }

    public function pihak1(){
        return $this->belongsTo('App\Model\Outlet'); 
    }

    public function pihak2(){
        return $this->belongsTo('App\Model\Outlet'); 
    }

    
}
