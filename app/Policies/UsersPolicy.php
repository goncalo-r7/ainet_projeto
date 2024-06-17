<?php

namespace App\Policies;

use App\Models\User;

class UsersPolicy
{
    public function access(User $user){
        return $user->type == 'A';
    }
}
