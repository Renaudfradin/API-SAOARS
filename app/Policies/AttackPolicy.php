<?php

namespace App\Policies;

use App\Models\Attack;
use App\Models\User;

class AttackPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Attack $attack): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Attack $attack): bool
    {
        return true;
    }

    public function delete(User $user, Attack $attack): bool
    {
        return true;
    }

    public function restore(User $user, Attack $attack): bool
    {
        return true;
    }

    public function forceDelete(User $user, Attack $attack): bool
    {
        return true;
    }
}
