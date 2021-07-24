<?php
namespace App\Helpers;

use App\Model\CreditPartner;
use Illuminate\Support\Facades\DB;

class CreditPartnerHelper {
    public static function getPartner() {
        return $category= CreditPartner::all();
    }
}