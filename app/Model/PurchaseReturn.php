<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'outlet_id', 'supplier_id', 'supplier_name', 'date_delivery', 'date_accepted_on_supplier', 'date_receipt', 'value', 'value_approvement', 'approvement', 'approvement_description', 'no_ref', 'account_id', 'account_name'
    ];

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }


    public function scopeFilter($query, array $filters)
    {
        //filter by outlet
        $query->when($filters['outlet_id']?? false, function ($query, $outlet_id) {
            return $query->where('outlet_id', $outlet_id);
        });

        //search
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('supplier_name', 'like', '%' . $search . '%')
                    ->orWhere('value_approvement', 'like', '%' . $search . '%')
                    ->orWhere('approvement_description', 'like', '%' . $search . '%')
                    ->orWhere('no_ref', 'like', '%' . $search . '%')
                    ->orWhere('value', 'like', '%' . $search . '%');
        });

        //filter by date between
        $query->when($filters['date_from'] ?? false, function ($query, $date_from) {
            return $query->where('date', '>=', $date_from);
        });

        $query->when($filters['date_to'] ?? false, function ($query, $date_to) {
            return $query->where('date', '<=', $date_to);
        });

        //filter by this week
        $query->when($filters['this_week'] ?? false, function ($query) {
            return $query->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        });

        //filter by end week
        $query->when($filters['end_week'] ?? false, function ($query) {
            return $query->whereBetween('date', '<=', now()->endOfWeek());
        });

        // filter by this month
        $query->when($filters['this_month'] ?? false, function ($query) {
            return $query->whereBetween('date', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ]);
        });

        // filter by end month
        $query->when($filters['end_month'] ?? false, function ($query) {
            return $query->where('date', '<=', now()->endOfMonth());
        });

        // filter by this year
        $query->when($filters['this_year'] ?? false, function ($query) {
            return $query->whereBetween('date', [
                now()->startOfYear(),
                now()->endOfYear()
            ]);
        });

        // filter by end year
        $query->when($filters['end_year'] ?? false, function ($query) {
            return $query->where('date','<=', now()->endOfYear());
        });
    }
}
