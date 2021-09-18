<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreditPaymentDelayController extends Controller
{
    public function index($partner)
    {
        return view('admin.credit.credit-delay-payment', [
            'partner' => $partner
        ]);
    }
}
