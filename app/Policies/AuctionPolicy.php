<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuctionPolicy
{
    public function create(User $user): bool
    {
        return ($user->type === 1 || $user->type === 3);
    }

    public function update(User $user, Auction $auction): bool
    {
        return (($user->type === 1 || $user->type === 3) && $auction->product->vendor_id === $user->id);
    }

    public function delete(User $user, Auction $auction): bool
    {
        return (($user->type === 1 || $user->type === 3) && $auction->product->vendor_id === $user->id);
    }

    // public function restore(User $user, Auction $auction): bool
    // {
    //     //
    // }
    // public function forceDelete(User $user, Auction $auction): bool
    // {
    //     //
    // }
}
