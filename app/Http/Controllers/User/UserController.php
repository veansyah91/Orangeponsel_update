<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changePassword()
    {
        return view('user.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $updateUser = User::find(Auth::user()->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect(route('home'));
    }
}
