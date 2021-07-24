<?php

namespace App\Http\Livewire;

use App\Model\Stock;
use App\Model\Product;
use Livewire\Component;
use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use App\Model\CreditApplicationInvoice;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditApplicationInvoiceUpdate extends Component
{
    public $partnerId;

    public $outlet;
    
    public $creditCustomerName, $creditCustomerId, $search ,$showNameSearch;

    public $searchType, $showTypeSearch, $type, $kode, $productId, $stockId, $jumlahUnit, $tanggal, $password, $email, $productIdLama;

    public $harga, $invoiceId;

    public $applicationId;

    protected $listeners = [
        'updateData' => 'handleUpdateData'
    ];

    public function mount($partnerId)
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->tanggal = Date('Y-m-d');

        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        $this->partnerId = $partnerId;

        $outletUser = OutletUser::where('user_id', $user->id)->first();

        $this->outlet = $outletUser->outlet_id;

        $this->showNameSearch = false;

        $this->showTypeSearch = false;
        
        $this->search = '';
        $this->searchType = '';
    }

    public function render()
    {
        $creditCustomers = DB::table('credit_applications')
                                ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                                ->where('credit_applications.outlet_id', $this->outlet)                 
                                ->where('credit_applications.credit_partner_id', $this->partnerId)    
                                ->where('credit_applications.status', '=', 1)         
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
        
        return view('livewire.credit-application-invoice-update',[
            'creditCustomers' => $creditCustomers,
            'products' => $products
        ]);
    }

    public function handleUpdateData($id)
    {
        // cari isi nota credit
        $creditApplicationInvoice = CreditApplicationInvoice::find($id);
        $this->invoiceId = $creditApplicationInvoice->id;

        // cari product
        $product = Product::find($creditApplicationInvoice->product_id);

        $this->type = $product->tipe;
        $this->productId = $creditApplicationInvoice->product_id;
        $this->productIdLama = $creditApplicationInvoice->product_id;
        $this->harga = $creditApplicationInvoice->harga;
        $this->email = $creditApplicationInvoice->email;
        $this->password = $creditApplicationInvoice->password;
        $this->kode = $creditApplicationInvoice->kode;

        // cari Customer
        $creditApplication = CreditApplication::find($creditApplicationInvoice->credit_application_id);

        // tampilkan nama konsumen 
        $this->creditCustomerName = $creditApplication->creditCustomer->nama;
        $this->password = $creditApplication->password;
        $this->email = $creditApplication->email;

        // simpan applicationId
        $this->applicationId = $creditApplication->id;


        $stock = Stock::where('product_id', $this->productIdLama)->where('outlet_id', $this->outlet)->first();
        $this->stockId = $stock->id;
    }

    public function cancelUpdate()
    {
        $this->emit('cancelUpdate');
    }
    
    public function update()
    {
        
        $this->validate([
            'creditCustomerName' => 'required',
            'type' => 'required',
        ]);

        // kembalikan tambahkan stok awal
        $stock = Stock::where('product_id', $this->productIdLama)->where('outlet_id', $this->outlet)->first();
        $stockBaru = $stock->jumlah + 1;
        $stock->update([
            'jumlah' => $stockBaru
        ]);

        $update = CreditApplicationInvoice::find($this->invoiceId)->update([
            'credit_application_id' =>  $this->applicationId,
            'harga' =>  $this->harga,
            'nama_produk' => $this->searchType,
            'status' =>  'waiting',
            'kode' => $this->kode,
            'product_id' =>  $this->productId,
        ]);

        // ubah status pengajuan
        $update = CreditApplication::find($this->applicationId)->update([
            'status' => 'taken',
            'email' => $this->email,
            'password' => $this->password,
        ]);

        // kurangi jumlah stock
        $this->jumlahUnit--;      
        $updateStock = Stock::find($this->stockId)->update([
            'jumlah' => $this->jumlahUnit
        ]);

        $this->emit('successUpdate');

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
        $this->kode= $stock->product->kode;
    }
}
