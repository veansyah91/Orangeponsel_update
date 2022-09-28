<?php

namespace App\Model;

use App\Model\Ledger;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = [];

    public function ledger()
    {
        return $this->hasMany(Ledger::class);
    }
}
