<?php

namespace App\Policies;

use App\Models\Ability;
use App\Models\User;

class AbilityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ability $ability): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ability $ability): bool
    {
        return true;
    }

    public function delete(User $user, Ability $ability): bool
    {
        return true;
    }

    public function restore(User $user, Ability $ability): bool
    {
        return true;
    }

    public function forceDelete(User $user, Ability $ability): bool
    {
        return true;
    }
}
