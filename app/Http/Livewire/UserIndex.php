<?php

namespace App\Http\Livewire;

use App\User;
use Livewire\Component;

use App\Model\OutletUser;
use App\Helpers\RoleHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{
    public $showRegisterUser, $editRegisterUser;

    protected $listeners = [
        "cancelRegister" => "handleCancelRegister",
    ];

    public function mount()
    {
        $this->showRegisterUser = false;
        $this->editRegisterUser = false;
    }

    public function render()
    {
        $user = Auth::user();
        $role = RoleHelper::getRole($user->id);
        $outletUser = OutletUser::where('user_id', $user->id)->first();
        $userss = User::all();
        
        $users = $role->name == "SUPER ADMIN" ? 
                    DB::table('users')
                        ->join('outlet_users','outlet_users.user_id','users.id')->get() : 
                    DB::table('users')
                        ->join('outlet_users','outlet_users.user_id','users.id')
                        ->where('outlet_users.outlet_id', $outletUser->outlet_id)
                        ->get();

        $partners = DB::table('users')
                        ->join('credit_sales','credit_sales.user_id','=','users.id')
                        ->get();

        return view('livewire.user-index',[
            'users' => $users,
            'partners' => $partners,
        ]);
    }

    public function showRegisterUser()
    {
        $this->showRegisterUser = true;
    }

    public function resetPassword($userId)
    {
        $updateUser = User::find($userId)->update([
            'password' => Hash::make(123456)
        ]);

        session()->flash('success', "Password Berhasil Diubah");
    }

    public function deleteUser($userId)
    {
        User::destroy($userId);
        session()->flash('success', "Pengguna Berhasil Dihapus");
    }

    public function editUser($userId)
    {
        $this->editRegisterUser = true;
        $this->emit('editUser', $userId);
    }

    public function handleCancelRegister()
    {
        $this->showRegisterUser = false;
        $this->editRegisterUser = false;
    }


}
