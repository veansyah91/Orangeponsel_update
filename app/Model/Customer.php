<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['nama','alamat','hp'];

    public function invoice()
    {
        return $this->hasMany('App\Model\Invoice');
    }
}
