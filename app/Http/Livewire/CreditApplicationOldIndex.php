<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Model\CreditApplicationOld;

class CreditApplicationOldIndex extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    
    public $create;
    public $edit;
    public $showDetail;
    public $search;

    public $partnerId;

    protected $listeners = [
        'showIndex' => 'handleShowIndex'
    ];

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->resetState();
    }

    public function resetState()
    {
        $this->create = false;
        $this->edit = false;
        $this->showDetail = false;
    }

    public function render()
    {
        $data = CreditApplicationOld::where('credit_partner_id', $this->partnerId)
                                        ->where(function($query) {
                                            $query->where('nama', 'like', '%' . $this->search. '%')
                                                    ->orWhere('no_hp', 'like', '%' . $this->search. '%');
                                        })
                                        ->paginate(10);
        return view('livewire.credit-application-old-index', [
            'data' => $data
        ]);
    }

    public function showCreate()
    {
        $this->create = true;
    }

    public function handleShowIndex()
    {
        $this->resetState();
    }

    public function delete($id)
    {
        $delete = CreditApplicationOld::find($id)->delete();
    }

    public function edit($id)
    {
        $this->edit = true;
        $this->emit('show', $id);
    }

    public function showDetail($id)
    {
        $this->showDetail = true;
        $this->emit('showDetail', $id);
    }
}
