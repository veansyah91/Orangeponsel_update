<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountPayableDetail extends Model
{
    protected $fillable = ['account_payable_id', 'debit', 'credit', 'ref', 'description', 'date', 'due_date', 'is_paid'];

    public function accountPayable(){
        return $this->belongsTo(AccountPayable::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['is_paid'] ?? false, function ($query, $is_paid) {
            return $query->where('is_paid', $is_paid);
        });
    }
}
