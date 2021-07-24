<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['nama','alamat','hp'];

    public function stock()
    {
        return $this->hasMany('App\Model\Stock');
    }//

    public function invoice()
    {
        return $this->hasMany('App\Model\Invoice');
    }

    public function interOutlet()
    {
        return $this->hasMany('App\Model\InterOutlet');
    }

    public function user()
    {
        return $this->belongsToMany('App\Model\User');
    }

    public function balance()
    {
        return $this->hasMany('App\Model\Balance');
    }

    public function balanceTransaction()
    {
        return $this->hasMany('App\Model\BalanceTransaction');
    }

    public function creditCustomer()
    {
        return $this->hasMany('App\Model\CreditCustomer');
    }
}
