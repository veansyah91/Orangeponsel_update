<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CreditApplicationInvoice extends Model
{
    protected $fillable = ['credit_application_id','harga','status','product_id','nama_produk','kode', 'user_name'];

    public function creditApplication()
    {
        return $this->hasOne('App\Model\CreditApplication');
    }
}
