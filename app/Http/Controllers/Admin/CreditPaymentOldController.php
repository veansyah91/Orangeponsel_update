<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditPaymentOldController extends Controller
{
    public function index($partner)
    {
        return view('admin.credit.credit-payment-old', [
            'partner' => $partner
        ]);
    }
}
