<?php

namespace App\Policies;
use App\Models\User;

class MoviePolicy
{
    public function showcase()
    {
        return true;
    }

    public function admin(User $user){
        return($user->type == 'A');
    }


}
