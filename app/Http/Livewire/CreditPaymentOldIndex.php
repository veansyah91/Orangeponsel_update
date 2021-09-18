<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Helpers\UserHelper;
use Livewire\WithPagination;
use App\Model\CreditPaymentOld;
use Illuminate\Support\Facades\DB;
use App\Model\CreditApplicationOld;
use Illuminate\Support\Facades\Auth;

class CreditPaymentOldIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'showIndex' => 'handleShowIndex'
    ];

    public $create;
    public $detail;
    public $partner_id;
    public $store;

    public function mount($partnerId)
    {
        $this->partner_id = $partnerId;
        $this->resetValue();
    }

    public function resetValue()
    {
        $this->create = false;
        $this->detail = false;
        $this->store = false;
    }
    
    public function render()
    {
        $outlet = UserHelper::getOutletUserByOutletName(Auth::user()['id'])['nama'];

        $data = DB::table('credit_payment_olds')
                    ->join('credit_application_olds','credit_application_olds.id','=','credit_payment_olds.credit_app_old_id')
                    ->where('credit_application_olds.credit_partner_id', $this->partner_id)
                    ->select('credit_payment_olds.*')
                    ->orderBy('credit_payment_olds.created_at', 'desc')
                    ->paginate(10);

        $status = CreditPaymentOld::where('status', '0')->where('outlet', $outlet)->get()->count();

        return view('livewire.credit-payment-old-index', [
            'data' => $data,
            'status' => $status
        ]);
    }

    public function addButton()
    {
        $this->create = true;
    }

    public function handleShowIndex()
    {
        $this->resetValue();
    }

    public function delete($id)
    {
        $data = CreditPaymentOld::find($id);

        // ubah total bayar 
        $app = CreditApplicationOld::find($data['credit_app_old_id']);

        $total_bayar_baru = $app['total_bayar'] - $data['jumlah'];
        $sisa_baru = $app['sisa'] + $data['jumlah'];

        $app->update([
            'total_bayar' => $total_bayar_baru,
            'sisa' => $sisa_baru
        ]);

        $data->delete();
    }

    public function showDetail($id)
    {
        $this->detail = true;
        $this->emit('showDetail', $id);
    }

    public function storePaymentButton()
    {
        $this->store = true;
    }
}
