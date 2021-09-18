<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CreditApplicationInvoiceHistory extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $partnerId;

    public $searchName;

    public $showDetail;

    protected $listeners = [
        'showHistories' => 'showHistories'
    ];

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->showDetail = false;
    }

    public function render()
    {
        $histories = DB::table('credit_applications')
                        ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                        ->join('outlets','outlets.id','=','credit_applications.outlet_id')
                        ->where('credit_applications.credit_partner_id', $this->partnerId)
                        ->where(function($query) {
                            $query->where('credit_customers.nama', 'like', '%' . $this->searchName . '%')
                                ->orWhere('credit_customers.no_ktp', 'like', '%' . $this->searchName . '%');
                        })
                        ->select('credit_applications.id','credit_applications.status','credit_applications.merk','credit_applications.tenor','credit_applications.angsuran','credit_applications.dp','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_ktp','credit_customers.no_hp','outlets.nama as nama_outlet','credit_applications.sales_name','credit_applications.lunas')
                        ->orderBy('credit_applications.id','desc')
                        ->paginate(10);

        return view('livewire.credit-application-invoice-history', [
            'histories' => $histories
        ]);
    }

    public function showDetailButton($id)
    {
        $this->showDetail = true;
        $this->emit('showDetail', $id);
    }

    public function showHistories()
    {
        $this->showDetail = false;
    }
}
