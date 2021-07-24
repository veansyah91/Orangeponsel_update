<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCreate extends Component
{
    public $role;

    public function mount()
    {
        $this->role;
    }

    public function render()
    {
        
        return view('livewire.role-create');
    }

    public function store()
    {
        $this->validate([
            'role' => 'required',
        ]);

        $role = Role::create(['name' => strtoupper($this->role)]);
        
        $this->role = '';
        $this->emit('getRoles');
    }
}
