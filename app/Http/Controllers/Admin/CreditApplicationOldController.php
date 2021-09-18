<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditApplicationOldController extends Controller
{
    public function index($partner)
    {
        return view('admin.credit.credit-application-old', [
            'partner' => $partner
        ]);
    }
}
