<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

use App\User;
use Spatie\Permission\Models\Role;

class RoleHelper {

    public static function getRole($userId)
    {   
        $user = User::find($userId);
        $getModelHasRole = DB::table('model_has_roles')->where('model_id', $userId)->first();
        return $role = Role::find($getModelHasRole->role_id);
    }
}