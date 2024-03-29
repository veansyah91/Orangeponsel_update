<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['tipe','kode','modal','jual','supplier_id','category_id','brand_id'];

    public function brand()
    {
        return $this->belongsTo('App\Modal\Brand');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo('App\Modal\Supplier');
    }

    public function stock()
    {
        return $this->hasOne('App\Model\Stock');
    }

    public function invoiceDetail()
    {
        return $this->hasOne('App\Model\Invoice');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('tipe', 'like', '%' . $search . '%')
                        ->orWhere('kode', 'like', '%' . $search . '%');
        });
    }
}
