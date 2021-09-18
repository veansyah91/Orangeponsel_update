<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Model\CreditCollect;
use App\Model\CreditPayment;
use App\Model\CreditCustomer;
use App\Model\CreditApplication;
use App\Model\CreditApplicationOld;
use App\Model\CreditApplicationInvoice;

use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CreditDebtIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $partnerId;
    public $showStatus;

    public $delaySet;
    public $detail;
    public $pengambilan_lama;

    protected $listeners = [
        'showIndex' => 'handleShowIndex',
        'showHistories' => 'handleShowIndex'
    ];

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->showStatus = false;
        $this->resetValue();
    }

    public function resetValue()
    {
        $this->delaySet = false;
        $this->detail = false;
    }

    public function render()
    {
        $data = DB::table('credit_collects')
                    ->where('credit_partner_id', $this->partnerId)
                    ->where(function($query) {
                        $query->whereNull('tenggang')
                            ->orWhere('tenggang', '<=', Date('Y-m-d'));
                    })
                    ->where('terlambat', '>', 0)
                    ->orderBy('terlambat','desc')
                    ->paginate(10);
                    
                    

        return view('livewire.credit-debt-index', [
            'data' => $data
        ]);
    }

    public function update()
    {
        // ambil data pegajuan kredit yang belum lunas
        // data baru
        $creditApps = CreditApplication::where('credit_partner_id', $this->partnerId)
                                        ->where('status', 'taken')
                                        ->where('lunas', '0')
                                        ->get();

        // data lama
        $creditAppOlds = CreditApplicationOld::where('credit_partner_id', $this->partnerId)
                                                ->where('sisa', '>', 0)
                                                ->get();

        $creditAppOld = CreditApplicationOld::first();

        foreach ($creditApps as $creditApp) {
            // hitung hutang yang seharusnya dibayar hingga bulan ini 
            // apabila berlebih tidak dimasukkan ke tabel penagihan dan atau terlambat diset nilai 0

            // hitung jumlah bulan dari pengambilan hingga sekarang
            $creditInv = CreditApplicationInvoice::where('credit_application_id', $creditApp->id)->first();

            $tanggal_akad = date_create($creditInv['created_at']->toDateString());
            $sekarang = date_create(Date('Y-m-d'));
            $selisih = date_diff($sekarang, $tanggal_akad);

            $biodata = CreditCustomer::find($creditApp->credit_customer_id);            

            // output:
            // 1. hari : $selisih->d 
            // 2. bulan: $selisih->m 
            // 3. tahun: $selisih->y 

            $jumlah_seharusnya_telah_bayar = $creditApp['angsuran'] * ($selisih->m + 1);
            

            // dapatkan jumlah angsuran yang telah dibayar 
            $creditPaymentSum = CreditPayment::where('credit_application_id', $creditApp->id)->get()->sum('jumlah');

            if ($jumlah_seharusnya_telah_bayar > $creditPaymentSum) {
                //hitung lama keterlambatan 
                //hitung jatuh tempo yaitu dengan melakukan pembagian antara creditPaymentSum dengan angsuran
                //maka akan didapat berapa bulan telah membayar
                //lalu akumulasikan jumlah bulan telah bayar ditambah 1 bulan, maka didapatkan jatuh tempo

                $telah_membayar = intval($creditPaymentSum / $creditApp['angsuran']);

                $bulan_ke = '+' . $telah_membayar . 'month';

                $jatuh_tempo = date('Y-m-d', strtotime($bulan_ke, strtotime( $creditInv['created_at']->toDateString() )));

                $jatuh_tempo_to_date = date_create($jatuh_tempo);
                
                $terlambat = date_diff($sekarang, $jatuh_tempo_to_date);

                // cek apakah sudah diinput ke tabel credit_collects
                $creditCollect =  CreditCollect::where('credit_application_id', $creditApp->id)->first();

                if ($creditCollect) {
                    $creditCollect->update([
                        'terlambat' => $terlambat->days,
                        'credit_partner_id' => $this->partnerId,
                    ]);
                } else {
                    $create = CreditCollect::create([
                        'credit_application_id' => $creditApp->id,
                        'nama' => $biodata['nama'],
                        'no_hp' => $biodata['no_hp'],
                        'pengambilan_lama' => false,
                        'terlambat' => $terlambat->days,
                        'credit_partner_id' => $this->partnerId,
                    ]);
                }
            }

            else {

                $creditCollect = CreditCollect::updateOrCreate(
                    [
                        'credit_application_id' => $creditApp->id,
                        'nama' => $biodata['nama'],
                        'no_hp' => $biodata['no_hp'],
                        'pengambilan_lama' => false,
                        'credit_partner_id' => $this->partnerId,
                    ],
                    [
                        'terlambat' => 0,
                        'tenggang' => null,
                        'keterangan' => null,
                    ]
                );
            }
        }

        foreach ($creditAppOlds as $creditAppOld) {
            $tanggal_akad = date_create($creditAppOld->tanggal_akad);
            $sekarang = date_create(Date('Y-m-d'));
            $selisih = date_diff($sekarang, $tanggal_akad);
            $jumlah_seharusnya_telah_bayar = $creditAppOld->angsuran * ($selisih->m + 1);            

            // dapatkan jumlah angsuran yang telah dibayar 
            $creditPaymentSum = $creditAppOld->total_bayar;

            if ($jumlah_seharusnya_telah_bayar > $creditPaymentSum) {
                //hitung lama keterlambatan 
                //hitung jatuh tempo yaitu dengan melakukan pembagian antara creditPaymentSum dengan angsuran
                //maka akan didapat berapa bulan telah membayar
                //lalu akumulasikan jumlah bulan telah bayar ditambah 1 bulan, maka didapatkan jatuh tempo

                $telah_membayar = intval($creditPaymentSum / $creditAppOld->angsuran);

                $bulan_ke = '+' . $telah_membayar . 'month';

                $jatuh_tempo = date('Y-m-d', strtotime($bulan_ke, strtotime( $creditAppOld->tanggal_akad)));

                $jatuh_tempo_to_date = date_create($jatuh_tempo);
                
                $terlambat = date_diff($sekarang, $jatuh_tempo_to_date);

                // cek apakah sudah diinput ke tabel credit_collects
                $creditCollect =  CreditCollect::where('credit_app_old_id', $creditAppOld->id)->first();

                if ($creditCollect) {
                    $creditCollect->update([
                        'terlambat' => $terlambat->days,
                        'credit_partner_id' => $this->partnerId,
                    ]);
                } else {
                    $create = CreditCollect::create([
                        'credit_app_old_id' => $creditAppOld->id,
                        'nama' => $creditAppOld->nama,
                        'no_hp' => $creditAppOld->no_hp,
                        'pengambilan_lama' => true,
                        'credit_partner_id' => $this->partnerId,
                        'terlambat' => $terlambat->days,
                    ]);
                }
            }
        
            else {

                $creditCollect = CreditCollect::updateOrCreate(
                    [
                        'credit_app_old_id' => $creditAppOld->id,
                        'nama' => $creditAppOld->nama,
                        'no_hp' => $creditAppOld->no_hp,
                        'credit_partner_id' => $this->partnerId,
                        'pengambilan_lama' => false,
                    ],
                    [
                        'terlambat' => 0,
                        'tenggang' => null,
                        'keterangan' => null,
                    ]
                );
            }
            
        }

        session()->flash('success', "Berhasil Melakukan Update");
    }

    public function pending($id)
    {
        $this->delaySet = true;
        $this->emit('showUpdate', $id);
    }

    public function handleShowIndex()
    {
        $this->resetValue();
    }

    public function detail($id)
    {
        $this->detail = true;

        $customerData = CreditCollect::find($id);

        if ($customerData['credit_app_old_id']) {
            $this->pengambilan_lama = true;
            $this->emit('showDetail', $customerData['credit_app_old_id']);
        } else {
            $this->pengambilan_lama = false;
            $this->emit('showDetail', $customerData['credit_application_id']);
        }
    }

}
