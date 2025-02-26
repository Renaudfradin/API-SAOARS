<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Weapon;
use Illuminate\Auth\Access\Response;

class WeaponPolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Weapon $weapon): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Weapon $weapon): bool
    {
        return true;
    }

    public function delete(User $user, Weapon $weapon): bool
    {
        return true;
    }

    public function restore(User $user, Weapon $weapon): bool
    {
        return true;
    }

    public function forceDelete(User $user, Weapon $weapon): bool
    {
        return true;
    }
}
