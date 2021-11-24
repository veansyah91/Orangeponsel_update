<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Helpers\UserHelper;
use App\Model\CreditPayment;
use Livewire\WithPagination;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Model\CreditApplicationInvoice;

class CreditPaymentIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $create_payment;
    public $detail_payment;
    public $store_payment;
    public $partner_id;

    protected $listeners = [
        'showIndex' => 'handleShowIndex'
    ];

    public function mount($partnerId)
    {
        $this->partner_id = $partnerId;
        $this->resetValue();
    }

    public function resetValue()
    {
        $this->create_payment = false;
        $this->detail_payment = false;
        $this->store_payment = false;
    }

    public function render()
    {
        $outlet = UserHelper::getOutletUserByOutletName(Auth::user()['id'])['nama'];
        
        $creditPayments = DB::table('credit_payments')
                            ->join('credit_applications', 'credit_applications.id','=','credit_payments.credit_application_id')
                            ->where('credit_applications.credit_partner_id', $this->partner_id)
                            ->select('credit_payments.*')
                            ->orderBy('credit_payments.id','desc')                                        
                            ->paginate(10);

        $status = CreditPayment::where('status', '0')->where('outlet', $outlet)->get()->count();

        $lastInvoice = DB::table('credit_application_invoices')
                            ->join('credit_applications', 'credit_applications.id', '=', 'credit_application_invoices.credit_application_id')
                            ->where('credit_applications.credit_partner_id', $this->partner_id)->select('credit_application_invoices.created_at')->first();
                            // dd($lastInvoice->created_at);

        return view('livewire.credit-payment-index', [
            'creditPayments' => $creditPayments,
            'status' => $status,
            'lastInvoice' => $lastInvoice,
        ]);
    }

    public function createPaymentButton()
    {
        $this->create_payment = true;
        $this->emit('getCreatePayment');
    }

    public function handleShowIndex()
    {
        $this->resetValue();
    }

    public function showDetail($id)
    {
        $this->detail_payment = true;
        $this->emit('showDetail', $id);
    }

    public function delete($id)
    {
        $creditPartner = CreditPayment::find($id);
        
        $creditApp = CreditApplication::find($creditPartner['credit_application_id'])->update([
            'lunas' => '0'
        ]);

        $creditPartner->delete();
    }

    public function storePaymentButton()
    {
        $this->store_payment = true;
    }
}
