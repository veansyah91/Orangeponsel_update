<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountPayable extends Model
{
    protected $fillable = ['outlet_id', 'supplier_id', 'supplier_name', 'balance'];

    public function outlet(){
        return $this->belongsTo(Outlet::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['outlet_id'], function ($query, $outlet_id) {
            return $query->where('outlet_id', $outlet_id);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('supplier_name', 'like', '%' . $search . '%')
                    ->orWhere('balance', 'like', '%' . $search . '%');
        });

        $query->when($filters['is_paid'] ?? false, function ($query) {
            return $query->where('balance', '>', 0);
        });
    }
}
