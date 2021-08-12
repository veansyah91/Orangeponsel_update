<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CreditInvoiceClaimDetail extends Model
{
    protected $fillable = ['credit_app_inv_id', 'credit_partner_invoice_id'];
}