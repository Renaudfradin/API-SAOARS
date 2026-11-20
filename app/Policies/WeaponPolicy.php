<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Weapon;
use App\Policies\Concerns\AdminOnlyMutations;

class WeaponPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Weapon $weapon): bool
    {
        return true;
    }
}
