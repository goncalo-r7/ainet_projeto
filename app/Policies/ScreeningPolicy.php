<?php
namespace App\Policies;
use App\Models\User;


class ScreeningPolicy
{


    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return $user->type;
    }

}
