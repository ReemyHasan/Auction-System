<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public function create(User $user): bool
    {
        return ($user->type == 1 || $user->type == 3);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return (($user->type === 1 || $user->type === 3) && $product->vendor_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return (($user->type === 1 || $user->type === 3) && $product->vendor_id === $user->id);

    }

    // public function restore(User $user, Product $product): bool
    // {
    //     //
    // }
    // public function forceDelete(User $user, Product $product): bool
    // {
    //     //
    // }
}
