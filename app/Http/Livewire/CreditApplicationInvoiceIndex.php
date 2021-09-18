<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use App\Model\CreditInvoice;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditApplicationInvoiceIndex extends Component
{
    public $partnerId;

    public $creditCustomerName, $creditCustomerId, $search;

    public $showCreate, $showUpdate;

    protected $listeners = [
        'cancelCreate' => 'handleCancelCreate',
        'successCreate' => 'handleSuccessCreate',
        'cancelUpdate' => 'handleCancelUpdate',
        'successUpdate' => 'handleSuccessUpdate'
    ];

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->showCreate = false;
        $this->showUpdate = false;
    }

    public function render()
    {   
        $creditInvoiceApplications = DB::table('credit_application_invoices')
                        ->join('credit_applications','credit_applications.id','=','credit_application_invoices.credit_application_id')
                        ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                        ->where('credit_application_invoices.status','=','waiting')
                        ->where('credit_applications.credit_partner_id', $this->partnerId)
                        ->select('credit_application_invoices.id','credit_application_invoices.user_name','credit_application_invoices.product_id','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_hp','credit_applications.merk','credit_applications.email','credit_applications.password')
                        ->get();       

        return view('livewire.credit-application-invoice-index',[
            'creditInvoiceApplications' => $creditInvoiceApplications
        ]);
    }

    public function showCreate()
    {
        $this->showCreate = true;
    }

    public function handleCancelUpdate()
    {
        $this->showUpdate = false;
    }

    public function handleSuccessUpdate()
    {
        $this->showUpdate = false;
        session()->flash('success', "Pengubahan Pengajuan Kredit Berhasil");
    }

    public function handleCancelCreate()
    {
        $this->showCreate = false;
    }

    public function handleSuccessCreate()
    {
        $this->showCreate = false;
        session()->flash('success', "Kredit Telah Diajukan dan Diproses");
    }

    public function showUpdate($id)
    {
        $this->showUpdate = true;
        $this->emit('updateData', $id);
    }

    public function delete($id)
    {
        $data = CreditInvoice::find($id);

        //kembalikan stock terlebih dahulu
        $cekStock = Stock::where('product_id',$data->product_id)->first();

        $jumlahStock = $cekStock->jumlah;

        $jumlahStock++;

        $updateStock = Stock::find($cekStock->id)->update([
            'jumlah' => $jumlahStock
        ]);

        // ubah status pengajuan menjadi 1
        $updateApplication = CreditApplication::find($data->credit_application_id)->update([
            'status' => 1
        ]);

        $data->delete();

        session()->flash('success', "Pengambilan Barang Kredit Dihapus");

    }
    
}
