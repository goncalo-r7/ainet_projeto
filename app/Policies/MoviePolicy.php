<?php

namespace App\Policies;
use App\Models\Movies;
use App\Models\User;

class MoviePolicy
{

    public function view(User $user): bool
    {
        return $user->type == 'A';
    }

    public function admin(User $user){
        return($user->type == 'A');
    }


}
