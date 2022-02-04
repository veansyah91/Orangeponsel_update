<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Model\Invoice;
use App\Model\InvoiceDetail;
use App\Model\Customer;
use App\Model\Product;
use App\Model\Stock;
use App\Model\InterOutlet;


use App\Helpers\CustomerHelper;

use Illuminate\Support\Facades\DB;

class InvoiceCreate extends Component
{
    public $showUpdate;

    public $tanggal;
    public $jumlah;
    public $kode;
    public $tipe;
    public $harga;

    public $outletId;
    public $outletCheck;

    public $nomorNota;
    public $nomorNotaSekarang;

    public $showCustomerInputList;
    public $customerInputLists;
    public $customer;
    public $customerId;

    public $showProductInputList, $showProductInput;
    public $productInputLists;
    public $product;
    public $productId;

    public $invoiceId;
    public $stockLama = '';

    public $disable;

    protected $listeners = [
        'getOutlet' => 'showOutlet',
        'showInvoice' => 'handleShowInvoice',
        'newInvoice' => 'handleNewInvoice',
    ];

    public function mount($outletId)
    {
        
        // $this->outletId = $selectOutlet;
        date_default_timezone_set('Asia/Jakarta');
        $this->tanggal = Date('Y-m-d');

        $this->outletId = $outletId;        
        $this->resetInputList();

        $nomorNota = Invoice::where('outlet_id', $this->outletId)->get()->last();
        
        $this->nomorNotaSekarang = $nomorNota ? $nomorNota->no_nota + 1 : 1;
        $this->nomorNota = $this->nomorNotaSekarang;
    }

    public function render()
    {
        $showProductInput = $this->customer ? true : false;
        $this->productInputLists = DB::table('stocks')
                                        ->join('products','products.id','=','stocks.product_id')
                                        ->where('products.kode', 'like', '%' . $this->kode . '%')
                                        ->where('stocks.jumlah','>',0)
                                        ->where('stocks.outlet_id', $this->outletId)
                                        ->select('products.kode','products.tipe','products.jual','stocks.product_id')
                                        ->skip(0)
                                        ->take(5)
                                        ->get();

                                        // dd($this->outletId);

        $this->customerInputLists = Customer::where('outlet_id','!=', $this->outletId)->orWhere('outlet_id', null)->where('nama', 'like', '%' . $this->customer . '%')->skip(0)->take(5)->get();
        return view('livewire.invoice-create',[
            'customerInputLists' => $this->customerInputLists,
            'showProductInput' => $showProductInput
        ]);
    }

    public function resetInputList()
    {
        $this->showCustomerInputList = false;
        $this->showProductInputList = false;

        $this->jumlah = 1;
        $this->harga = 0;
        $this->showUpdate =  false;
        $this->kode = '';
        $this->tipe = '';
        $this->invoiceId = '';

        $this->disable = false;
        
    }

    public function notaBaru()
    {
        $this->getInvoiceDetail();
    }

    public function searchNota()
    {
        $this->resetInputList();
    }

    public function getInvoiceDetail()
    {
        $this->customerId = Invoice::where('no_nota', $this->nomorNota)->select('customer_id')->first() ? Invoice::where('no_nota', $this->nomorNota)->select('customer_id')->first()->customer_id : '';
        $this->customer = CustomerHelper::getName($this->customerId) ? CustomerHelper::getName($this->customerId)->nama : '';
        $this->emit('getInvoice', $this->nomorNota);
    }

    public function showCustomer()
    {
        $this->showCustomerInputList ? $this->showCustomerInputList = false : $this->showCustomerInputList = true;
    }

    public function inputCustomer()
    {
        $this->showCustomerInputList = true;
    }

    public function selectcustomer($id, $nama)
    {
        $this->customerId = $id;
        $this->customer = $nama;
        $this->showProductInputList = false;
        $this->showCustomerInputList = false;
    }

    public function showSearchProduct()
    {
        $this->showProductInputList ? $this->showProductInputList = false : $this->showProductInputList = true;
    }

    public function inputSearchProduct()
    {
        $this->showProductInputList = true;
    }

    public function selectProduct(Product $product)
    {
        $this->productId = $product->id;
        $this->kode = $product->kode;
        $this->tipe = $product->tipe;

        // dd($this->customerId);
        $this->harga = CustomerHelper::getName($this->customerId)['outlet_id'] ? $product->modal : $product->jual;
        $this->showProductInputList = false;
        $this->showCustomerInputList = false;
    }

    public function saveInvoice()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = Date('Y-m-d H:i:s');

        $this->validate([
            'kode' => 'required',
            'customer' => 'required',
            'nomorNota' => 'required|numeric',
        ]);


        $stock = Stock::checkStock($this->productId, $this->outletId);

        $sisa = $stock->jumlah >= $this->jumlah ? $stock->jumlah - $this->jumlah : 0;
        
        Stock::reduceStock($this->productId, $this->outletId, $sisa);

        $this->jumlah = $this->jumlah > $stock ->jumlah? $stock->jumlah : $this->jumlah;

        // cek ada atau tidak nota berdasarkan nota
        $invoice = Invoice::where('outlet_id', $this->outletId)->where('no_nota', $this->nomorNota)->first();

        if ($invoice) {
            $invoiceUpdate = Invoice::where('id', $invoice->id)->update(['outlet_id' => $this->outletId,'customer_id' => $this->customerId]);
        }else {
            $invoice = Invoice::create(['no_nota' => $this->nomorNota, 'outlet_id' => $this->outletId, 'customer_id' => $this->customerId]);
        }

        $invoiceDetail = InvoiceDetail::create([
            'invoice_id' => $invoice->id,
            'product_id' => $this->productId,
            'jumlah' => $this->jumlah,
            'jual' => $this->harga,
            'created_at' => $tanggal
        ]);

        // jika yang ambil barang adalah outlet maka terjadi penambahan stock pada outlet baru
        if (CustomerHelper::getName($this->customerId)->outlet_id) {    
            // cek stock yang ada 
            $cekStock = Stock::checkStock($this->productId, CustomerHelper::getName($this->customerId)->outlet_id);

            if ($cekStock) {
                $updateStock = Stock::where('id', $cekStock->id)->update([
                    'jumlah' => $cekStock->jumlah + $this->jumlah
                ]);
            } else {
                $storeStock = Stock::create([
                    'product_id' => $this->productId,
                    'outlet_id' => CustomerHelper::getName($this->customerId)->outlet_id,
                    'jumlah' => $this->jumlah
                ]);
            }

            // masukkan ke transaksi antar outlet
            // cek apakah sudah dibuat transaksi antar outlet
            $interOutlet = InterOutlet::firstOrCreate(
                [
                    'pihak_1' => $this->outletId,
                    'pihak_2' => CustomerHelper::getName($this->customerId)->outlet_id,
                    'invoice_id' => $invoice->id,
                    'konfirmasi' => 'check',
                ]);

        }

        $this->kode = '';
        $this->tipe = '';
        $this->jumlah = 1;

        $this->getInvoiceDetail();
    }

    public function cancelUpdateInvoice()
    {
        $this->resetInputList();
    }

    public function updateInvoice()
    {

        // cek stok sekarang
        $sisaStock = Stock::where('product_id', $this->productId)->where('outlet_id', $this->outletId)->first();

        // kembalikan stok 
        $tambahStock = $sisaStock->jumlah + $this->stockLama;


        $updateStock = Stock::where('product_id', $this->productId)->update([
            'jumlah' => $tambahStock
        ]);


        $stock = Stock::checkStock($this->productId,$this->outletId);

        $sisa = $stock->jumlah >= $this->jumlah ? $stock->jumlah - $this->jumlah : 0;
        
        Stock::reduceStock($this->productId, $this->outletId, $sisa);

        $this->jumlah = $this->jumlah > $stock ->jumlah? $stock->jumlah : $this->jumlah;

        // update invoice
        $updateInvoice = InvoiceDetail::find($this->invoiceId)->update([
                    'jumlah' => $this->jumlah,
                    'product_id' => $this->productId,
                    'jual' => $this->harga,
        ]);

        $this->resetInputList();
        $this->getInvoiceDetail();

    }

    public function showOutlet($outletId)
    {
        $this->outletId = $outletId;
        $this->mount($outletId);
    }

    public function handleShowInvoice(InvoiceDetail $invoiceLama)
    {
        $this->invoiceId = $invoiceLama->id;
        $this->stockLama = $invoiceLama->jumlah;
        $this->jumlah = $invoiceLama->jumlah;
        $this->customer = $invoiceLama->invoice->customer->nama;
        $this->productId = $invoiceLama->product_id;
        $this->kode = $invoiceLama->product->kode;
        $this->tipe = $invoiceLama->product->tipe;
        $this->harga = $invoiceLama->product->jual;

        $this->showUpdate = true;
        $this->disable = true;
    }

    public function handleNewInvoice()
    {
        // $this->nomorNota += 1;
        // $this->nomorNotaSekarang = $this->nomorNota;
        $this->mount($this->outletId);
        $this->getInvoiceDetail();

    }

}
