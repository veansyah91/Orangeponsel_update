<?php

namespace App\Http\Livewire;

use App\Model\Product;
use Livewire\Component;
use App\Model\OutletUser;
use App\Model\CreditSales;
use App\Model\CreditCollect;
use App\Model\CreditPayment;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Model\CreditApplicationInvoice;

class CreditPaymentCreate extends Component
{
    public $showSearchBiodata;

    public $searchName, $creditCustomerName, $creditCustomerPhone, $tipeHp;

    public $jumlah, $jumlahPlaceholder, $angsuran_ke, $nomor_pembayaran, $tanggal_bayar, $jatuh_tempo, $terlambat, $status, $sales_name, $sisa, $tanggal, $credit_app_id;

    public $partner_id;

    public $outlet_id;

    public function mount($partnerId)
    {
        $this->partner_id = $partnerId;
        $this->showSearchBiodata = false;
    }

    public function render()
    {
        $creditCustomers = DB::table('credit_applications')
                                ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')     
                                ->where('credit_applications.credit_partner_id', $this->partner_id)    
                                ->where('credit_applications.status', 'taken')
                                ->where(function($query) {
                                    $query->whereNull('credit_applications.lunas')
                                          ->orWhere('credit_applications.lunas', '0');
                                }) 
                                ->where(function($query) {
                                    $query->where('credit_customers.nama', 'like', '%' . $this->searchName . '%')
                                          ->orWhere('credit_customers.no_hp', 'like', '%' . $this->searchName . '%');
                                })       
                                ->select('credit_applications.id as id','credit_applications.credit_customer_id','credit_customers.nama','credit_customers.no_hp')     
                                ->skip(0)
                                ->take(5)
                                ->get();

        return view('livewire.credit-payment-create', [
            'creditCustomers' => $creditCustomers
        ]);
    }

    public function showNameSearch()
    {
        $this->showSearchBiodata = !$this->showSearchBiodata;
    }

    public function selectCustomer($credit_app_id, $credit_customer_name, $credit_customer_phone)
    {

        $creditApp = CreditApplication::find($credit_app_id);

        // cari detail kredit
        $this->showSearchBiodata = !$this->showSearchBiodata;
        $this->creditCustomerName = $credit_customer_name;
        $this->creditCustomerPhone = $credit_customer_phone;
        $this->credit_app_id = $credit_app_id;

        $creditProductInvoice = CreditApplicationInvoice::where('credit_application_id', $credit_app_id)->first();
        $creditProduct = Product::find($creditProductInvoice->product_id);
        $this->tipeHp = $creditProduct['tipe'];        

        // cari pembayaran terakhir
        // jika belum ada maka isi nilai angsuran_ke = 1
        $lastCreditPayment = CreditPayment::where('credit_application_id', $credit_app_id)->get()->last();
        if ($lastCreditPayment) {
            $this->angsuran_ke += $lastCreditPayment['angsuran_ke'] + 1;
        } else {
            $this->angsuran_ke = 1;
        }
        
        $this->jumlahPlaceholder = $creditApp['angsuran'];
    }

    public function store()
    {        
        $user = Auth::user();

        $outlet = DB::table('outlet_users')
                        ->join('outlets', 'outlets.id', '=', 'outlet_users.outlet_id')
                        ->where('outlet_users.user_id', $user['id'])
                        ->first();

        // apakah user adalah collector?
        $creditSales = CreditSales::where('user_id', $user['id'])->first();

        $this->validate([
            'creditCustomerName' => 'required',
            'nomor_pembayaran' => 'numeric',
            'jumlah' => 'required|numeric',
            'tanggal' => 'required',
        ]);

        // ambil jatuh tempo berdasarkan nota pengambilan barang
        $creditAppInv = CreditApplicationInvoice::where('credit_application_id', $this->credit_app_id)->first();

        $bulan_ke = '+' . ($this->angsuran_ke - 1) . 'month';

        $this->jatuh_tempo = date('Y-m-d', strtotime($bulan_ke, strtotime( $creditAppInv['created_at']->toDateString() )));

        if ($this->tanggal > $this->jatuh_tempo) {
            
            $tanggal_to_date = date_create($this->tanggal);
            $tempo_to_date = date_create($this->jatuh_tempo);
            $dd = date_diff($tempo_to_date, $tanggal_to_date);
            $this->terlambat = $dd->days;

        } else {
            $this->terlambat = 0;
        }

        $this->status = '0';        

        if ($creditSales) {
            $creditPayment = CreditPayment::create([
                'credit_application_id' => $this->credit_app_id,
                'jumlah' => $this->jumlah,
                'angsuran_ke' => $this->angsuran_ke,
                'nomor_pembayaran' => $this->nomor_pembayaran,
                'tanggal_bayar' => $this->tanggal,
                'jatuh_tempo' => $this->jatuh_tempo,
                'terlambat' => $this->terlambat,
                'status' => '1',
                'note-taker' => $user->name,
                'sales_name' => $user->name,
            ]);
        } else {
            $creditPayment = CreditPayment::create([
                'credit_application_id' => $this->credit_app_id,
                'jumlah' => $this->jumlah,
                'angsuran_ke' => $this->angsuran_ke,
                'nomor_pembayaran' => $this->nomor_pembayaran,
                'tanggal_bayar' => $this->tanggal,
                'jatuh_tempo' => $this->jatuh_tempo,
                'terlambat' => $this->terlambat,
                'status' => '0',
                'note-taker' => $user->name,
                'outlet' => $outlet->nama
            ]);
        }
        
        // hitung semua hutang
        $creditApplication = CreditApplication::find($this->credit_app_id);
        $totalHutang = $creditApplication->dp + ($creditApplication->tenor * $creditApplication->angsuran);

        // hitung semua angsuran yang telah dibayar
        $totalBayar = CreditPayment::where('credit_application_id', $this->credit_app_id)->get()->sum('jumlah');

        if ($totalHutang == $totalBayar) {
            $creditApplication = CreditApplication::find($this->credit_app_id)->update([
                'lunas' => '1'
            ]);

            $creditCollect = CreditCollect::where('credit_application_id', $this->credit_app_id)->first();

            if ($creditCollect) {
                $creditCollect->delete();
            }
        }
        $this->emit('showIndex');
    }

    public function backToIndex()
    {
        $this->emit('showIndex');
    }
}