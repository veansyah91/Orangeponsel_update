<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['nama','alamat','hp'];

    public function product()
    {
        return $this->hasMany('App\Model\Product');
    }

    public function balance()
    {
        return $this->hasMany('App\Model\Balance');
    }

    public function balanceTransaction()
    {
        return $this->hasMany('App\Model\BalanceTransaction');
    }
}
