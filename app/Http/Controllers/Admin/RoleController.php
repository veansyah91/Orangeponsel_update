<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:SUPER ADMIN']);
    }

    public function index()
    {
        return view('admin.account.role.index');
    }
}
