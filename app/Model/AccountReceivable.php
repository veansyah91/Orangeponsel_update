<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
}
