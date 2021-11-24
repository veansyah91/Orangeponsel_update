<?php

namespace App\Http\Livewire;

use App\Model\Service;
use Livewire\Component;
use App\Model\OutletUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ServiceIndex extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'showIndex' => 'handleShowIndex'
    ];

    public $showCreate, $showUpdate;

    public function mount()
    {
        $this->resetData();
    }

    public function resetData()
    {
        $this->showCreate = false;
        $this->showUpdate = false;
    }

    public function render()
    {
        $user = Auth::user();
        $outletUser = OutletUser::where('user_id', $user['id'])->first();

        $services = Service::where('outlet_id', $outletUser['outlet_id'])
                            ->orderByDesc('created_at')
                            ->paginate(10);
                            
        return view('livewire.service-index', [
            'services' => $services
        ]);
    }

    public function showCreateFunc()
    {
        $this->showCreate = true;
    }

    public function handleShowIndex()
    {
        $this->resetData();
    }

    public function delete($id)
    {
        $delete = Service::find($id)->delete();
    }

    public function update($id)
    {
        $this->showUpdate = true;
        $this->emit('showUpdate', $id);
    }

    public function cancelSetStateFunc($id)
    {
        $delete = Service::find($id)->update([
            'status' => 'cancel'
        ]);
    }
}
