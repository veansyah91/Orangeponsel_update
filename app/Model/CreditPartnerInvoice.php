<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CreditPartnerInvoice extends Model
{
    protected $fillable = ['nomor','credit_partner_id','status'];
}
