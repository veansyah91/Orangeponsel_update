<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $fillable = ['invoice_id', 'bayar'];

    public function invoice()
    {
        return $this->belongsTo('App\Model\PaymentStatus');
    }
}
