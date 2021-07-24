<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Model\CreditApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditApplicationIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $partnerId;

    public $user;

    public $showUpdate, $showCreate, $searchName, $paginate;

    protected $listeners = [
        'hasStored' => 'handleStored',
        'hasUpdated' => 'handleUpdated'
    ];

    public function mount($partnerId)
    {
        $this->user = Auth::user();
        $this->partnerId = $partnerId;
        $this->showUpdate = false;
        $this->showCreate = false;
        $this->searchName = '';
        $this->paginate = 5;
    }

    public function render()
    {
        $applications = DB::table('credit_applications')
                            ->join('credit_customers','credit_customers.id','=','credit_applications.credit_customer_id')
                            ->join('outlets','outlets.id','=','credit_applications.outlet_id')
                            ->where('credit_applications.credit_partner_id', $this->partnerId)
                            ->where(function($query) {
                                $query->where('credit_customers.nama', 'like', '%' . $this->searchName . '%')
                                    ->orWhere('credit_customers.no_ktp', 'like', '%' . $this->searchName . '%');
                            })
                            ->select('credit_applications.id','credit_applications.status','credit_applications.merk','credit_applications.tenor','credit_applications.angsuran','credit_applications.dp','credit_applications.outlet_id','credit_customers.nama','credit_customers.no_ktp','credit_customers.alamat','outlets.nama as nama_outlet','credit_applications.sales_name')
                            ->orderBy('status')
                            ->paginate($this->paginate);

        return view('livewire.credit-application-index', [
            'applications' => $applications
        ]);
    }

    public function create()
    {
        $this->showCreate = true;
    }

    public function cancelCreate()
    {
        $this->showCreate = false;
    }

    public function handleStored()
    {
        session()->flash('success', "Kredit dari Konsumen Berhasil Diajukan");
        $this->showCreate = false;
    }

    public function delete($id)
    {
        $delete = CreditApplication::find($id)->delete();
        session()->flash('success', "Pengajuan Kredit Konsumen Berhasil Dihapus");
    }

    public function update($id)
    {
        $this->showUpdate = true;
        $this->emit('getUpdateData', $id);
    }

    public function cancelUpdate()
    {
        $this->showUpdate = false;
    }

    public function handleUpdated()
    {
        $this->showUpdate = false;
        session()->flash('success', "Data Pengajuan Kredit Konsumen Berhasil Diubah");
    }

    public function accept($id)
    {
        $accept = CreditApplication::find($id)->update([
            'status' => 'accept',
            'sales_name' => $this->user->name
        ]);

        session()->flash('success', "Data Pengajuan Kredit Diterima");
    }

    public function reject($id)
    {
        
        $accept = CreditApplication::find($id)->update([
            'status' => 'reject',
            'sales_name' => $this->user->name
        ]);

        session()->flash('fail', "Data Pengajuan Kredit Ditolak");
    }

    public function waitList($id)
    {
        $accept = CreditApplication::find($id)->update([
            'status' => 'pending',
            'sales_name' => $this->user->name
        ]);

        session()->flash('success', "Data Pengajuan Kredit Dimasukkan Ke Daftar Tunggu");
    }
}
