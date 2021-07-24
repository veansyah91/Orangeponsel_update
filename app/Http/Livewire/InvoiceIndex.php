<?php

namespace App\Http\Livewire;

use App\Model\Stock;

use App\Model\Income;
use App\Model\Outlet;
use App\Model\Invoice;
use App\Model\Customer;
use Livewire\Component;
use App\Model\OutletUser;
use App\Model\InterOutlet;
use App\Helpers\RoleHelper;

use App\Model\InvoiceDetail;
use App\Model\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceIndex extends Component
{
    public $selectOutlet;
    public $outlets;

    public $invoice;
    public $editInvoice;
    public $nomorNota;
    public $invoiceDetails;

    public $total;
    public $bayar;
    public $sisaBayar;

    public $showBayar;

    protected $listeners = [
        'getInvoice' => "showInvoice" 
    ];

    public function mount()
    {
        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        if ($roleUser->name == 'SUPER ADMIN') {
            # code...
            $this->outlets = Outlet::all();
            $this->selectOutlet = $this->outlets[0]->id;
        } else {
            $outletUser = OutletUser::where('user_id', $user->id)->first();
            $this->selectOutlet = $outletUser->outlet_id;
        }

        $nomorNota = Invoice::where('outlet_id', $this->selectOutlet)->get()->last();
        $this->nomorNota = $nomorNota ? $nomorNota->no_nota + 1 : 1;

        $this->editInvoice = false;

        $this->bayar = 0;
        $this->showBayar = false;
        
    }

    public function render()
    {
        $this->invoice = Invoice::where('no_nota', $this->nomorNota)->where('outlet_id', $this->selectOutlet)->first();

        $this->invoiceDetails = $this->invoice ? InvoiceDetail::where('invoice_id', $this->invoice->id)->get() : '';

        $totals = $this->invoice ? InvoiceDetail::where('invoice_id', $this->invoice->id)->select(DB::raw('jumlah * jual as total'))->get() : '';

        $this->total = 0;
        if ($totals) {
            foreach ($totals as $total) {
                $this->total += $total->total;
            }
        }
        
        $this->bayar = $this->bayar ? $this->bayar : 0;
        $this->sisaBayar = $this->bayar - $this->total;

        return view('livewire.invoice-index',[
            'invoiceDetails' => $this->invoiceDetails
        ]);
    }

    public function showInvoice($nomorNota)
    {
        $this->showBayar = false ;
        $this->nomorNota = $nomorNota;
        
        $invoice = Invoice::where('no_nota', $this->nomorNota)->first();

        // cek status pembayaran
        $status = $invoice ? PaymentStatus::where('invoice_id', $invoice->id)->first() : '';
        
        if ($status) {
            $this->bayar = $status->total + $status->sisa;
        }
    }

    public function selectOutlet()
    {
        $this->emit('getOutlet', $this->selectOutlet);
    }

    public function deleteInvoice(InvoiceDetail $invoice)
    {   
        // cek stok sekarang
        $sisaStock = Stock::where('product_id', $invoice->product_id)->first();
        
        // kembalikan stock
        $tambahStock = $sisaStock->jumlah + $invoice->jumlah;

        Stock::reduceStock($invoice->product_id, $invoice->invoice->outlet_id, $tambahStock);

        $deleteInvoice = InvoiceDetail::find($invoice->id);

        // cek pelanggan
        if ($deleteInvoice->invoice->customer->outlet_id) {
            $stockOutlet = Stock::where('product_id', $invoice->product_id)->where('outlet_id', $invoice->invoice->customer->outlet_id)->first();
            $stockCurrently = $stockOutlet->jumlah - $invoice->jumlah;
            Stock::reduceStock($invoice->product_id, $invoice->invoice->customer->outlet_id, $stockCurrently);
        }

        $deleteInvoice->delete();


    }

    public function getInvoice($invoice)
    {
        // $invoiceLama = Invoice::find($invoice);

        $this->emit('showInvoice', $invoice);
    }

    public function showBayar()
    {
        $this->showBayar ? $this->showBayar = false : $this->showBayar = true;
    }

    public function saveInvoice()
    {
        // cek apakah status pembayaran telah diisi berdasarkan invoiceId
        $payment = PaymentStatus::where('invoice_id', $this->invoice->id)->first();

        $this->sisaBayar = $this->sisaBayar > 0 ? 0 : $this->sisaBayar;

        if ($payment) {
            $updatePayment = PaymentStatus::find($payment->id)->update([
                'total' => $this->total,
                'sisa' => $this->sisaBayar
            ]);
        } else {
            $newInvoice = PaymentStatus::create([
                'invoice_id' => $this->invoice->id,
                'total' => $this->total,
                'sisa' => $this->sisaBayar
            ]);
        }

        $income = Income::updateOrCreate([
            'invoice_id' => $this->invoice->id],
            ['jumlah' => $this->bayar,
        ]);

        // jika yang belanja adalah outlet maka hitung jumlah belanja 
        $invoice = Invoice::find($this->invoice->id);
        $custumer = Customer::find($invoice->customer_id);

        if ($custumer->outlet_id) {
            $invoiceDetail = DB::table('invoice_details')
                                ->where('invoice_id', $invoice->id)
                                ->select(DB::raw('jumlah * jual as total'))
                                ->get()->sum('total');
            
            $updateInterOutlet = InterOutlet::where('invoice_id', $invoice->id)->update([
                                                    'jumlah' => $invoiceDetail
                                                ]);
        }

        $this->emit('newInvoice');
        $this->bayar = 0;

        // $paymentStatus = 
    }

    public function uangPas()
    {
        $this->bayar = $this->total;
    }
}
