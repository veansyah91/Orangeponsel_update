<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TopupInvoice extends Model
{
    protected $fillable = ['outlet_id', 'customer_id','account_id', 'product', 'address_no', 'selling_price', 'unit_cost', 'server', 'invoice_number', 'cashier_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    
}
