<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function create(User $user): bool
    {
        return ($user->type === 1 || $user->type === 3);
    }

    public function update(User $user, Category $category): bool
    {
        return (($user->type === 1 || $user->type === 3) && $category->created_by === $user->id);
    }

    public function delete(User $user, Category $category): bool
    {
        return (($user->type === 1 || $user->type === 3) && $category->created_by === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Category $category): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Category $category): bool
    // {
    //     //
    // }
}
