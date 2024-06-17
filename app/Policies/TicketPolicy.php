<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class TicketPolicy
{

    public function viewAny(User $user)
    {
        return ($user->type == 'A');
    }

    public function view_my(User $user, Customer $customer)
    {
        return ($user->type == 'C' && $user->customer->id == $customer->id);
    }

    public function invalidate(User $user)
    {
        return ($user->type == 'E');
    }

    public function delete(User $user)
    {
        return ($user->type == 'A');
    }

    public function update(User $user)
    {
        return ($user->type == 'A');
    }

    public function create(User $user)
    {
        return ($user->type == 'A');
    }

    public function download(User $user, Customer $customer)
    {
        return ($user->type == 'A' || ($user->type == 'C' && $user->customer->id == $customer->id));
    }
}