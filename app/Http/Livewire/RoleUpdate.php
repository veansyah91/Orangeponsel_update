<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Spatie\Permission\Models\Role;

class RoleUpdate extends Component
{
    public $roleName, $roleId;

    protected $listeners = [
        'getRole' => "showRole" 
    ];

    public function render()
    {
        return view('livewire.role-update');
    }

    public function showRole($id)
    {
        $role = Role::find($id);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
    }

    public function cancelUpdate()
    {
        $this->emit('getRoles');
    }

    public function update()
    {
        $updateRole = Role::find($this->roleId)->update([
            'name' => strtoupper($this->roleName)
        ]);

        $this->emit('getRoles');
    }
}
