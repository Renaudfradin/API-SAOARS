<?php

namespace App\Policies;

use App\Models\Attack;
use App\Models\User;
use App\Policies\Concerns\AdminOnlyMutations;

class AttackPolicy
{
    use AdminOnlyMutations;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Attack $attack): bool
    {
        return true;
    }
}
