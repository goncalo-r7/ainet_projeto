<?php

namespace App\Policies;
use App\Models\User;

class MoviePolicy
{
    public function before(?User $user): bool|null
    {
        if ($user?->type == 'A') {
            return true;
        }
        return null;
    }

    public function showcase()
    {
        return true;
    }

    public function admin(User $user){
        return($user->type == 'A');
    }


}