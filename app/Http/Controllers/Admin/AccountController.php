<?php

namespace App\Http\Controllers\Admin;

use App\Model\Account;
use App\Model\OutletUser;
use Illuminate\Http\Request;
use App\Model\AccountCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        return view('admin.account.index', 
            [
                'user' => Auth::user(),
                'outletUser' => OutletUser::where('user_id', Auth::user()->id)
                ->first()
            ]);
    }

    public function getData()
    {
        $data = request('is_active') ? 
                        Account::where('outlet_id', request('outletId'))
                        ->where('name', 'like', '%' . request('search') . '%')    
                        ->where('is_active', 1)
                        ->orderBy('code', 'asc')
                        ->get() 
                        :
                        Account::where('outlet_id', request('outletId'))
                        ->where('name', 'like', '%' . request('search') . '%')    
                        ->orderBy('code', 'asc')
                        ->get();


        return response()->json([
            'status' => 'success',
            'data' => $data,
            'is_active' => request('is_active')
        ]);
    }

    public function getNextAccountNumber()
    {
        return response()->json([
            'status' => 'success',
            'data' => Account::where('outlet_id', request('outletId'))
            ->where('classification', request('classification'))
            ->get()->last()
        ]);
    }

    public function addAccount(Request $request)
    { 
        $account = Account::create([
            'outlet_id' => request('outlet_id'),
            'name' => request('name'),
            'code' => request('code'),
            'classification' => request('classification'),
            'cash' => request('cash'),
            'is_active' => request('is_active'),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $account
        ]);
    }

    public function editAccount(Request $request, Account $account)
    {
        $account->update([
            'name' => request('name'),
            'code' => request('code'),
            'classification' => request('classification'),
            'cash' => request('cash'),
            'is_active' => request('is_active'),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $account
        ]);
    }

    public function getAccountCategory()
    {
        return response()->json([
            'status' => 'success',
            'data' => AccountCategory::where('name', 'like', '%' . request('name') . '%')->orderBy('name', 'asc')->get()
        ]);
    }

    public function createAccountCategory(Request $request)
    {
        $accountCategory = AccountCategory::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $accountCategory
        ]);
    }

    public function updateAccountCategory(Request $request, AccountCategory $accountCategory)
    {
        $accountCategory->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $accountCategory
        ]);
    }

    public function deleteAccountCategory(AccountCategory $accountCategory)
    {
        //cek apakah kategori akun ini sudah digunakan di akun apa belum
        $account = Account::where('classification', $accountCategory->name)->first();

        if ($account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori akun ini sudah digunakan di akun ' . $account->name
            ]);
        }

        $accountCategory->delete();
        return response()->json([
            'status' => 'success',
            'data' => $accountCategory
        ]);
    }

    public function getExpense()
        {
            $account = Account::where('outlet_id', request('outlet_id'))
                                ->where('code', 'like', '510' . '%')
                                ->where('name', 'like', '%' . request('search') . '%')    
                                ->where('is_active', 1)
                                ->orderBy('code', 'asc')
                                ->limit(5)
                                ->get() ;
                                
            return response()->json([
                'status' => 'success',
                'data' => $account
            ]);
        }
}
