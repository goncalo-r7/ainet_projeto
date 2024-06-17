
<?php

namespace App\Policies;
use App\Models\Purchase;
use App\Models\User;

class PurchasePolicy
{

    public function view(?User $user, Purchase $purchase)
    {
       return  ($user?->type == 'A' || ($user?->type == 'C' && $user?->id == $purchase?->customer?->id));
    }
}
