<?php
namespace App\Policies;
use App\Models\User;


class ScreeningPolicy
{


    public function viewAny(User $user)
    {
        return ($user->type == 'A' || $user->type == 'E');
    }

    public function view(User $user)
    {
        return ($user->type == 'A' || $user->type == 'E');
    }

}
