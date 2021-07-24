<?php

namespace App\Http\Livewire;

use App\User;

use App\Model\Outlet;
use Livewire\Component;
use App\Model\OutletUser;
use App\Model\CreditSales;
use App\Helpers\RoleHelper;
use App\Model\CreditPartner;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUser extends Component
{
    public $name, $email, $password, $outlet, $role, $roleUser, $user, $outletUser, $partner;

    public function mount(){
        $this->resetInput();
    }

    public function render()
    {
        $roles = $this->roleUser->name == "SUPER ADMIN" ? 
                    Role::all() : 
                    Role::where('name', '<>', 'SUPER ADMIN')->get();
                    
        $outlets = Outlet::all();

        $partners = CreditPartner::all();
        return view('livewire.register-user',[
            'roles' => $roles,
            'outlets' => $outlets,
            'partners' => $partners,
        ]);
    }

    public function cancelRegisterUser()
    {
        $this->emit('cancelRegister');
    }

    public function store()
    {
    
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make(123456),
        ]);

        $role = $user->assignRole($this->role);

        if ($this->role != 'SUPER ADMIN') {
            if ($this->outlet) {
                $outletUser = OutletUser::create([
                    'user_id' => $user->id,
                    'outlet_id' => $this->outlet
                ]);
            }
            
            if ($this->partner) {
                $creditSales = CreditSales::create([
                    'user_id' => $user->id,
                    'credit_partner_id' => $this->partner
                ]);
            }            
        }        

        $this->resetInput();
    }

    public function resetInput()
    {
        $this->name = '';
        $this->password = '';
        $this->email = '';

        $this->user = Auth::user();
        $this->roleUser = RoleHelper::getRole($this->user->id);
        $this->outletUser = OutletUser::where('user_id', $this->user->id)->first();

        $this->outlet = $this->roleUser->name == "SUPER ADMIN" ? 
                        Outlet::first()->id : 
                        $this->outletUser->outlet_id;

        $this->role = $this->roleUser->name == "SUPER ADMIN" ?
                        Role::first()->name :    
                        $this->roleUser->name;     
             
    }

    public function selectOutlet()
    {
        $this->partner = '';
    }

    public function selectPartner()
    {

        $this->outlet = '';
    }
}
