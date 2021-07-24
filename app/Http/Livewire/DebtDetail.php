<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\DebtPayment;
use App\Model\PaymentStatus;
use App\Model\Income;
use App\Model\Invoice;

use App\Helpers\InvoiceHelper;

class DebtDetail extends Component
{
    public $invoiceId, $paymentId, $customerName, $sisa, $jumlahBayar;

    public function mount($invoiceId, $paymentId, $customerName, $sisa){
        $this->invoiceId = $invoiceId;
        $this->paymentId = $paymentId;
        $this->customerName = $customerName;
        $this->sisa = $sisa * -1 - InvoiceHelper::getDebtPaymentTotal($invoiceId);
        $this->jumlahBayar = $this->sisa;
    }

    public function render()
    {
        $sisa = $this->sisa;
        return view('livewire.debt-detail',[
            'sisa' => $sisa
        ]);
    }

    public function makePayment(){
        $this->validate([
            'jumlahBayar' => 'numeric|required',
        ]);

        date_default_timezone_set('Asia/Jakarta');

        $paymentCreate = DebtPayment::create([
            'invoice_id' => $this->invoiceId,
            'bayar' => $this->jumlahBayar,
            'updated_at' => Date('Y-m-d H:i:s')
        ]);

        $payment = PaymentStatus::where('invoice_id', $this->invoiceId)->first();
        
        $this->sisa = $payment->sisa * -1 - InvoiceHelper::getDebtPaymentTotal($this->invoiceId);
            
        $income = Income::create([
            'debt_payment_id' => $paymentCreate->id,
            'jumlah' => $this->jumlahBayar,
            'updated_at' => Date('Y-m-d H:i:s')
        ]);

        $this->jumlahBayar = $this->sisa;
        $this->emit('getDebtPayment');
    }

    public function paymentDelete($id){
        $payment = DebtPayment::find($id);
        $payment->delete();

        $payment = PaymentStatus::where('invoice_id', $this->invoiceId)->first();
        $this->sisa = $payment->sisa * -1 - InvoiceHelper::getDebtPaymentTotal($this->invoiceId);
        
        $this->jumlahBayar = $this->sisa;
    }
}
