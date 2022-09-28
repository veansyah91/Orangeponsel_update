<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountReceivable extends Model
{
    protected $guarded = [];

    public function accountReceivableDetail()
    {
        return $this->hasMany(AccountReceivableDetail::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['outlet_id'], function ($query, $outlet_id) {
            return $query->where('outlet_id', $outlet_id);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('balance', 'like', '%' . $search . '%');
        });

        $query->when($filters['is_paid'] ?? false, function ($query) {
            return $query->where('balance', '>', 0);
        });
    }
}
