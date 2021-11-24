<?php

namespace App\Http\Livewire;

use App\Model\Service;
use Livewire\Component;
use App\Model\OutletUser;
use Illuminate\Support\Facades\Auth;

class ServiceInvoiceIndex extends Component
{
    public $showSearch, $labelButton;

    public function mount()
    {
        $this->resetState();
    }

    public function resetState()
    {
        $this->showSearch = false;
    }

    public function render()
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();
        $services = Service::where('outlet_id', $outletUser['outlet_id'])->get();
        return view('livewire.service-invoice-index', [
            'services' => $services
        ]);
    }

    public function showSearchFunc()
    {
        $this->showSearch = !$this->showSearch;
    }
}
