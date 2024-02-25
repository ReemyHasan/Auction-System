<?php

namespace App\Policies;

use App\Models\CustomerBid;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BidPolicy
{
    public function view(User $user, CustomerBid $customerBid): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return ($user->type === 2 || $user->type === 3);
    }

    public function delete(User $user, CustomerBid $customerBid): bool
    {
        return (($user->type === 2 || $user->type === 3) && $customerBid->customer_id === $user->id);

    }


    // public function restore(User $user, CustomerBid $customerBid): bool
    // {
    //     //
    // }

    // public function forceDelete(User $user, CustomerBid $customerBid): bool
    // {
    //     //
    // }
}
