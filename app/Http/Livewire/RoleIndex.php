<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    public $showUpdate;

    protected $listeners = [
        'getRoles' => "showRoles", 
    ];

    public function mount()
    {
        $this->showUpdate = false;
    }

    public function render()
    {   
        $roles = Role::all();
        return view('livewire.role-index', [
            'roles' => $roles
        ]);
    }

    public function destroy($id)
    {
        $deleteRole = Role::find($id)->delete();
    }

    public function showRole($id)
    {
        $this->showUpdate = true;
        $this->emit('getRole', $id);
    }

    public function showRoles()
    {
        $this->showUpdate = false;
    }
}
