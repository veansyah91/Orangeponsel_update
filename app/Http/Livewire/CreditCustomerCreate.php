<?php

namespace App\Http\Livewire;

use Faker\Factory;
use App\Model\Outlet;
use Livewire\Component;
use App\Helpers\RoleHelper;
use App\Helpers\UserHelper;
use App\Model\CreditCustomer;
use Illuminate\Support\Facades\Auth;

class CreditCustomerCreate extends Component
{
    public $no_ktp, $no_kk, $nama, $jenis_kelamin, $alamat, $no_hp, $outlet, $outlets;

    public function mount()
    {
        $user = Auth::user();
        $roleUser = RoleHelper::getRole($user->id);

        $this->outlets = Outlet::all();

        if ($roleUser->name == 'SUPER ADMIN' || $roleUser->name == 'SALES') {
            $this->outlet = $this->outlets[0]->id;
        } else {
            $this->outlet = UserHelper::getOutletUser($user->id)->outlet_id;
        }
    }

    public function render()
    {
        return view('livewire.credit-customer-create', [
            'outlets' => $this->outlets
        ]);
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_kk' => 'required|unique:credit_customers',
            'no_ktp' => 'required|unique:credit_customers',
            'alamat' => 'required',
            'no_hp' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $store = CreditCustomer::create([
            'nama' => $this->nama,
            'no_ktp' => $this->no_ktp,
            'no_kk' => $this->no_kk,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
        ]);

        // $faker = Factory::create('id_ID');
        
        // for ($i=0; $i < 100; $i++) { 
        //     $store = CreditCustomer::create([
        //             'nama' => $faker->name,
        //             'no_ktp' => $faker->ean13,
        //             'no_kk' => $faker->ean13,
        //             'jenis_kelamin' => "Laki-Laki",
        //             'alamat' => $faker->address,
        //             'no_hp' => $faker->phoneNumber,
        //         ]); 
        // }

        $this->emit('hasStoredData');

    }
}
