<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['nama'];     
    
    public function product()
    {
        return $this->hasMany('App\Model\Product');
    }//
}
