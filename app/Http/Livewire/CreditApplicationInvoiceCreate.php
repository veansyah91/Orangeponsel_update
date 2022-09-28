<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use App\Model\CreditApplicationInvoice;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditApplicationInvoiceCreate extends Component
{
    public $partnerId;

    public $outlet;

    public $creditCustomerName, $creditCustomerId, $search ,$showNameSearch;

    public $searchType, $showTypeSearch, $kode, $type, $productId, $stockId, $jumlahUnit, $tanggal, $password, $email;

    public $harga;

    public $applicationId;

    public $roleUser, $user;

    public function mount($partnerId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->tanggal = Date('Y-m-d');

        $this->user = Auth::user();
        $this->roleUser = RoleHelper::getRole($this->user->id);

        $this->partnerId = $partnerId;

        $outletUser = OutletUser::where('user_id', $this->user->id)->first();

        $this->outlet = $outletUser ? $outletUser->outlet_id : '';

        $this->creditCustomerName = '';
        $this->showNameSearch = false;

        $this->showTypeSearch = false;
        
        $this->search = '';
        $this->searchType = '';

        $this->harga = 0;
    }
    
    public function render()
    {
        $creditCustomers = DB::table('credit_applications')
                                ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                                ->where('credit_applications.outlet_id', $this->outlet)                 
                                ->where('credit_applications.credit_partner_id', $this->partnerId)    
                                ->where('credit_applications.status', '=', 'accept')         
                                ->where(function($query) {
                                    $query->where('credit_customers.nama', 'like', '%' . $this->search . '%')
                                          ->orWhere('credit_customers.no_hp', 'like', '%' . $this->search . '%');
                                })       
                                ->select('credit_applications.id as id','credit_applications.credit_customer_id','credit_customers.nama','credit_customers.no_hp')     
                                ->skip(0)
                                ->take(5)
                                ->get();

        $products = DB::table('stocks')
                        ->join('products','products.id','=','stocks.product_id')
                        ->where('stocks.outlet_id', $this->outlet)
                        ->where('products.kode', 'like', '%' . $this->searchType . '%')
                        ->where('stocks.jumlah','>', 0)
                        ->select('stocks.id','products.kode','products.tipe')
                        ->skip(0)
                        ->take(5)
                        ->get();

        return view('livewire.credit-application-invoice-create', [
            'creditCustomers' => $creditCustomers,
            'products' => $products
        ]);
    }

    public function showNameSearch()
    {
        $this->showNameSearch = !$this->showNameSearch;
    }

    public function selectCustomer($id, $customerName)
    {
        $this->showNameSearch = !$this->showNameSearch;
        $this->creditCustomerName = $customerName;
        $this->applicationId = $id;
    }

    public function showTypeSearch()
    {
        $this->showTypeSearch = !$this->showTypeSearch;
    }

    public function selectType($id)
    {
        $stock = Stock::find($id);

        $this->showTypeSearch = !$this->showTypeSearch;

        $this->stockId = $id;
        $this->type = $stock->product->tipe;
        $this->harga = $stock->product->jual;
        $this->jumlahUnit = $stock->jumlah;
        $this->productId = $stock->product_id;
        $this->kode = $stock->product->kode;
    }

    public function cancelCreate()
    {
        $this->emit('cancelCreate');
    }

    public function store()
    {
        $this->validate([
            'creditCustomerName' => 'required',
            'type' => 'required',
        ]);

        // buat nota
        $createInvoice = CreditApplicationInvoice::create([
            'credit_application_id' =>  $this->applicationId,
            'harga' =>  $this->harga,
            'nama_produk' => $this->searchType,
            'status' =>  'waiting',
            'kode' => $this->kode,
            'product_id' =>  $this->productId,
            'created_at' =>  $this->tanggal,
            'user_name' => $this->user->name
        ]);

        // ubah status pengajuan
        $update = CreditApplication::find($this->applicationId)->update([
            'status' => 'taken',
            'email' => $this->email,
            'password' => $this->password,
        ]);

        
        $this->emit('successCreate');
    }
}
