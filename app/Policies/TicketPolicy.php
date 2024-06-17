<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Models\Ticket;

class TicketPolicy
{
    /**
     * Determine if any user can purchase tickets.
     */
    public function purchase(User $user = null)
    {
        return true;
    }
    public function viewAny(User $user)
    {
        return ($user->type == 'A');
    }

    public function view_my(User $user, Customer $customer)
    {
        return ($user->type == 'C' || $user->customer-> == 'E');
    }

    /**
     * Determine if the user can invalidate tickets.
     */
    public function invalidate(User $user, Ticket $ticket)
    {
        return $user->isEmployee();
    }
}