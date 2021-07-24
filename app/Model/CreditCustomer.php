<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CreditCustomer extends Model
{
    protected $fillable = ['nama','no_ktp','no_kk','jenis_kelamin','alamat','no_hp','outlet_id'];

    public function creditAplication()
    {
        return $this->hasMany('App\Model\CreditApplication');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Model\Outlet');
    }

}
