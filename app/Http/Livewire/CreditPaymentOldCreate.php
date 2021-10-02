<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditSales;
use App\Model\CreditPaymentOld;
use Illuminate\Support\Facades\DB;
use App\Model\CreditApplicationOld;
use Illuminate\Support\Facades\Auth;

class CreditPaymentOldCreate extends Component
{
    protected $listeners = [
        'showInputPayment' => 'handleShowInputPayment',
        'selectId' => 'handleSelectId',
    ];

    public $show_search;

    public $partner_id;

    public $customerId, $creditCustomerName, $creditCustomerPhone, $tipeHp, $nomor_pembayaran, $angsuran_ke, $jumlah, $tanggal, $jumlahSeharusnya;

    public function mount($partnerId)
    {
        $this->partner_id = $partnerId;
        $this->resetValue();
    }

    public function resetValue()
    {
        $this->show_search = false;
    }

    public function render()
    {
        return view('livewire.credit-payment-old-create');
    }

    public function backToIndex()
    {
        $this->emit('showIndex');
    }

    public function showNameSearch()
    {
        $this->show_search = true;
    }

    public function handleShowInputPayment()
    {
        $this->resetValue();
    }

    public function handleSelectId($id)
    {
        $this->show_search = false;
        $data = CreditApplicationOld::find($id);
        $this->customerId = $data['id'];
        $this->creditCustomerName = $data['nama'];
        $this->creditCustomerPhone = $data['no_hp'];
        $this->tipeHp = $data['tipe'];
        $this->jumlahSeharusnya = $data['angsuran'];

        // cek pembayaran sebelumnya
        $creditPayment = CreditPaymentOld::where('credit_app_old_id', $data['id'])->get()->last();
        if ($creditPayment) {
            $this->angsuran_ke = $creditPayment['angsuran_ke'] + 1;
        }
    }

    public function store()
    {
        $this->validate([
            'creditCustomerName' => 'required',
            'creditCustomerPhone' => 'required',
            'nomor_pembayaran' => 'numeric',
            'tipeHp' => 'required',
            'angsuran_ke' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ]);

        $user = Auth::user();

        $outlet = DB::table('outlet_users')
                        ->join('outlets', 'outlets.id', '=', 'outlet_users.outlet_id')
                        ->where('outlet_users.user_id', $user['id'])
                        ->first();

        // apakah user adalah collector?
        $creditSales = CreditSales::where('user_id', $user['id'])->first();

        // dapatkan jatuh tempo dari data pengajuan kredit lama 
        $creditAppOld = CreditApplicationOld::find($this->customerId);

        $bulan_ke = '+' . $this->angsuran_ke - 1 . 'month';
        $jatuh_tempo = date('Y-m-d', strtotime($bulan_ke, strtotime( $creditAppOld['tanggal_akad'] )));

        $terlambat = 0;

        if ($this->tanggal > $jatuh_tempo) {
            
            $tanggal_to_date = date_create($this->tanggal);
            $tempo_to_date = date_create($jatuh_tempo);
            $dd = date_diff($tempo_to_date, $tanggal_to_date);
            $terlambat = $dd->days;
        }

        $status = '0';

        if ($creditSales) {
            $creditPayment = CreditPaymentOld::create([
                'credit_app_old_id' => $this->customerId,
                'jumlah' => $this->jumlah,
                'angsuran_ke' => $this->angsuran_ke,
                'nomor_nota' => $this->nomor_pembayaran,
                'tanggal_bayar' => $this->tanggal,
                'jatuh_tempo' => $jatuh_tempo,
                'terlambat' => $terlambat,
                'status' => '1',
                'pencatat' => $user->name,
                'kolektor' => $user->name,
            ]);
        } else {
            $creditPayment = CreditPaymentOld::create([
                'credit_app_old_id' => $this->customerId,
                'jumlah' => $this->jumlah,
                'angsuran_ke' => $this->angsuran_ke,
                'nomor_nota' => $this->nomor_pembayaran,
                'tanggal_bayar' => $this->tanggal,
                'jatuh_tempo' => $jatuh_tempo,
                'terlambat' => $terlambat,
                'status' => '0',
                'pencatat' => $user->name,
                'outlet' => $outlet->nama
            ]);
        }

        // update tabel credit application olds
        $totalBayar = $creditAppOld['total_bayar'] + $this->jumlah;

        $sisa = $creditAppOld['sisa'] - $this->jumlah;

        $creditAppOld->update([
            'tgl_terakhir_bayar' => $this->tanggal,
            'total_bayar' => $totalBayar,
            'sisa' => $sisa
        ]);

        $this->emit('showIndex');
    }
}
