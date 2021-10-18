<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemEntry extends Model
{
    protected $guarded = [];

    public function stock()
    {
        return $this->hasOne('App\Model\Stock');
    }
}
