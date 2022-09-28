<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountReceivableDetail extends Model
{
    protected $guarded = [];

    protected $fillable = ['date', 'account_receivable_id', 'debit', 'credit', 'ref', 'description','is_paid'];

    public function accountReceivable()
    {
        return $this->belongsTo(AccountReceivable::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['is_paid'] ?? false, function ($query, $is_paid) {
            return $query->where('is_paid', $is_paid);
        });
    }
}
