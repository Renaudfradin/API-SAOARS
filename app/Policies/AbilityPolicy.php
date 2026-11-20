<?php

namespace App\Policies;

use App\Models\Ability;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class AbilityPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ability $ability): bool
    {
        return true;
    }
}
